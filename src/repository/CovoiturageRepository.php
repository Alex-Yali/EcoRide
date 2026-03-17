<?php

namespace App\Repository;

use PDO;
use App\Entity\Covoiturage;
use App\Entity\Utilise;

class CovoiturageRepository extends Repository
{
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
                    JOIN utilise ut ON ut.covoiturage_covoiturage_id = c.covoiturage_id -- On relie le covoiturage à la voiture qu’il utilise 
                    JOIN voiture v ON v.voiture_id = ut.voiture_voiture_id -- Puis on récupère les infos de la voiture utilisée
                    WHERE c.lieu_depart = :depart
                    AND c.lieu_arrivee = :arrivee
                    AND c.date_depart = :date
                    AND c.nb_place > 0 -- Trajets où il reste des places
                    AND pa.chauffeur = 1 -- Récupérer les covoiturages côté conducteur
                    AND (c.statut IS NULL OR c.statut NOT IN ('Demarrer','Terminer')) -- Récupérer les covoiturages non demarrer ni terminer ni annuler
                    AND c.covoiturage_id NOT IN ( -- Empêche l’affichage des covoiturages où l’utilisateur est déjà chauffeur ou passager
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
            JOIN utilise ut ON ut.covoiturage_covoiturage_id = c.covoiturage_id -- On relie le covoiturage à la voiture qu’il utilise 
            JOIN voiture v ON v.voiture_id = ut.voiture_voiture_id -- Puis on récupère les infos de la voiture utilisée
            WHERE c.lieu_depart = :depart
            AND c.lieu_arrivee = :arrivee
            AND c.date_depart > :date -- Covoits futurs
            AND c.nb_place > 0 -- Trajets où il reste des places
            AND pa.chauffeur = 1 -- Récupérer les covoiturages côté conducteur
            AND (c.statut IS NULL OR c.statut NOT IN ('Demarrer','Terminer')) -- Récupérer les covoiturages non demarrer ni terminer ni annuler
            AND c.covoiturage_id NOT IN ( -- Empêche l’affichage des covoiturages où l’utilisateur est déjà chauffeur ou passager
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

    public function covoiturageCars(): array
    {
        $sqlCovoitCars = "SELECT * FROM utilise";
        $stmtCovoitCars = $this->pdo->prepare($sqlCovoitCars);
        $stmtCovoitCars->execute();
        $carsCovoiturages = $stmtCovoitCars->fetchAll(PDO::FETCH_CLASS, Utilise::class);
        return $carsCovoiturages;
    }
}
