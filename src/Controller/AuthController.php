<?php

namespace App\Controller;

use App\Service\AuthServices;

class AuthController extends Controller
{
    /* ============================================= Connexion ============================================= */

    public function connexion(): void
    {
        try {
            $message = '';
            $csrf = generate_csrf_token();
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                // Vérification CSRF
                if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
                    $message = "Erreur CSRF : requête invalide.";
                } else {

                    // Appel fonction d'authentification
                    $authService = new AuthServices();
                    $user = $authService->connexionUtilisateur($email, $password);

                    // Redirige à l'espace utilisateur
                    if ($user) {

                        header('Location: /espace/');
                        exit;
                    }

                    $message = $authService->message;
                }
            }
        } catch (\Exception $e) {

            $message = "Une erreur est survenue : " . $e->getMessage();
        }

        // Afficher la vue
        $this->render("pages/connexion", [
            'message' => $message,
            'csrf' => $csrf ?? '',
        ]);
    }

    /* ============================================ Inscription ============================================= */

    public function inscription(): void
    {
        try {
            $message = '';
            $csrf = generate_csrf_token();
            $pseudo = trim($_POST['pseudo'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $passwordConfirm = trim($_POST['password_confirm'] ?? '');

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                // Vérification CSRF
                if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
                    $message = "Erreur CSRF : requête invalide.";
                } else {

                    // Appel fonction d'authentification
                    $authService = new AuthServices();

                    if ($authService->inscriptionUtilisateur($pseudo, $email, $password, $passwordConfirm)) {
                        header('Location: /espace/');
                        exit;
                    }

                    $message = $authService->message;
                }
            }
        } catch (\Exception $e) {

            $message = "Une erreur est survenue : " . $e->getMessage();
        }

        // Afficher la vue
        $this->render("pages/inscription", [
            'message' => $message,
            'csrf' => $csrf ?? '',
        ]);
    }

    public function deconnexion(): void
    {
        session_destroy();
        header('Location: /');
        exit;
    }
}
