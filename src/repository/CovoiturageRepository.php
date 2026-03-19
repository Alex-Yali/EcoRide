<?php

namespace App\Repository;

use PDO;
use App\Entity\Covoiturage;
use App\Entity\Participe;

class CovoiturageRepository extends Repository
{
    // --------------------------------- Recherche covoits --------------------------------- //

    // Rechercher le covoiturage à la date demandée
    public function findCovoiturage($depart, $arrivee, $date, $idUtilisateur): array
    {
        $sqlDateExacte = " SELECT
                        u.utilisateur_id,
                        u.pseudo,
                        (
                            SELECT AVG(a.note)
                            FROM avis a                               
                            WHERE a.chauffeur_id = u.utilisateur_id
                            AND a.statut = 'valider'
                        ) AS moyenne,
                        c.covoiturage_id,
                        c.lieu_depart,
                        c.date_depart,
                        c.heure_depart,
                        c.lieu_arrivee,
                        c.date_arrivee,
                        c.heure_arrivee,
                        c.nb_place,
                        c.prix_personne,
                        c.statut,
                        v.voiture_id,
                        v.modele,
                        v.energie
                    FROM utilisateur u
                    JOIN participe pa ON pa.utilisateur_utilisateur_id = u.utilisateur_id
                    JOIN covoiturage c ON c.covoiturage_id = pa.covoiturage_covoiturage_id
                    JOIN utilise ut ON ut.covoiturage_covoiturage_id = c.covoiturage_id 
                    JOIN voiture v ON v.voiture_id = ut.voiture_voiture_id 
                    WHERE c.lieu_depart = :depart
                    AND c.lieu_arrivee = :arrivee
                    AND c.date_depart = :date
                    AND c.nb_place > 0 
                    AND pa.chauffeur = 1 
                    AND (c.statut IS NULL OR c.statut NOT IN ('Demarrer','Terminer')) 
                    AND c.covoiturage_id NOT IN ( 
                        SELECT covoiturage_covoiturage_id
                        FROM participe
                        WHERE utilisateur_utilisateur_id = :idUtilisateur
                    )
                    ";
        $stmtDateExacte = $this->pdo->prepare($sqlDateExacte);
        $params = [
            ':depart'  => $depart,
            ':arrivee' => $arrivee,
            ':date'    => $date,
            ':idUtilisateur' => $idUtilisateur ?? 0
        ];
        $stmtDateExacte->execute($params);
        $covoitsDateExacte = $stmtDateExacte->fetchAll(PDO::FETCH_CLASS, Covoiturage::class);
        return $covoitsDateExacte;
    }

    // Rechercher le covoiturage le plus proche de la date demandée si date demandée non existante
    public function findCovoiturageProche($depart, $arrivee, $date, $idUtilisateur): array
    {
        $sqlDateProche = "SELECT 
                u.utilisateur_id,
                u.pseudo,
                (
                    SELECT AVG(a.note)
                    FROM avis a                               
                    WHERE a.chauffeur_id = u.utilisateur_id
                    AND a.statut = 'valider'
                ) AS moyenne,
                c.covoiturage_id,
                c.lieu_depart,
                c.date_depart,
                c.heure_depart,
                c.lieu_arrivee,
                c.date_arrivee,
                c.heure_arrivee,
                c.nb_place,
                c.prix_personne,
                c.statut,
                v.voiture_id,
                v.modele,
                v.energie
            FROM utilisateur u
            JOIN participe pa ON pa.utilisateur_utilisateur_id = u.utilisateur_id
            JOIN covoiturage c ON c.covoiturage_id = pa.covoiturage_covoiturage_id
            JOIN utilise ut ON ut.covoiturage_covoiturage_id = c.covoiturage_id 
            JOIN voiture v ON v.voiture_id = ut.voiture_voiture_id 
            WHERE c.lieu_depart = :depart
            AND c.lieu_arrivee = :arrivee
            AND c.date_depart > :date 
            AND c.nb_place > 0 
            AND pa.chauffeur = 1 
            AND (c.statut IS NULL OR c.statut NOT IN ('Demarrer','Terminer'))
            AND c.covoiturage_id NOT IN ( 
                SELECT covoiturage_covoiturage_id
                FROM participe
                WHERE utilisateur_utilisateur_id = :idUtilisateur
            )
            ORDER BY c.date_depart ASC, c.heure_depart ASC
            LIMIT 1 ";
        $stmtDateProche = $this->pdo->prepare($sqlDateProche);
        $paramsProche = [
            ':depart'  => $depart,
            ':arrivee' => $arrivee,
            ':date'    => $date,
            ':idUtilisateur' => $idUtilisateur ?? 0
        ];
        $stmtDateProche->execute($paramsProche);
        $covoitsDateProche = $stmtDateProche->fetchAll(PDO::FETCH_CLASS, Covoiturage::class);
        return $covoitsDateProche;
    }

    // --------------------------------- Covoit utilisateur participe --------------------------------- //

    // Récupération des covoiturages actifs
    public function mesCovoiturages($idUtilisateur): array
    {
        $sqlMesCovoit = "SELECT 
                        u.utilisateur_id,
                        u.pseudo,
                        c.covoiturage_id,
                        c.lieu_depart,
                        c.date_depart,
                        c.heure_depart,
                        c.lieu_arrivee,
                        c.date_arrivee,
                        c.heure_arrivee,
                        c.nb_place,
                        c.prix_personne,
                        v.voiture_id,
                        v.modele,
                        v.energie,
                        c.statut,
                        u_conducteur.pseudo AS conducteur_pseudo,
                        u_conducteur.utilisateur_id AS conducteur_id,
                        (
                            SELECT AVG(a2.note)
                            FROM avis a2
                            WHERE a2.chauffeur_id = u_conducteur.utilisateur_id
                            AND a2.statut = 'valider'
                        ) AS conducteur_moyenne
                    FROM covoiturage c
                    JOIN participe pa ON pa.covoiturage_covoiturage_id = c.covoiturage_id
                    JOIN utilisateur u ON u.utilisateur_id = pa.utilisateur_utilisateur_id
                    LEFT JOIN participe p_conducteur 
                        ON p_conducteur.covoiturage_covoiturage_id = c.covoiturage_id
                        AND p_conducteur.chauffeur = 1
                    LEFT JOIN utilisateur u_conducteur ON u_conducteur.utilisateur_id = p_conducteur.utilisateur_utilisateur_id
                    JOIN utilise ut ON ut.covoiturage_covoiturage_id = c.covoiturage_id
                    JOIN voiture v ON v.voiture_id = ut.voiture_voiture_id
                    WHERE pa.utilisateur_utilisateur_id = :idUtilisateur
                    AND (c.statut IS NULL OR c.statut NOT IN ('Terminer','Annuler','Valider'))
                    ORDER BY c.date_depart ASC, c.heure_depart ASC
                ";

        $stmtMesCovoit = $this->pdo->prepare($sqlMesCovoit);
        $stmtMesCovoit->execute([
            ':idUtilisateur' => $idUtilisateur
        ]);
        $mesCovoits = $stmtMesCovoit->fetchAll(PDO::FETCH_CLASS, Covoiturage::class);
        return $mesCovoits;
    }

    // Vérifier le rôle de l’utilisateur dans le covoit
    public function roleUtilisateurCovoit($idUtilisateur, $covoiturage_id)
    {
        $sqlCheck = "SELECT * FROM participe 
                     WHERE covoiturage_covoiturage_id = :participe_Covoiturage_id 
                     AND utilisateur_utilisateur_id = :participe_Utilisateur_id";
        $stmtCheck = $this->pdo->prepare($sqlCheck);
        $stmtCheck->execute([
            ':participe_Covoiturage_id' => $covoiturage_id,
            ':participe_Utilisateur_id' => $idUtilisateur
        ]);
        $participant = $stmtCheck->fetch(PDO::FETCH_ASSOC);
        return $participant;
    }

    // Mise à jour du statut du covoiturage
    public function majStatut($statut, $covoiturage_id)
    {
        $sqlStatut = "UPDATE covoiturage SET statut = :statut WHERE covoiturage_id = :covoiturage_id";
        $stmtStatut = $this->pdo->prepare($sqlStatut);
        $stmtStatut->execute([
            ':statut' => $statut,
            ':covoiturage_id' => $covoiturage_id
        ]);
    }

    // Récupérer infos du covoiturage
    public function infosCovoiturages($covoiturage_id)
    {
        $sqlInfos = "SELECT * FROM covoiturage 
                     WHERE covoiturage_id = :covoiturage_id";
        $stmtInfos = $this->pdo->prepare($sqlInfos);
        $stmtInfos->execute([':covoiturage_id' => $covoiturage_id]);
        $infosCovoit = $stmtInfos->fetch(PDO::FETCH_ASSOC);
        return $infosCovoit;
    }

    // Récupérer les passagers
    public function passagersCovoiturages($covoiturage_id)
    {
        $sqlPassagers = "SELECT u.utilisateur_id, u.email, u.pseudo 
                         FROM participe p 
                         JOIN utilisateur u ON u.utilisateur_id = p.utilisateur_utilisateur_id 
                         WHERE p.covoiturage_covoiturage_id = :covoiturage_id
                         AND p.chauffeur = 0";
        $stmtPassagers = $this->pdo->prepare($sqlPassagers);
        $stmtPassagers->execute([':covoiturage_id' => $covoiturage_id]);
        $passagers = $stmtPassagers->fetchAll(PDO::FETCH_ASSOC);
        return $passagers;
    }

    // Rembourser les utilisateurs
    public function rembourserUtilisateur($prix, $idUtilisateur)
    {
        $sqlAjoutCredit = "UPDATE utilisateur SET credits = credits + :prix WHERE utilisateur_id = :utilisateur_id";
        $stmtAjoutCredit = $this->pdo->prepare($sqlAjoutCredit);
        $stmtAjoutCredit->execute([
            ':prix' => $prix,
            ':utilisateur_id' => $idUtilisateur
        ]);
    }

    // Remettre toutes les places disponibles
    public function incrementerPlacesTotales($nbPlaces, $covoiturage_id)
    {
        $sqlPlacesTotales = "UPDATE covoiturage SET nb_place = :nbPlaces WHERE covoiturage_id = :covoiturage_id";
        $stmtPlacesTotales = $this->pdo->prepare($sqlPlacesTotales);
        $stmtPlacesTotales->execute([
            ':nbPlaces' => $nbPlaces,
            ':covoiturage_id' => $covoiturage_id
        ]);
    }

    // Libérer une place (+1)
    public function incrementerPlace($covoiturage_id)
    {
        $sqlPlacePassager = "UPDATE covoiturage SET nb_place = nb_place + 1 WHERE covoiturage_id = :covoiturage_id";
        $stmtPlacePassager = $this->pdo->prepare($sqlPlacePassager);
        $stmtPlacePassager->execute([':covoiturage_id' => $covoiturage_id]);
    }

    // Supprimer la participation du passager
    public function supprimerParticipation($idUtilisateur, $covoiturage_id)
    {
        $sqlSupParticipation = "DELETE FROM participe 
                WHERE utilisateur_utilisateur_id = :idUtilisateur 
                AND covoiturage_covoiturage_id = :covoiturage_id";
        $stmtSupParticipation = $this->pdo->prepare($sqlSupParticipation);
        $stmtSupParticipation->execute([
            ':idUtilisateur' => $idUtilisateur,
            ':covoiturage_id' => $covoiturage_id
        ]);
    }
}
