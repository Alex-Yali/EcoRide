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

            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
                || (($_SERVER['CONTENT_TYPE'] ?? '') === 'application/json');

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                if ($isAjax) {
                    $body = json_decode(file_get_contents('php://input'), true);
                    $email = trim($body['email'] ?? '');
                    $password = $body['password'] ?? '';
                    $csrfToken = $body['csrf_token'] ?? '';
                } else {
                    $csrfToken = $_POST['csrf_token'] ?? '';
                }

                // Vérification CSRF
                if (!verify_csrf_token($csrfToken)) {
                    if ($isAjax) {
                        header('Content-Type: application/json');
                        echo json_encode(["success" => false, "message" => "Erreur CSRF"]);
                        return;
                    }
                    $message = "Erreur CSRF";
                } else {
                    // Appel fonction d'authentification
                    $authService = new AuthServices();
                    $user = $authService->connexionUtilisateur($email, $password);

                    if ($user) {
                        if ($isAjax) {
                            header('Content-Type: application/json');
                            echo json_encode(["success" => true]);
                            return;
                        }
                        // Redirige à l'espace utilisateur
                        header('Location: /espace/');
                        exit;
                    }

                    if ($isAjax) {
                        header('Content-Type: application/json');
                        echo json_encode([
                            "success" => false,
                            "message" => $authService->message
                        ]);
                        return;
                    }
                    $message = $authService->message;
                }
            }
        } catch (\Exception $e) {
            $message = "Une erreur est survenue : " . $e->getMessage();
        }

        $this->render("pages/connexion", [
            'message' => $message,
            'csrf'    => $csrf ?? '',
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

            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
                || (($_SERVER['CONTENT_TYPE'] ?? '') === 'application/json');

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                if ($isAjax) {
                    $body = json_decode(file_get_contents('php://input'), true);
                    $pseudo = trim($body['pseudo'] ?? '');
                    $email = trim($body['email'] ?? '');
                    $password = $body['password'] ?? '';
                    $passwordConfirm = $body['passwordConfirm'] ?? '';
                    $csrfToken = $body['csrf_token'] ?? '';
                } else {
                    $csrfToken = $_POST['csrf_token'] ?? '';
                }

                // Vérification CSRF
                if (!verify_csrf_token($csrfToken)) {
                    if ($isAjax) {
                        header('Content-Type: application/json');
                        echo json_encode(["success" => false, "message" => "Erreur CSRF"]);
                        return;
                    }
                    $message = "Erreur CSRF";
                } else {
                    // Appel fonction d'authentification
                    $authService = new AuthServices();
                    $user = $authService->inscriptionUtilisateur($pseudo, $email, $password, $passwordConfirm);

                    if ($user) {
                        if ($isAjax) {
                            header('Content-Type: application/json');
                            echo json_encode(["success" => true]);
                            return;
                        }
                        // Redirige à l'espace utilisateur
                        header('Location: /espace/');
                        exit;
                    }
                    if ($isAjax) {
                        header('Content-Type: application/json');
                        echo json_encode([
                            "success" => false,
                            "message" => $authService->message
                        ]);
                        return;
                    }
                    $message = $authService->message;
                }
            }
        } catch (\Exception $e) {
            $message = "Une erreur est survenue : " . $e->getMessage();
        }

        $this->render("pages/inscription", [
            'message' => $message,
            'csrf'    => $csrf ?? '',
        ]);
    }

    public function deconnexion(): void
    {
        session_destroy();
        header('Location: /');
        exit;
    }
}
