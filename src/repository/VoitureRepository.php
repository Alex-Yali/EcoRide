<?php

namespace App\Repository;

use PDO;
use App\Entity\Voiture;
use App\db\MongoDB;

class VoitureRepository extends Repository
{
    protected   PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Vérifier si immatriculation existe déjà
    public function checkImmatriculation($immatriculation): bool
    {
        $sqlVoiture = "SELECT voiture_id FROM voiture WHERE immatriculation = :immatriculation";
        $stmtVoiture = $this->pdo->prepare($sqlVoiture);
        $stmtVoiture->execute([':immatriculation' => $immatriculation]);
        return $stmtVoiture->fetch() !== false;
    }

    // Inserer table voiture
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

    // Inserer table marque
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

    // Inserer table détient
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

    // Inserer table gere
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

    // Ajouter les preferences utilisateur
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
        $voituresUtilisateur = $stmtVoituresUtilisateur->fetchAll(PDO::FETCH_ASSOC);
        return $voituresUtilisateur ?: [];
    }
}
