<?php

namespace App\Controller;

use App\Service\CovoiturageServices;

class CovoiturageController extends Controller
{
    public function covoiturage(): void
    {
        try {
            $covoiturageService = new CovoiturageServices();

            $covoitsDateExacte = [];
            $dateCovoit = null;
            $message = '';
            $messageCovoit = '';
            $covoitValide = false;
            $csrf = generate_csrf_token();

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                // Vérification CSRF
                if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
                    $message = "Erreur CSRF : requête invalide.";
                } else {

                    // Récupération des valeurs du formulaire
                    $depart      = trim($_POST['depart'] ?? '');
                    $arrivee     = trim($_POST['arrivee'] ?? '');
                    $date        = trim($_POST['date'] ?? '');
                    $chauffeurId = $_SESSION['user_id'] ?? 0;

                    // Appel fonction de recherche
                    $covoitsDateExacte = $covoiturageService->searchCovoiturage($depart, $arrivee, $date, $chauffeurId);

                    $message = $covoiturageService->message;
                    $messageCovoit = $covoiturageService->messageCovoit;
                    $covoitValide = $covoiturageService->covoitValide;

                    // Appel fonction date formatée
                    $dateCovoit = $covoiturageService->formatDate($covoitsDateExacte);
                }
            }
        } catch (\Exception $e) {
            $message = "Une erreur est survenue : " . $e->getMessage();
        }
        // Afficher la vue
        $this->render("pages/covoiturage", [
            'covoitsDateExacte' => $covoitsDateExacte,
            'message' => $message,
            'messageCovoit' => $messageCovoit,
            'covoitValide' => $covoitValide,
            'dateCovoit' => $dateCovoit,
            'csrf' => $csrf ?? '',
        ]);
    }
}
