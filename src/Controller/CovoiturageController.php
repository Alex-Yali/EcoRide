<?php

namespace App\Controller;

use App\Service\CovoiturageServices;

class CovoiturageController extends Controller
{
    // --------------------------------- Recherche covoits --------------------------------- //
    public function covoiturage(): void
    {
        try {
            $covoiturageService = new CovoiturageServices();

            $covoits = [];
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
                    $covoits = $covoiturageService->searchCovoiturage($depart, $arrivee, $date, $chauffeurId);

                    $message = $covoiturageService->message;
                    $messageCovoit = $covoiturageService->messageCovoit;
                    $covoitValide = $covoiturageService->covoitValide;
                }
            }
        } catch (\Exception $e) {
            $message = "Une erreur est survenue : " . $e->getMessage();
        }
        // Afficher la vue
        $this->render("pages/covoiturage", [
            'covoits' => $covoits,
            'message' => $message,
            'messageCovoit' => $messageCovoit,
            'covoitValide' => $covoitValide,
            'csrf' => $csrf ?? '',
        ]);
    }

    // --------------------------------- Covoit utilisateur participe --------------------------------- //
    public function mesCovoiturages(): void
    {
        try {
            $idUtilisateur = $_SESSION['user_id'] ?? 0;
            $covoiturage_id = $_POST['covoiturage_id'] ?? 0;
            $csrf = generate_csrf_token();

            $covoituraServices = new CovoiturageServices();

            // Récupérer les covoiturages actifs
            $mesCovoits = $covoituraServices->mesCovoiturages($idUtilisateur);

            if (empty($mesCovoits)) {
                $message = "Aucun covoiturage en cours.";
            } else {
                $message = '';
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $covoiturage_id > 0) {

                // Vérification CSRF
                if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
                    $message = "Erreur CSRF : requête invalide.";
                } else {
                    $action = $_POST['action'] ?? '';
                    $message = $covoituraServices->gestionStatutCovoit($idUtilisateur, $covoiturage_id, $action);

                    // Rafraîchissement
                    header("Location: /mesCovoiturages");
                    exit();
                }
            }
        } catch (\Exception $e) {
            $message = "Une erreur est survenue : " . $e->getMessage();
        }
        $this->render('pages/mesCovoiturages', [
            'mesCovoits' => $mesCovoits,
            'message' => $message,
            'csrf' => $csrf ?? '',
        ]);
    }
}
