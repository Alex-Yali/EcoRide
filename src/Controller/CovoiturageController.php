<?php

namespace App\Controller;

use App\Service\CovoiturageServices;
use App\Repository\CovoiturageRepository;
use App\db\Mysql;
use App\db\MongoDB;
use PDO;

class CovoiturageController extends Controller

{

    private PDO $pdo;
    private $collectionPreferences;

    public function __construct()
    {
        $this->pdo = Mysql::getInstance()->getPDO();
        $this->collectionPreferences = MongoDB::getInstance()->getCollection('preferences');
    }
    /* ============================================ Recherche covoits ============================================= */

    public function covoiturage(): void
    {
        try {
            $covoiturageService = new CovoiturageServices($this->pdo, $this->collectionPreferences);

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

    /* ============================================ Covoit utilisateur participe ============================================= */

    public function mesCovoiturages(): void
    {
        try {
            $idUtilisateur = $_SESSION['user_id'] ?? 0;
            $covoiturage_id = $_POST['covoiturage_id'] ?? 0;
            $message = "";
            $mesCovoits = [];
            $csrf = generate_csrf_token();

            $covoituraServices = new CovoiturageServices($this->pdo, $this->collectionPreferences);

            // Récupérer les covoiturages actifs
            $mesCovoits = $covoituraServices->mesCovoiturages($idUtilisateur);

            if (empty($mesCovoits)) {
                $message = "Aucun covoiturage en cours.";
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $covoiturage_id > 0) {

                // Vérification CSRF
                if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
                    $message = "Erreur CSRF : requête invalide.";
                } else {
                    $action = $_POST['action'] ?? '';
                    $covoituraServices->gestionStatutCovoit($idUtilisateur, $covoiturage_id, $action);

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

    /* ============================================ Historique covoit utilisateur participe ============================================= */

    public function mesCovoituragesHistorique(): void
    {
        try {
            $idUtilisateur = $_SESSION['user_id'] ?? 0;
            $message = "";
            $mesCovoitsHistorique = [];
            $csrf = generate_csrf_token();

            $covoiturageServices = new CovoiturageServices($this->pdo, $this->collectionPreferences);

            // Récupérer les historiques des covoits où l'utilisateur à participé
            $mesCovoitsHistorique = $covoiturageServices->mesCovoituragesHistorique($idUtilisateur);

            if (empty($mesCovoitsHistorique)) {
                $message = "Aucun historique de covoiturage.";
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'envoyer') {

                // Vérification CSRF
                if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
                    $message = "Erreur CSRF : requête invalide.";
                } else {
                    $covoiturageServices->traiterAvis($_POST, $idUtilisateur);

                    // Rafraîchissement
                    header("Location: /historique");
                    exit();
                }
            }
            // Calculer pour chaque covoiturage si l'avis a déjà été donné
            $covoiturageRepository = new CovoiturageRepository();

            foreach ($mesCovoitsHistorique as $c) {
                $conducteurId = $c->getConducteurId();
                $covoitId = $c->getCovoiturageId();

                // Ajouter une propriété à l'objet
                $dejaAvis = $covoiturageRepository->avisDejaDonne($idUtilisateur, $covoitId, $conducteurId);
                $c->setDejaAvis($dejaAvis);
            }
        } catch (\Exception $e) {
            $message = "Une erreur est survenue : " . $e->getMessage();
        }
        $this->render('pages/historique', [
            'mesCovoitsHistorique' => $mesCovoitsHistorique,
            'message' => $message,
            'csrf' => $csrf ?? '',
        ]);
    }

    /* ============================================ Detail covoit participe ============================================= */

    public function participerCovoit()
    {
        try {
            $pdo = Mysql::getInstance()->getPDO();
            $collectionPreferences = MongoDB::getInstance()->getCollection('preferences');

            $covoiturageService = new CovoiturageServices($pdo, $collectionPreferences);

            $idUtilisateur = $_SESSION['user_id'] ?? 0;
            $message = "";
            $messageCovoit = "";
            $participeCovoit = false;
            $csrf = generate_csrf_token();

            // Récupération de l’ID dans l’URL
            $idCovoit = $_GET['id'] ?? '';
            if (!ctype_digit($idCovoit)) {
                header('Location: /covoiturage/');
                exit;
            }

            $result = $covoiturageService->covoitDetail($idCovoit);
            if ($result) {
                $covoitDetail = $result['covoitDetail'];
                $dateDetailCovoit = $result['dateDetailCovoit'];
                $preferences = $result['preferences'];
                $imageVoiture = $result['imageVoiture'];
                $dureeCovoit = $result['dureeCovoit'];
            } else {
                $covoitDetail = null;
                $dateDetailCovoit = '';
                $preferences = [];
                $imageVoiture = [];
                $dureeCovoit = "";
            }


            if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'oui') {

                // Vérification CSRF
                if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
                    $message = "Erreur CSRF : requête invalide.";
                } else {

                    // Participer au covoit
                    $participeCovoit = $covoiturageService->participerCovoit($idCovoit, $idUtilisateur);
                    $messageCovoit = $covoiturageService->messageCovoit ?? "";
                }
            }
        } catch (\Exception $e) {
            $message = "Une erreur est survenue : " . $e->getMessage();
        }
        $this->render('pages/detail', [
            'participeCovoit' => $participeCovoit,
            'dateDetailCovoit' => $dateDetailCovoit,
            'covoitDetail' => $covoitDetail,
            'preferences' => $preferences,
            'imageVoiture' => $imageVoiture,
            'dureeCovoit' => $dureeCovoit,
            'message' => $message,
            'messageCovoit' => $messageCovoit,
            'idUtilisateur' => $idUtilisateur,
            'csrf' => $csrf ?? '',
        ]);
    }
}
