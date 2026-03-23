<?php

namespace App\Repository;

use App\Entity\infosCompte;
use PDO;
use App\Entity\Role;
use App\Entity\Utilisateur;

class UtilisateurRepository extends Repository
{
    /* ============================================ Infos utilisateur ============================================= */

    public function infosUtilisateur(int $userId): ?Utilisateur
    {
        $sqlInfos = "SELECT * FROM utilisateur u
                     WHERE u.utilisateur_id = :user_id";
        $stmtinfos = $this->pdo->prepare($sqlInfos);
        $stmtinfos->execute([
            ':user_id' => $userId
        ]);
        $infosUtilisateur = $stmtinfos->fetchObject(Utilisateur::class);
        return $infosUtilisateur ?: null;
    }

    public function allRole(): array
    {
        $sqlAllRole = "SELECT role_id, libelle
                       FROM role";
        $stmtAllRole = $this->pdo->prepare($sqlAllRole);
        $stmtAllRole->execute();
        $allRole = $stmtAllRole->fetch(PDO::FETCH_CLASS, Role::class);
        return $allRole;
    }

    /* ============================================ Création compte employé ============================================= */

    // 1. Vérifier le role
    public function checkRoleEmploye($role)
    {
        $sqlCheckRoleEmploye = "SELECT role_id, libelle
                                FROM role
                                WHERE libelle = :role";
        $stmtCheckRoleEmploye = $this->pdo->prepare($sqlCheckRoleEmploye);
        $stmtCheckRoleEmploye->execute([':role' => $role]);
        $checkRoleEmploye = $stmtCheckRoleEmploye->fetchObject(Role::class);
        return $checkRoleEmploye;
    }

    // 2. Ajouter le role
    public function ajouterRoleEmploye($role)
    {
        $sqlAjoutRoleEmploye = "INSERT INTO role (libelle) VALUES (:role)";
        $stmtAjoutRoleEmploye = $this->pdo->prepare($sqlAjoutRoleEmploye);
        $stmtAjoutRoleEmploye->execute([':role' => $role]);
        return $this->pdo->lastInsertId();
    }

    // 3. Ajouter relation utilisateur-role dans possede
    public function ajouterRolePossede($idUtilisateur, $idRole)
    {
        $sqlPossede = "INSERT INTO possede (utilisateur_utilisateur_id, role_role_id)
                       VALUES (:idUtilisateur, :idRole)";
        $stmtPossede = $this->pdo->prepare($sqlPossede);
        $stmtPossede->execute([
            ':idUtilisateur' => $idUtilisateur,
            ':idRole' => $idRole
        ]);
    }

    /* ============================================ Suppression compte employé ============================================= */

    // Recuperer les comptes
    public function checkUtilisateurOrEmploye()
    {
        $sqlCompte = "SELECT u.utilisateur_id,
                        u.pseudo,
                        u.email,
                        u.statut,
                        r.libelle
                    FROM utilisateur u
                    JOIN possede p ON p.utilisateur_utilisateur_id = u.utilisateur_id
                    JOIN role r ON r.role_id = p.role_role_id
                    WHERE r.libelle IN ('utilisateur', 'employe')
                    AND u.statut IN ('actif')";
        $stmtCompte = $this->pdo->prepare($sqlCompte);
        $stmtCompte->execute();
        $compte = $stmtCompte->fetchAll(PDO::FETCH_CLASS, infosCompte::class);
        return $compte;
    }

    // Mise à jour statut
    public function updateStatut($idCompte)
    {
        $sqlUpdateStatut = "UPDATE utilisateur SET statut = :statut 
                            WHERE utilisateur_id = :idUtilisateur";
        $stmtUpdateStatut = $this->pdo->prepare($sqlUpdateStatut);
        $stmtUpdateStatut->execute([
            ':idUtilisateur' => $idCompte,
            ':statut' => "suspendu"
        ]);
    }
}
