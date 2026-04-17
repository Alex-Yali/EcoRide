<?php

namespace App\repository;

use PDO;
use App\Entity\utilisateur;

class AuthRepository extends Repository
{

    /* ============================================= Connexion ============================================= */

    //Vérifier si les infos renseignées correspondent à un utilisateur
    public function infosValide($email): ?Utilisateur
    {
        $sqlInfosValide = "SELECT utilisateur_id, pseudo, email, password, credits, statut FROM utilisateur 
                           WHERE email = :email LIMIT 1 ";
        $stmtInfosValide = $this->pdo->prepare($sqlInfosValide);
        $stmtInfosValide->execute([
            ':email' => $email
        ]);
        $stmtInfosValide->setFetchMode(PDO::FETCH_CLASS, Utilisateur::class);
        $userValide = $stmtInfosValide->fetch();
        return $userValide ?: null;
    }

    // Mot de passe en clair : on vérifie l'égalite puis on hache et met à jour
    public function updateHash($newHash, $user_id): bool
    {
        $sqlUpdatePass = "UPDATE utilisateur SET password = :hash WHERE utilisateur_id = :id";
        $stmtUpdatePass = $this->pdo->prepare($sqlUpdatePass);
        $hashUpdate = $stmtUpdatePass->execute([
            ':hash' => $newHash,
            ':id' => $user_id
        ]);
        return $hashUpdate;
    }

    /* ============================================ Inscription ============================================= */

    //Vérifier si l'utilisateur existe déjà
    public function checkUser($email, $pseudo): array
    {
        $sqlDejaInscri = "SELECT * FROM utilisateur WHERE email = :email OR pseudo = :pseudo LIMIT 1";
        $stmtDejaInscri = $this->pdo->prepare($sqlDejaInscri);
        $stmtDejaInscri->execute([
            ':email' => $email,
            ':pseudo' => $pseudo,
        ]);
        $userCheck = $stmtDejaInscri->fetchAll(PDO::FETCH_CLASS, Utilisateur::class);
        return $userCheck;
    }

    // Ajout utilisateur table utilisateur
    public function addUser($email, $pseudo, $startCredit, $hashedPassword): int
    {
        $sqlInscri = "INSERT INTO utilisateur (pseudo, email, password, credits) 
                      VALUES (:pseudo, :email, :password, :credits)";
        $stmtInscri = $this->pdo->prepare($sqlInscri);
        $stmtInscri->execute([
            ':email' => $email,
            ':pseudo' => $pseudo,
            ':credits' =>  $startCredit,
            ':password' => $hashedPassword
        ]);
        $userId = $this->pdo->lastInsertId();
        return $userId;
    }

    // Ajout role table possede
    public function addRolePossede($user_id, $role_id = 3): bool
    {
        $sqlAddRole = "INSERT INTO possede (utilisateur_utilisateur_id, role_role_id)
                       VALUES (:utilisateur, :role)";
        $stmtAddRole = $this->pdo->prepare($sqlAddRole);
        $roleAdd = $stmtAddRole->execute([
            ':utilisateur' => $user_id,
            ':role' => $role_id
        ]);
        return $roleAdd;
    }
}
