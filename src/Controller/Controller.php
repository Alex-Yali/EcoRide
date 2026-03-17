<?php

namespace App\Controller;

use App\db\Mysql;
use App\Repository\EspaceRepository;

class Controller
{
    protected function render(string $path, array $params = []): void
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Ajouter automatiquement les infos utilisateur
        $params['idUtilisateur'] = $_SESSION['user_id'] ?? null;
        $params['roleUtilisateur'] = $_SESSION['user_roles'] ?? [];

        // Si user_roles est vide mais que user_id existe, récupérer depuis la BDD
        if (!empty($params['idUtilisateur']) && empty($params['roleUtilisateur'])) {
            $espaceRepository = new EspaceRepository(Mysql::getInstance()->getPDO());
            $roles = $espaceRepository->roleUtilisateur($params['idUtilisateur']);

            // Transformer en tableau
            $roleLabels = [];
            foreach ($roles as $roleObj) {
                if (method_exists($roleObj, 'getLibelle')) {
                    $roleLabels[] = $roleObj->getLibelle();
                }
            }
            $params['roleUtilisateur'] = $roleLabels;

            // Stocker en session
            $_SESSION['roleUtilisateur'] = $roleLabels;
        }

        $filePath = APP_ROOT . "/templates/$path.php";

        if (!file_exists($filePath)) {
            echo "Le fichier $filePath n'existe pas";
        } else {
            extract($params);
            require_once $filePath;
        }
    }
}
