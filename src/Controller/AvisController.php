<?php

namespace App\Controller;

use App\Service\AvisServices;

class AvisController extends Controller
{
    /* ============================================ Afficher avis chauffeur ============================================= */
    public function afficherAvis()
    {
        try {
            $message = "";
            $idCovoit = $_GET['id'] ?? '';
            $avisChauffeur = [];

            $avisServices = new AvisServices;

            // Récupération avis chauffeur
            $avisChauffeur = $avisServices->afficherAvis($idCovoit);
        } catch (\Exception $e) {
            $message = "Une erreur est survenue : " . $e->getMessage();
        }
        // Afficher la vue
        $this->render("pages/avis", [
            'avisChauffeur' => $avisChauffeur,
            'message' => $message,
        ]);
    }
}
