<?php

namespace App\Service;

use App\repository\AuthRepository;
use App\Db\Mysql;

class AuthServices
{
    public string $message = '';

    public function connexionUtilisateur($email, $password)
    {
        // Vérifier si un champ est vide
        if ($email === '' || $password === '') {
            $this->message = "Veuillez renseigner l'email et le mot de passe.";
            return false;
        }

        // Vérifier si les infos sont bonnes
        $connexionRepository = new AuthRepository();
        $userValide = $connexionRepository->infosValide($email);

        // Vérifier si l'utilisateur existe
        if (!$userValide) {
            $this->message = "Email ou mot de passe incorrect.";
            return false;
        }

        // Vérifier le statut du compte
        $statut = $userValide->getStatut();

        if ($statut == "suspendu") {
            $this->message = "Compte suspendu";
            return false;
        }

        // Si infos bonnes on vérifie si le mot de passe est hashé (bcrypt)
        $dbPass = $userValide->getPassword();
        $is_hashed = (strpos($dbPass, '$2y$') === 0);

        // Mot de passe déjà haché -> vérifier avec password_verify
        if ($is_hashed) {
            if (password_verify($password, $dbPass)) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $userValide->getUtilisateurId();
                $_SESSION['user_email'] = $userValide->getEmail();
                $_SESSION['user_pseudo'] = $userValide->getPseudo();
                $_SESSION['user_credits'] = $userValide->getCredits();

                return $userValide;
            }
            $this->message = "Email ou mot de passe incorrect.";
            return false;
        }

        // Mot de passe en clair : on vérifie l'égalite puis on hache et met à jour
        if ($password === $dbPass) {

            // On hash le mot de passe
            $newHash = password_hash($password, PASSWORD_BCRYPT);

            // On modifie dans la bdd
            $connexionRepository->updateHash(
                $newHash,
                $userValide->getUtilisateurId()
            );

            // Ensuite connecter l'utilisateur
            session_regenerate_id(true);
            $_SESSION['user_id'] = $userValide->getUtilisateurId();
            $_SESSION['user_email'] = $userValide->getEmail();
            $_SESSION['user_pseudo'] = $userValide->getPseudo();
            $_SESSION['user_credits'] = $userValide->getCredits();

            return $userValide;
        }
    }

    public function inscriptionUtilisateur($pseudo, $email, $password, $passwordConfirm)
    {
        $startCredit = 20;

        // Vérifier si un champ est vide
        if ($pseudo === '' || $email === '' || $password === '' || $passwordConfirm === '') {
            $this->message = "Veuillez renseigner tous les champs.";
            return false;
        }

        // Vérifier la longueur du pseudo
        if (mb_strlen($pseudo) > 10) {
            $this->message = "Le pseudo ne doit pas dépasser 10 caractères.";
            return false;
        }

        // Vérifier si le mot de passe respect notre demande
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{9,}$/', $password)) {
            $this->message = "Le mot de passe doit contenir au moins 9 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.";
            return false;
        }

        // Vérifier si les mots de passe correspondent
        if ($passwordConfirm !== $password) {
            $this->message = "Les mots de passe ne correspondent pas";
            return false;
        }

        // Vérifier si l'utilisateur existe déjà
        $inscriptionRepository = new AuthRepository();
        $userCheck = $inscriptionRepository->checkUser($email, $pseudo);

        if ($userCheck) {
            $this->message = "Un utilisateur avec ce pseudo ou cet email existe déjà.";
            return false;
        }
        // Hashage
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        try {
            // Commencer transaction
            $pdo = Mysql::getInstance()->getPDO();
            $pdo->beginTransaction();

            // Ajout utilisateur bdd
            $userId = $inscriptionRepository->addUser($email, $pseudo, $startCredit, $hashedPassword);

            // Ajout rôle bdd
            $inscriptionRepository->addRolePossede($userId, 3);

            $pdo->commit();

            // Stockage session
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_pseudo'] = $pseudo;
            $_SESSION['email'] = $email;
            $_SESSION['user_credits'] = $startCredit;
            $_SESSION['inscription_ok'] = true;
            return true;
        } catch (\PDOException $e) {
            $pdo->rollBack();
            $this->message = "Erreur lors de l'ajout";
            return false;
        }
    }
}
