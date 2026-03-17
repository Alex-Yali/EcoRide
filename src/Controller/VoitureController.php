<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use App\db\Mysql;
use App\Service\VoitureServices;

class VoitureController extends Controller
{
    public function vehicule(): void
    {
        $idUtilisateur = $_SESSION['user_id'] ?? null;
        $message = '';
        $voituresUtilisateur = false;
        $infosUtilisateur = null;

        if (!$idUtilisateur) {
            $message = "Utilisateur non connecté.";
        } else {
            try {
                //  Afficher les voitures
                $voitureServices = new VoitureServices();
                $voituresUtilisateur = $voitureServices->voitureUtilisateur(Mysql::getInstance()->getPDO(), $idUtilisateur);

                //  Recuperer les infos utilisateur
                $utilisateurRepository = new UtilisateurRepository();
                $infosUtilisateur = $utilisateurRepository->infosUtilisateur($idUtilisateur);
            } catch (\Exception $e) {
                $message = "Une erreur est survenue : " . $e->getMessage();
            }
        }

        // Afficher la vue
        $this->render("pages/vehicule", [
            'infosUtilisateur' => $infosUtilisateur,
            'voituresUtilisateur' => $voituresUtilisateur,
            'message' => $message,
        ]);
    }
}
