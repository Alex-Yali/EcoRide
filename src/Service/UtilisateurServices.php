<?php

namespace App\Service;

use App\repository\AuthRepository;
use App\Db\Mysql;
use App\repository\UtilisateurRepository;

class UtilisateurServices
{

    public string $message = '';
    public string $messageCompte = '';
    public bool $compteValide = false;
    public string $messageSusp = '';
    public bool $compteSusp = false;

    /* ============================================ Création compte employé ============================================= */

    public function ajouterEmploye($pseudo, $email, $password, $passwordConfirm): bool
    {
        $startCredit = 20;
        $role = "employe";
        $pdo = Mysql::getInstance()->getPDO();

        // Vérifier si un champ est vide
        if ($pseudo === '' || $email === '' || $password === '' || $passwordConfirm === '') {
            $this->message = "Veuillez renseigner tous les champs.";
            $this->compteValide = false;
            return false;
        }

        // Vérifier la longueur du pseudo
        if (mb_strlen($pseudo) > 10) {
            $this->message = "Le pseudo ne doit pas dépasser 10 caractères.";
            $this->compteValide = false;
            return false;
        }

        // Vérifier si le mot de passe respect notre demande
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{9,}$/', $password)) {
            $this->message = "Le mot de passe doit contenir au moins 9 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.";
            $this->compteValide = false;
            return false;
        }

        // Vérifier si les mots de passe correspondent
        if ($passwordConfirm !== $password) {
            $this->message = "Les mots de passe ne correspondent pas";
            $this->compteValide = false;
            return false;
        }

        // Vérifier si l'utilisateur existe déjà
        $inscriptionRepository = new AuthRepository($pdo);
        $userCheck = $inscriptionRepository->checkUser($email, $pseudo);

        if ($userCheck) {
            $this->messageCompte = "Un utilisateur avec ce pseudo ou cet email existe déjà.";
            $this->compteValide = false;
            return false;
        }
        // Hashage
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        try {
            // Commencer transaction
            $pdo->beginTransaction();

            // Ajout employe bdd
            $userId = $inscriptionRepository->addUser($email, $pseudo, $startCredit, $hashedPassword);

            // Vérifier si le role employe existe
            $utilisateurRepository = new UtilisateurRepository($pdo);
            $checkRoleEmploye = $utilisateurRepository->checkRoleEmploye($role);

            if ($checkRoleEmploye) {
                $idRole = $checkRoleEmploye->getRoleId();
            } else {
                // Ajouter le role employe
                $idRole = $utilisateurRepository->ajouterRoleEmploye($role);
            }
            // Ajouter relation utilisateur-role dans possede
            $utilisateurRepository->ajouterRolePossede($userId, $idRole);

            $pdo->commit();

            $this->messageCompte  = "Compte créé avec succès.";
            $this->compteValide = true;
            return true;
        } catch (\PDOException $e) {
            $pdo->rollBack();
            $this->message = "Erreur lors de la création";
            return false;
        }
    }

    /* ============================================ Suspendre compte utilisateur/employé ============================================= */

    public function suspendreCompte($idCompte): bool
    {
        try {
            $pdo = Mysql::getInstance()->getPDO();

            // Commencer transaction
            $pdo->beginTransaction();

            // Mise à jour statut
            $utilisateurRepository = new UtilisateurRepository($pdo);
            $utilisateurRepository->updateStatut($idCompte);

            $pdo->commit();

            $this->messageSusp  = "Compte suspendu avec succès.";
            $this->compteSusp = true;
            return true;
        } catch (\PDOException $e) {
            $pdo->rollBack();
            $this->message = "Erreur lors de la suspension";
            return false;
        }
    }
}
