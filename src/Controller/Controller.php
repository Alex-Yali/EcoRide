<?php

namespace App\Controller;

use App\db\Mysql;
use App\Repository\EspaceRepository;
use App\Repository\UtilisateurRepository;
use App\Service\CovoiturageServices;

class Controller
{
    protected function render(string $path, array $params = []): void
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $params['idUtilisateur'] = $_SESSION['user_id'] ?? null;
        $params['roleUtilisateur'] = $_SESSION['user_roles'] ?? [];

        // Ajouter automatiquement les infos utilisateur au global
        $infosUtilisateur = null;
        if (!empty($params['idUtilisateur'])) {
            $utilisateurRepository = new UtilisateurRepository(Mysql::getInstance()->getPDO());
            $infosUtilisateur = $utilisateurRepository->infosUtilisateur($params['idUtilisateur']);
        }
        $params['infosUtilisateur'] = $infosUtilisateur;

        // Ajouter automatiquement la moyenne utilisateur connecté au global
        $moyenneUtilisateur = null;
        if (!empty($params['idUtilisateur'])) {
            $espaceRepository = new EspaceRepository(Mysql::getInstance()->getPDO());
            $moyenneUtilisateur = $espaceRepository->Moyenne($params['idUtilisateur']);
        }
        $params['moyenneUtilisateur'] = $moyenneUtilisateur;

        // Ajouter automatiquement le role utilisateur connecté au global
        if (!empty($params['idUtilisateur']) && empty($params['roleUtilisateur'])) {
            $espaceRepository = new EspaceRepository(Mysql::getInstance()->getPDO());
            $roles = $espaceRepository->roleUtilisateur($params['idUtilisateur']);

            $roleLabels = [];
            foreach ($roles as $role) {
                if (method_exists($role, 'getLibelle')) {
                    $roleLabels[] = $role->getLibelle();
                }
            }
            $params['roleUtilisateur'] = $roleLabels;
            $_SESSION['roleUtilisateur'] = $roleLabels;
        }

        // if (!empty($params['covoiturages'])) {
        //     $covoiturageService = new CovoiturageServices();
        //     $params['dateCovoit'] = $covoiturageService->formatDate($params['covoiturages']);
        // }

        $filePath = APP_ROOT . "/templates/$path.php";

        if (!file_exists($filePath)) {
            echo "Le fichier $filePath n'existe pas";
        } else {
            extract($params);
            require_once $filePath;
        }
    }
}
