<?php

namespace App\Repository;

use App\Entity\Covoiturage;
use App\Entity\Utilisateur;
use App\Entity\Role;
use PDO;

class EspaceRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    /* ============================================ Gestion radio utilisateur ============================================= */

    // Sélection statut passager / chauffeur
    public function statutUtilisateur(int $userId): ?Utilisateur
    {
        $sqlStatutUtilisateur = "SELECT passager, chauffeur 
                                 FROM utilisateur
                                 WHERE utilisateur_id = :user_id";
        $stmtStatutUtilisateur = $this->pdo->prepare($sqlStatutUtilisateur);
        $stmtStatutUtilisateur->execute([
            ':user_id' => $userId
        ]);
        $statut = $stmtStatutUtilisateur->fetch(PDO::FETCH_ASSOC);

        if (!$statut) {
            return null;
        }

        // Créer un objet Utilisateur et assigner les valeurs
        $statutUtilisateur = new Utilisateur();
        $statutUtilisateur->setPassager((int)$statut['passager']);
        $statutUtilisateur->setChauffeur((int)$statut['chauffeur']);
        return $statutUtilisateur;
    }

    // Vérifie si l'utilisateur a déjà une voiture
    public function checkVoitureUtilisateur(int $userId): bool
    {
        $sqlCheckVoiture = "SELECT 1
                            FROM gere
                            WHERE utilisateur_utilisateur_id = :user_id
                            LIMIT 1";
        $stmtCheckVoiture = $this->pdo->prepare($sqlCheckVoiture);
        $stmtCheckVoiture->execute([':user_id' => $userId]);
        $checkVoiture = $stmtCheckVoiture->fetchColumn();
        return $checkVoiture !== false;
    }

    // Récupérer la moyenne des notes
    public function Moyenne(int $chauffeurId): ?float
    {
        $sqlMoyenne = "SELECT AVG(a.note) AS moyenne
                       FROM avis a
                       WHERE a.chauffeur_id = :chauffeurId
                       AND a.statut = 'valider';
                   ";

        $stmtMoyenne = $this->pdo->prepare($sqlMoyenne);
        $stmtMoyenne->execute([
            ':chauffeurId' => $chauffeurId
        ]);

        $moyenne = $stmtMoyenne->fetch();
        return $moyenne['moyenne'] !== null ? (float)$moyenne['moyenne'] : null;
    }

    // Récupérer les rôles
    public function roleUtilisateur(int $userId): array
    {
        $sqlRoles = "SELECT r.role_id, r.libelle
                     FROM role r
                     JOIN possede p ON p.role_role_id = r.role_id
                     WHERE p.utilisateur_utilisateur_id = :user_id";
        $stmtRoles = $this->pdo->prepare($sqlRoles);
        $stmtRoles->execute([
            ':user_id' => $userId
        ]);
        $roles = $stmtRoles->fetchAll(PDO::FETCH_CLASS, Role::class);
        return $roles ?: [];
    }

    // Switch passager / chauffeur
    public function switchStatutUtilisateur(int $userId, bool $passager, bool $chauffeur)
    {
        $passager = (int)$passager;
        $chauffeur = (int)$chauffeur;

        // Mise à jour du statut
        $sqlUpdateStatut = "UPDATE utilisateur 
                            SET passager = :passager, chauffeur = :chauffeur
                            WHERE utilisateur_id = :user_id";
        $stmtUpdateStatut = $this->pdo->prepare($sqlUpdateStatut);
        $stmtUpdateStatut->execute([
            ':passager' => $passager,
            ':chauffeur' => $chauffeur,
            ':user_id' => $userId
        ]);
    }

    /* ============================================ Graphiques ============================================= */

    // Graphiques
    public function graphique()
    {
        // -- Graphique 1 -- //

        // 1-Récupérer les covoiturages par date
        $sqlCovoitDate = " SELECT date_depart, COUNT(*) AS total
                           FROM covoiturage
                           GROUP BY date_depart
                           ORDER BY date_depart ASC";
        $stmtCovoitDate = $this->pdo->prepare($sqlCovoitDate);
        $stmtCovoitDate->execute();

        $data = $stmtCovoitDate->fetchAll(PDO::FETCH_ASSOC);

        // 2-Préparer les données pour Chart.js
        $date = [];
        $total = [];

        foreach ($data as $row) {
            $date[] = $row['date_depart'];
            $total[] = (int)$row['total'];
        }

        // -- Graphique 2 -- //

        // 1-Récupérer les crédits par covoit
        $sqlCovoitCredit = " SELECT date_depart, COUNT(*) * 2 AS totalCredit
                             FROM covoiturage
                             GROUP BY date_depart
                             ORDER BY date_depart ASC";
        $stmtCovoitCredit = $this->pdo->prepare($sqlCovoitCredit);
        $stmtCovoitCredit->execute();

        $data2 = $stmtCovoitCredit->fetchAll(PDO::FETCH_ASSOC);

        // 2-Préparer les données pour Chart.js
        $date2 = [];
        $totalCredit = [];

        foreach ($data2 as $row) {
            $date2[] = $row['date_depart'];
            $totalCredit[] = (int)$row['totalCredit'];
        }

        // -- Total crédits -- //
        $sqlTotalCredit = "SELECT COUNT(*) * 2 AS totalCredits FROM covoiturage";
        $stmtTotalCredit = $this->pdo->prepare($sqlTotalCredit);
        $stmtTotalCredit->execute();
        $totalCredits = $stmtTotalCredit->fetch(PDO::FETCH_ASSOC);
        return [
            'graph1' => ['dates' => $date, 'total' => $total],
            'graph2' => ['dates' => $date2, 'totalCredits' => $totalCredit],
            'totalCredits' => $totalCredits
        ];
    }
}
