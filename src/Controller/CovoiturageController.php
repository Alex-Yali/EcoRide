<?php

namespace App\Controller;

use App\Service\CovoiturageServices;
use App\Repository\CovoiturageRepository;
use App\db\Mysql;
use App\db\MongoDB;
use App\Repository\AvisRepository;
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
            $covoitDetail =  [];
            $dateDetailCovoit = '';
            $preferences = [];
            $imageVoiture = [];
            $dureeCovoit = "";
            $participeCovoit = false;
            $totalAvis = null;
            $csrf = generate_csrf_token();

            // Récupération de l’ID dans l’URL
            $idCovoit = $_GET['id'] ?? null;

            if (!$idCovoit || !is_numeric($idCovoit)) {
                die("ID invalide : " . var_dump($idCovoit));
            }

            $idCovoit = (int) $idCovoit;

            // Récupérer nombre avis 
            $avisRepository = new AvisRepository();
            $totalAvis = $avisRepository->totalAvis($idCovoit);

            $result = $covoiturageService->covoitDetail($idCovoit) ?? [];

            $covoitDetail = $result['covoitDetail'] ?? [];
            $dateDetailCovoit = $result['dateDetailCovoit'] ?? '';
            $preferences = $result['preferences'] ?? [];
            $imageVoiture = $result['imageVoiture'] ??  '';
            $dureeCovoit = $result['dureeCovoit'] ?? '';
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
            'totalAvis' => $totalAvis,
            'csrf' => $csrf ?? '',
        ]);
    }

    public function covoitsActifsUtilisateur(int $idUtilisateur): array
    {
        $covoiturageServices = new CovoiturageServices($this->pdo, $this->collectionPreferences);
        $mesCovoits = $covoiturageServices->mesCovoiturages($idUtilisateur) ?? [];
        return $mesCovoits;
    }

    public function covoitsHistoriqueUtilisateur(int $idUtilisateur): array
    {
        $covoiturageServices = new CovoiturageServices($this->pdo, $this->collectionPreferences);
        $mesCovoitsHistorique = $covoiturageServices->mesCovoituragesHistorique($idUtilisateur) ?? [];
        return $mesCovoitsHistorique;
    }
}
