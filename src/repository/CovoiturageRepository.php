<?php

namespace App\repository;

use PDO;
use App\Entity\covoiturage;

class CovoiturageRepository extends Repository
{
    /* ============================================ Recherche covoits ============================================= */

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
                    ORDER BY c.date_depart ASC, c.heure_depart ASC";
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

    /* ============================================ Covoit utilisateur participe ============================================= */

    // Récupérer les covoiturages actifs
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
        $stmtMesCovoit->execute([':idUtilisateur' => $idUtilisateur]);
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

    /* ============================================ Historique covoit utilisateur participe ============================================= */

    // Récupérer les covoiturages non actifs
    public function mesCovoituragesHistorique($idUtilisateur): array
    {
        $sqlMesCovoitsHistorique = "SELECT 
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
                                        WHERE a2.chauffeur_id = conducteur_id
                                        AND a2.statut = 'valider'
                                        ) AS conducteur_moyenne
                                    FROM covoiturage c
                                    JOIN participe pa ON pa.covoiturage_covoiturage_id = c.covoiturage_id
                                    JOIN utilisateur u ON u.utilisateur_id = pa.utilisateur_utilisateur_id
                                    LEFT JOIN participe p_conducteur 
                                        ON p_conducteur.covoiturage_covoiturage_id = c.covoiturage_id
                                        AND p_conducteur.chauffeur = 1
                                    LEFT JOIN utilisateur u_conducteur ON u_conducteur.utilisateur_id = p_conducteur.utilisateur_utilisateur_id
                                    LEFT JOIN utilise ut ON ut.covoiturage_covoiturage_id = c.covoiturage_id
                                    LEFT JOIN voiture v ON v.voiture_id = ut.voiture_voiture_id 
                                    WHERE pa.utilisateur_utilisateur_id = :idUtilisateur
                                    AND c.statut IN ('Terminer','Annuler','Valider')
                                    ORDER BY c.date_depart ASC, c.heure_depart ASC
                                    ";
        $stmtMesCovoitsHistorique = $this->pdo->prepare($sqlMesCovoitsHistorique);
        $stmtMesCovoitsHistorique->execute([':idUtilisateur' => $idUtilisateur]);
        $mesCovoitsHistorique = $stmtMesCovoitsHistorique->fetchAll(PDO::FETCH_CLASS, Covoiturage::class);
        return $mesCovoitsHistorique;
    }

    public function avisDejaDonne($idUtilisateur, $covoiturage_id, $conducteur_id)
    {
        $sqlCheck = "SELECT COUNT(*) FROM depose d
                     JOIN avis a ON a.avis_id = d.avis_avis_id 
                     WHERE a.covoiturage_id = :covoiturage
                     AND d.utilisateur_utilisateur_id = :utilisateur
                     AND a.chauffeur_id = :chauffeur";
        $stmtCheck = $this->pdo->prepare($sqlCheck);
        $stmtCheck->execute([
            ':utilisateur' => $idUtilisateur,
            ':covoiturage' => $covoiturage_id,
            ':chauffeur' => $conducteur_id
        ]);
        return $stmtCheck->fetchColumn() > 0;
    }

    public function ajouterAvis($commentaire, $rating, $conducteur_id, $covoiturage_id, $etatAvis)
    {
        $sqlAddAvis = "INSERT INTO avis (commentaire, note, statut, chauffeur_id, covoiturage_id, etat)
                       VALUES (:commentaire, :note, :statut, :chauffeur, :covoiturage, :etat)";
        $stmtAddAvis = $this->pdo->prepare($sqlAddAvis);
        $stmtAddAvis->execute([
            ':commentaire' => $commentaire,
            ':note' => $rating,
            ':statut' => 'en attente',
            ':chauffeur' => $conducteur_id,
            ':covoiturage' => $covoiturage_id,
            ':etat' => $etatAvis
        ]);
        return $this->pdo->lastInsertId();
    }

    public function ajouterDepose($idUtilisateur, $idAvis)
    {
        $sqlAddDepose = "INSERT INTO depose (utilisateur_utilisateur_id, avis_avis_id)
                         VALUES (:utilisateur, :avis)";
        $stmtAddDepose = $this->pdo->prepare($sqlAddDepose);
        $stmtAddDepose->execute([
            ':utilisateur' => $idUtilisateur,
            ':avis' => $idAvis
        ]);
    }

    public function ajouterCredits($prixParPersonne, $conducteur_id)
    {
        $sqlAddCredits = "UPDATE utilisateur 
                          SET credits = credits + :credit 
                          WHERE utilisateur_id = :id";
        $stmtAddCredits = $this->pdo->prepare($sqlAddCredits);
        $stmtAddCredits->execute([
            ':credit' => $prixParPersonne,
            ':id' => $conducteur_id
        ]);
    }

    /* ============================================ Detail covoit participe ============================================= */

    // Fonction check si participe deja au covoiturage
    function participeDeja($pdo, $idUtilisateur, $idCovoit)
    {
        $sqlCheck = "SELECT COUNT(*) FROM participe p
                     JOIN covoiturage c ON c.covoiturage_id = p.covoiturage_covoiturage_id 
                     WHERE c.covoiturage_id = :covoiturage
                     AND p.utilisateur_utilisateur_id = :utilisateur
                     AND p.passager = :passager";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute([
            ':utilisateur' => $idUtilisateur,
            ':covoiturage' => $idCovoit,
            ':passager' => 1
        ]);
        return $stmtCheck->fetchColumn() > 0;
    }

    // Récupérer les infos du detail du covoiturage
    public function detailCovoit($idCovoit)
    {
        $sqlDetail = "SELECT 
                        u.utilisateur_id,
                        u.pseudo,
                        u.credits,
                        a.note,
                        (
                            SELECT AVG(a2.note)
                            FROM avis a2
                            WHERE a2.chauffeur_id = u.utilisateur_id
                            AND a2.statut = 'valider'
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
                        v.voiture_id,
                        v.modele,
                        v.energie,
                        m.marque_id,
                        m.libelle AS marqueVoiture,
                        a.commentaire
                    FROM covoiturage c
                    JOIN participe pa ON pa.covoiturage_covoiturage_id = c.covoiturage_id AND pa.chauffeur = 1
                    JOIN utilisateur u ON u.utilisateur_id = pa.utilisateur_utilisateur_id
                    LEFT JOIN utilise ut ON ut.covoiturage_covoiturage_id = c.covoiturage_id
                    LEFT JOIN voiture v ON v.voiture_id = ut.voiture_voiture_id
                    LEFT JOIN depose d ON u.utilisateur_id = d.utilisateur_utilisateur_id
                    LEFT JOIN avis a ON d.avis_avis_id = a.avis_id
                    LEFT JOIN detient de ON de.voiture_voiture_id = v.voiture_id
                    LEFT JOIN marque m ON m.marque_id = de.marque_marque_id
                    WHERE c.covoiturage_id = :idCovoit
                    AND pa.chauffeur = 1
                    ";

        $stmtDetail = $this->pdo->prepare($sqlDetail);
        $stmtDetail->execute([':idCovoit' => $idCovoit]);
        return $stmtDetail->fetch(PDO::FETCH_ASSOC);
    }

    // Enlever crédits a l'utilisateur
    public function removeCredits($prixCovoit, $idUtilisateur)
    {
        $sqlRemoveCredits = "UPDATE utilisateur 
                            SET credits = credits - :credits 
                            WHERE utilisateur_id = :idUtilisateur";
        $stmtRemoveCredits = $this->pdo->prepare($sqlRemoveCredits);
        $stmtRemoveCredits->execute([
            'credits' => $prixCovoit,
            'idUtilisateur' => $idUtilisateur
        ]);
    }

    // Ajouter utilisateur au covoiturage
    public function participerCovoit($idUtilisateur, $idCovoit)
    {
        $sqlAddUtilisateur = "INSERT INTO participe (utilisateur_utilisateur_id, covoiturage_covoiturage_id, passager)
                              VALUES (:utilisateur, :covoiturage, :passager)";
        $stmtAddUtilisateur = $this->pdo->prepare($sqlAddUtilisateur);
        $stmtAddUtilisateur->execute([
            ':utilisateur' => $idUtilisateur,
            ':covoiturage' => $idCovoit,
            ':passager' => 1
        ]);
    }

    // Enlever place dispo au covoiturage
    public function removePlace($idCovoit)
    {
        $sqlRemovePlace = " UPDATE covoiturage
                            SET nb_place = nb_place - 1
                            WHERE covoiturage_id = :idCovoit";
        $stmtRemovePlace = $this->pdo->prepare($sqlRemovePlace);
        $stmtRemovePlace->execute([':idCovoit' => $idCovoit]);
    }
}
