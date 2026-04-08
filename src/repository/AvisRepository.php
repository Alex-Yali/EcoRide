<?php

namespace App\Repository;

use PDO;
use App\Entity\Avis;
use App\Entity\InfosCovoitAvis;

class AvisRepository extends Repository
{
    /* ============================================ Gestion des avis ============================================= */

    public function avis()
    {
        // Récupérer les avis
        $sqlAvis = "SELECT 
                    u.utilisateur_id,
                    u.pseudo,
                    c.prix_personne,
                    ua.utilisateur_id AS auteur_id,
                    ua.pseudo AS auteur_pseudo,
                    a.commentaire,
                    a.chauffeur_id,
                    a.avis_id,
                    a.etat,
                    a.note,
                    (
                        SELECT AVG(a2.note)
                        FROM avis a2
                        WHERE a2.chauffeur_id = u.utilisateur_id
                        AND a2.statut = 'valider'
                    ) AS moyenne
                FROM utilisateur u
                JOIN avis a ON a.chauffeur_id = u.utilisateur_id
                JOIN depose d ON d.avis_avis_id = a.avis_id
                JOIN utilisateur ua ON ua.utilisateur_id = d.utilisateur_utilisateur_id  -- auteur de l'avis
                JOIN covoiturage c ON c.covoiturage_id = a.covoiturage_id
                WHERE a.statut = 'en attente'
                ORDER BY a.avis_id ASC
                ";

        $stmtAvis = $this->pdo->prepare($sqlAvis);
        $stmtAvis->execute();
        $avis = $stmtAvis->fetchAll(PDO::FETCH_CLASS, Avis::class);
        return $avis;
    }

    // Récupérer les infos de l'avis en cours de validation
    public function infosAvisValide($idAvis)
    {
        $sqlInfo = "SELECT a.etat, c.prix_personne, a.chauffeur_id
                    FROM avis a
                    JOIN covoiturage c ON c.covoiturage_id = a.covoiturage_id
                    WHERE a.avis_id = :idAvis";
        $stmtInfo = $this->pdo->prepare($sqlInfo);
        $stmtInfo->execute([':idAvis' => $idAvis]);
        $info = $stmtInfo->fetch(PDO::FETCH_ASSOC);
        return $info;
    }

    // Valider l'avis
    public function validerAvis($idAvis, $idUtilisateur): bool
    {
        $sqlValider = "UPDATE avis 
                   SET statut = 'valider', 
                       employe_id = :idEmploye, 
                       date_avis = :dateAvis
                   WHERE avis_id = :idAvis 
                   AND statut = 'en attente'";

        $stmtValider = $this->pdo->prepare($sqlValider);
        $stmtValider->execute([
            ':idAvis' => $idAvis,
            ':idEmploye' => $idUtilisateur,
            ':dateAvis' => date('Y-m-d')
        ]);
        return $stmtValider->rowCount() > 0;
    }

    // Ajouter credits
    public function ajouterCredits($prixParPersonne, $idChauffeur)
    {
        $sqlAddCredits = "UPDATE utilisateur 
                          SET credits = credits + :credit 
                          WHERE utilisateur_id = :idChauffeur";
        $stmtAddCredits = $this->pdo->prepare($sqlAddCredits);
        $stmtAddCredits->execute([
            ':credit' => $prixParPersonne,
            ':idChauffeur' => $idChauffeur
        ]);
    }

    /* ============================================ Refuser avis ============================================= */

    // Refuser l'avis
    public function refuserAvis($idAvis, $idUtilisateur)
    {
        $sqlRefuser = "UPDATE avis SET statut = 'refuser', employe_id = :idEmploye 
                       WHERE avis_id = :idAvis";
        $stmtRefuser = $this->pdo->prepare($sqlRefuser);
        $stmtRefuser->execute([
            ':idAvis' => $idAvis,
            ':idEmploye' => $idUtilisateur
        ]);
    }

    // Récupérer les infos du voyage de l'avis à traiter
    public function infosCovoitAvis($idAvis)
    {
        $sqlInfosCovoitAvis = "SELECT 
                    a.covoiturage_id,
                    a.note,
                    ua.pseudo AS passager_pseudo,
                    ua.email AS passager_email,
                    u.pseudo AS chauffeur_pseudo,
                    u.email AS chauffeur_email,
                    c.date_depart,
                    c.lieu_depart,
                    c.date_arrivee,
                    c.lieu_arrivee
                FROM avis a
                JOIN depose d ON d.avis_avis_id = a.avis_id
                JOIN utilisateur ua ON ua.utilisateur_id = d.utilisateur_utilisateur_id  -- auteur de l'avis
                JOIN utilisateur u ON u.utilisateur_id = a.chauffeur_id -- chauffeur
                JOIN covoiturage c ON c.covoiturage_id = a.covoiturage_id
                WHERE a.statut = 'en attente'
                AND a.avis_id = :idAvis
                ";
        $stmtInfosCovoitAvis = $this->pdo->prepare($sqlInfosCovoitAvis);
        $stmtInfosCovoitAvis->execute([':idAvis' => $idAvis]);
        $stmtInfosCovoitAvis->setFetchMode(PDO::FETCH_CLASS, InfosCovoitAvis::class);
        $infosCovoitAvis = $stmtInfosCovoitAvis->fetch();
        return $infosCovoitAvis;
    }

    /* ============================================ Historique avis ============================================= */

    // Historique des avis traités par employé
    public function historiqueAvis($idUtilisateur)
    {
        $sqlAvisCheck = "SELECT 
                            a.avis_id,
                            a.employe_id,
                            a.statut,
                            a.note,
                            a.commentaire,
                            ua.pseudo AS auteur_pseudo 
                        FROM avis a
                        JOIN depose d ON d.avis_avis_id = a.avis_id
                        JOIN utilisateur ua ON ua.utilisateur_id = d.utilisateur_utilisateur_id  -- auteur de l'avis
                        WHERE a.statut IN ('valider','refuser')
                        AND a.employe_id = :idEmploye
                        ";
        $stmtAvisCheck = $this->pdo->prepare($sqlAvisCheck);
        $stmtAvisCheck->execute([
            ':idEmploye' => $idUtilisateur
        ]);
        $avisCheck = $stmtAvisCheck->fetchAll(PDO::FETCH_CLASS, Avis::class);
        return $avisCheck;
    }

    /* ============================================ Afficher avis chauffeur ============================================= */

    public function afficherAvis($idCovoit)
    {
        $sqlAvis = "SELECT 
                u.utilisateur_id AS chauffeur_id,
                u.pseudo AS chauffeur_pseudo,
                ua.utilisateur_id AS auteur_id,
                ua.pseudo AS auteur_pseudo,
                a.commentaire,
                a.date_avis,
                a.note,
                (
                    SELECT AVG(a2.note)
                    FROM avis a2
                    WHERE a2.chauffeur_id = u.utilisateur_id
                    AND a2.statut = 'valider'
                ) AS moyenne
            FROM utilisateur u
            JOIN avis a ON a.chauffeur_id = u.utilisateur_id
            JOIN depose d ON d.avis_avis_id = a.avis_id
            JOIN utilisateur ua ON ua.utilisateur_id = d.utilisateur_utilisateur_id  
            JOIN participe pa ON pa.utilisateur_utilisateur_id = u.utilisateur_id
            JOIN covoiturage c ON c.covoiturage_id = pa.covoiturage_covoiturage_id
            WHERE a.statut = 'valider'
            AND c.covoiturage_id = :idCovoit
            AND pa.chauffeur = 1
            ORDER BY a.date_avis DESC
            ";
        $stmtAvis = $this->pdo->prepare($sqlAvis);
        $stmtAvis->execute(['idCovoit' => $idCovoit]);
        return $stmtAvis->fetchAll(PDO::FETCH_ASSOC);
    }

    public function totalAvis($idCovoit)
    {
        $sqlTotalAvis = "SELECT count(*) AS total 
                         FROM avis a
                         JOIN participe pa ON pa.utilisateur_utilisateur_id = a.chauffeur_id
                         JOIN covoiturage c ON c.covoiturage_id = pa.covoiturage_covoiturage_id
                         WHERE c.covoiturage_id = :idCovoit
                         AND pa.chauffeur = 1
                         AND a.statut = 'valider'";
        $stmtTotalAvis = $this->pdo->prepare($sqlTotalAvis);
        $stmtTotalAvis->execute(['idCovoit' => $idCovoit]);
        return $stmtTotalAvis->fetch(PDO::FETCH_ASSOC);;
    }
}
