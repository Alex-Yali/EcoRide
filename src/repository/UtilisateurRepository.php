<?php

namespace App\Repository;

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
        $allRole = $stmtAllRole->fetchAll(PDO::FETCH_CLASS, Role::class);
        return $allRole;
    }
}
