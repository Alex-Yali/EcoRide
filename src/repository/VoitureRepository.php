<?php

namespace App\Repository;

use PDO;
use App\db\MongoDB;
use App\Entity\Covoiturage;
use App\Entity\Voiture;

class VoitureRepository extends Repository
{
    protected   PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /* ============================================ Ajout voiture ============================================= */

    // 1. Vérifier si immatriculation existe déjà
    public function checkImmatriculation($immatriculation): bool
    {
        $sqlVoiture = "SELECT voiture_id FROM voiture WHERE immatriculation = :immatriculation";
        $stmtVoiture = $this->pdo->prepare($sqlVoiture);
        $stmtVoiture->execute([':immatriculation' => $immatriculation]);
        return $stmtVoiture->fetch() !== false;
    }

    // 2. Inserer table voiture
    public function insertVoiture($modele, $immatriculation, $couleur, $dateImmat, $energie, $place)
    {
        $sqlAjoutVoiture = "INSERT INTO voiture (modele, immatriculation, couleur, date_premiere_immatriculation, energie, nb_place)
                            VALUES (:modele, :immatriculation, :couleur, :dateImmat, :energie, :place)";
        $stmtAjoutVoiture = $this->pdo->prepare($sqlAjoutVoiture);
        $stmtAjoutVoiture->execute([
            ':modele' => ucfirst(strtolower($modele)),
            ':immatriculation' => $immatriculation,
            ':couleur' => strtolower($couleur),
            ':dateImmat' => $dateImmat,
            ':energie' => strtolower($energie),
            ':place' => $place
        ]);
    }

    // Derniere insertion PDO
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    // 3. Inserer table marque
    public function ajoutMarque($marque)
    {
        $sqlMarque = "SELECT marque_id FROM marque WHERE libelle = :marque";
        $stmtMarque = $this->pdo->prepare($sqlMarque);
        $stmtMarque->execute([':marque' => ucfirst(strtolower($marque))]);
        $resultMarque = $stmtMarque->fetch(\PDO::FETCH_ASSOC);

        if ($resultMarque) return $resultMarque['marque_id'];

        $sqlAjoutMarque = "INSERT INTO marque (libelle) VALUES (:marque)";
        $stmtAjoutMarque = $this->pdo->prepare($sqlAjoutMarque);
        $stmtAjoutMarque->execute([':marque' => ucfirst(strtolower($marque))]);

        return $this->pdo->lastInsertId();
    }

    // 4. Inserer table détient
    public function insertDetient($idVoiture, $idMarque)
    {
        $sqlDetient = "INSERT INTO detient (voiture_voiture_id, marque_marque_id)
                       VALUES (:idVoiture, :idMarque)";
        $stmtDetient = $this->pdo->prepare($sqlDetient);
        $stmtDetient->execute([
            ':idVoiture' => $idVoiture,
            ':idMarque' => $idMarque
        ]);
    }

    // 5. Inserer table gere
    public function insertGere($userId, $idVoiture)
    {
        $sqlGere = "INSERT INTO gere (utilisateur_utilisateur_id, voiture_voiture_id)
                    VALUES (:user_id, :idVoiture)";
        $stmtGere = $this->pdo->prepare($sqlGere);
        $stmtGere->execute([
            ':user_id' => $userId,
            ':idVoiture' => $idVoiture
        ]);
    }

    // 6. Ajouter les preferences utilisateur
    public function updatePreferencesMongo($userId, $tabac, $animal, $autre)
    {
        $mongo = MongoDB::getInstance();

        $collectionPreferences = $mongo->getCollection('preferences');

        $preferencesMongo = [
            "tabac" => $tabac,
            "animal" => $animal,
            "autre" => $autre
        ];
        $collectionPreferences->updateOne(
            ["utilisateur_id" => (int)$userId],
            ['$set' => ["preferences" => $preferencesMongo]],
            ['upsert' => true]
        );
    }

    /* ============================================ Affichage voiture chauffeur ============================================= */

    // Récupérer les véhicules de l'utilisateur connecté
    public function voitureUtilisateur($userId)
    {
        $sqlVoituresUtilisateur = "SELECT v.voiture_id, v.modele, v.immatriculation, v.couleur, v.date_premiere_immatriculation, v.energie, m.libelle
                                   FROM voiture v
                                   JOIN gere g ON g.voiture_voiture_id = v.voiture_id
                                   JOIN utilisateur u ON u.utilisateur_id = g.utilisateur_utilisateur_id
                                   JOIN detient d ON d.voiture_voiture_id = v.voiture_id
                                   JOIN marque m ON m.marque_id = d.marque_marque_id
                                   WHERE g.utilisateur_utilisateur_id = :userId
                                   ORDER BY v.voiture_id";

        $stmtVoituresUtilisateur = $this->pdo->prepare($sqlVoituresUtilisateur);
        $stmtVoituresUtilisateur->execute([':userId' => $userId]);
        $voituresUtilisateur = $stmtVoituresUtilisateur->fetchAll(PDO::FETCH_CLASS, Voiture::class);
        return $voituresUtilisateur ?: [];
    }

    /* ============================================ Ajouter trajet ============================================= */

    // 1. Vérifier que la voiture appartient à l'utilisateur via la table 'gere'
    public function checkVoitureUtilisateur($idUtilisateur, $idVoiture)
    {
        $sqlCheckVoitureUtilisateur = "SELECT voiture_voiture_id 
                                       FROM gere 
                                       WHERE utilisateur_utilisateur_id = :idUtilisateur 
                                       AND voiture_voiture_id = :idVoiture";

        $stmtCheckVoitureUtilisateur = $this->pdo->prepare($sqlCheckVoitureUtilisateur);
        $stmtCheckVoitureUtilisateur->execute([
            ':idUtilisateur' => $idUtilisateur,
            ':idVoiture' => $idVoiture
        ]);
        $checkVoitureUtilisateur = $stmtCheckVoitureUtilisateur->fetchColumn();
        return $checkVoitureUtilisateur;
    }

    // 2. Vérifier si l'utilisateur a déjà ce covoiturage
    public function checkCovoitUtilisateur($idUtilisateur, $dateDepart, $heureDepart, $depart, $dateArrivee, $heureArrivee, $destination)
    {
        $sqlCheckCovoitUtilisateur = "SELECT c.covoiturage_id
                        FROM covoiturage c
                        JOIN participe p 
                            ON p.covoiturage_covoiturage_id = c.covoiturage_id
                        WHERE p.utilisateur_utilisateur_id = :idUtilisateur
                            AND c.date_depart = :dateDepart
                            AND c.heure_depart = :heureDepart
                            AND c.lieu_depart = :lieuDepart
                            AND c.date_arrivee = :dateArrivee
                            AND c.heure_arrivee = :heureArrivee
                            AND c.lieu_arrivee = :lieuArrivee";

        $stmtCheckCovoitUtilisateur = $this->pdo->prepare($sqlCheckCovoitUtilisateur);
        $stmtCheckCovoitUtilisateur->execute([
            ':idUtilisateur' => $idUtilisateur,
            ':dateDepart' => $dateDepart,
            ':heureDepart' => $heureDepart,
            ':lieuDepart' => $depart,
            ':dateArrivee' => $dateArrivee,
            ':heureArrivee' => $heureArrivee,
            ':lieuArrivee' => $destination
        ]);
        $checkCovoitUtilisateur = $stmtCheckCovoitUtilisateur->fetchObject(Covoiturage::class);
        return $checkCovoitUtilisateur;
    }

    // 3. Ajouter le covoiturage
    public function ajouterTrajet($dateDepart, $heureDepart, $depart, $dateArrivee, $heureArrivee, $destination, $places, $prix)
    {
        $sqlAjoutTrajet = "INSERT INTO covoiturage (date_depart, heure_depart, lieu_depart, date_arrivee, heure_arrivee, lieu_arrivee, nb_place, prix_personne)
                           VALUES (:dateDepart, :heureDepart, :lieuDepart, :dateArrivee, :heureArrivee, :lieuArrivee, :places, :prix)";
        $stmtAjoutTrajet = $this->pdo->prepare($sqlAjoutTrajet);
        $stmtAjoutTrajet->execute([
            ':dateDepart' => $dateDepart,
            ':heureDepart' => $heureDepart,
            ':lieuDepart' => $depart,
            ':dateArrivee' => $dateArrivee,
            ':heureArrivee' => $heureArrivee,
            ':lieuArrivee' => $destination,
            ':places' => $places,
            ':prix' => $prix
        ]);
        $trajet = $this->pdo->lastInsertId();
        return $trajet;
    }

    // 4. Ajouter relation utilisateur-covoiturage dans participe
    public function participerCovoit($idUtilisateur, $idTrajet)
    {
        $sqlParticipe = "INSERT INTO participe (utilisateur_utilisateur_id, covoiturage_covoiturage_id, chauffeur, passager)
                         VALUES (:idUtilisateur, :idCovoiturage, :chauffeur, :passager)";
        $stmtParticipe = $this->pdo->prepare($sqlParticipe);
        $stmtParticipe->execute([
            ':idUtilisateur' => $idUtilisateur,
            ':idCovoiturage' => $idTrajet,
            ':chauffeur' => 1,
            ':passager' => 0
        ]);
    }

    // 5. Ajouter relation covoiturage–voiture dans utilise
    public function utiliseVoiturecovoit($voiture, $idTrajet)
    {
        $sqlUtiliseVoiturecovoit = "INSERT INTO utilise (voiture_voiture_id, covoiturage_covoiturage_id)
                            VALUES (:idVoiture, :idCovoiturage)";
        $stmtUtiliseVoiturecovoit = $this->pdo->prepare($sqlUtiliseVoiturecovoit);
        $stmtUtiliseVoiturecovoit->execute([
            ':idVoiture' => $voiture,
            ':idCovoiturage' => $idTrajet
        ]);
    }

    // 6. Déduire 2 crédits à l'utilisateur
    public function removeCredits($idUtilisateur, $prixTrajet)
    {
        $sqlRemoveCredits = "UPDATE utilisateur 
                             SET credits = credits - :prixTrajet 
                             WHERE utilisateur_id = :idUtilisateur";
        $stmtRemoveCredits = $this->pdo->prepare($sqlRemoveCredits);
        $stmtRemoveCredits->execute([
            'prixTrajet' => $prixTrajet,
            'idUtilisateur' => $idUtilisateur
        ]);
    }
}
