<?php

namespace App\Controller;

use App\Repository\EspaceRepository;
use App\Repository\CovoiturageRepository;
use App\Repository\UtilisateurRepository;
use App\Service\EspaceServices;
use App\Service\UtilisateurServices;
use App\Service\CovoiturageServices;
use App\Service\VoitureServices;
use App\Service\AvisServices;
use PDO;
use App\db\Mysql;
use App\db\MongoDB;

class EspaceController extends Controller
{
    private PDO $pdo;
    private $collectionPreferences;

    public function __construct()
    {
        $this->pdo = Mysql::getInstance()->getPDO();
        $this->collectionPreferences = MongoDB::getInstance()->getCollection('preferences');
    }

    public function espace(): void
    {
        $idUtilisateur = $_SESSION['user_id'] ?? null;
        $radio = 'passager';
        $message = '';
        $messageVoiture = '';
        $voitureExiste = false;
        $voitureValide = false;
        $messageCompte = '';
        $compteValide = false;
        $comptes = '';
        $messageSusp = '';
        $compteSusp = false;
        $graphiques = false;
        $totalCovoitUtilisateur = null;
        $totalTrajetUtilisateur = null;
        $totalVoitureUtilisateur = null;
        $totalCovoitActif = null;
        $totalCovoitInactif = null;
        $totalAvisActif = null;
        $totalAvisInactif = null;
        $mesCovoits = [];
        $mesCovoitsHistorique = [];
        $voituresUtilisateur = false;
        $avis = [];
        $infosCovoitAvis = [];
        $avisCheck = false;
        $totalCompteActif = null;
        $totalCompteSuspendu = null;
        $csrf = generate_csrf_token();

        if (!$idUtilisateur) {
            $message = "Utilisateur non connecté.";
        } else {
            try {
                // Repository
                $espaceRepository = new EspaceRepository(Mysql::getInstance()->getPDO());
                $covoiturageRepository = new CovoiturageRepository();
                $utilisateurRepository = new UtilisateurRepository();

                // Services
                $espaceServices = new EspaceServices();
                $covoiturageServices = new CovoiturageServices($this->pdo, $this->collectionPreferences);
                $utilisateurServices = new UtilisateurServices();
                $voitureServices = new VoitureServices();
                $avisServices = new AvisServices;

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                    // Vérification CSRF
                    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
                        $message = "Erreur CSRF : requête invalide.";
                    } else {

                        // Gérer le switch de statut
                        if (($_POST['formType'] ?? '') === 'switchRadio') {
                            $radioSwitch = $_POST['user-statut'] ?? 'passager';
                            $passager = ($radioSwitch === 'passager' || $radioSwitch === 'lesDeux') ? 1 : 0;
                            $chauffeur = ($radioSwitch === 'chauffeur' || $radioSwitch === 'lesDeux') ? 1 : 0;

                            $espaceRepository->switchStatutUtilisateur($idUtilisateur, $passager, $chauffeur);
                        }

                        // Ajouter la voiture
                        if (($_POST['formType'] ?? '') === 'ajoutVoiture') {
                            $voitureValide = $espaceServices->ajouterVoiture($_POST, $idUtilisateur, Mysql::getInstance()->getPDO());
                            $messageVoiture = $espaceServices->messageVoiture;
                        }

                        // Ajouter compte employé
                        if (($_POST['formType'] ?? '') === 'ajoutCompte') {

                            // Recherche principale
                            $pseudo = trim($_POST['pseudo'] ?? '');
                            $email = trim($_POST['email'] ?? '');
                            $password = trim($_POST['password'] ?? '');
                            $passwordConfirm = trim($_POST['password_confirm'] ?? '');

                            $compteValide = $utilisateurServices->ajouterEmploye($pseudo, $email, $password, $passwordConfirm);
                            $message = $utilisateurServices->message;
                            $messageCompte = $utilisateurServices->messageCompte;
                        }

                        // Suspendre compte utilisateur/employé
                        if (isset($_POST['compte'])) {

                            $idCompte = intval($_POST['compte']);

                            $compteSusp = $utilisateurServices->suspendreCompte($idCompte);
                            $messageSusp = $utilisateurServices->messageSusp;
                        }

                        // Ajouter avis
                        if ($_POST['action'] ?? '' === 'envoyer') {
                            // Récupération des données du formulaire
                            $avis = $_POST['avis'] ?? '';
                            $rating = $_POST['rating'] ?? '';
                            $commentaire = trim($_POST['commentaire'] ?? '');
                            $covoiturage_id = intval($_POST['covoiturage_id'] ?? 0);

                            $covoiturageServices->traiterAvis($_POST, $idUtilisateur, $covoiturage_id, $avis, $commentaire, $rating);

                            // Rafraîchissement
                            header("Location: /espace/");
                            exit();
                        }

                        // Gestion Démarrer / Terminer / Annuler covoiturage
                        if (!empty($_POST['covoiturage_id']) && !empty($_POST['action'])) {
                            $covoiturage_id = $_POST['covoiturage_id'];
                            $action = $_POST['action'] ?? '';

                            $covoiturageServices->gestionStatutCovoit($idUtilisateur, $covoiturage_id, $action);

                            header("Location: /espace/");
                            exit();
                        }

                        // Valider avis
                        if (isset($_POST['valider'])) {

                            $idAvis = $_POST['valider'];

                            $avisServices->validerAvis($idAvis, $idUtilisateur);

                            header("Location: /espace/");
                            exit;
                        }

                        // Refuser l'avis
                        if (isset($_POST['refuser'])) {

                            $idAvis = $_POST['refuser'];

                            $avisServices->refuserAvis($idAvis, $idUtilisateur);

                            header("Location: /espace/");
                            exit;
                        }
                    }
                }

                // Récupérer compte utilisateur/employé
                $comptes = $utilisateurRepository->checkUtilisateurOrEmploye();

                // Récupérer le statut
                $statutUtilisateur = $espaceRepository->statutUtilisateur($idUtilisateur);

                if ($statutUtilisateur) {
                    $passager = $statutUtilisateur->getPassager();
                    $chauffeur = $statutUtilisateur->getChauffeur();

                    // Déterminer le statut
                    if ($passager && $chauffeur) {
                        $radio = 'lesDeux';
                    } elseif ($chauffeur) {
                        $radio = 'chauffeur';
                    } else {
                        $radio = 'passager';
                    }

                    // Vérifier si l'utilisateur a déjà une voiture
                    if ($radio === 'chauffeur' || $radio === 'lesDeux') {
                        $voitureExiste = $espaceServices->voitureExiste($idUtilisateur, Mysql::getInstance()->getPDO());
                    }

                    /* ============================================ Graphiques ============================================= */

                    //  Afficher les graphiques
                    $graphiques = $espaceServices->graphique(Mysql::getInstance()->getPDO());
                    $message = $espaceServices->message;

                    /* ============================================ Gestion des covoits ============================================= */

                    // Récupérer nombre covoit participe
                    $totalCovoitUtilisateur = $espaceRepository->totalCovoitPassager($idUtilisateur);
                    $totalTrajetUtilisateur = $espaceRepository->totalTrajetChauffeur($idUtilisateur);

                    // Récupérer nombre de covoit participe
                    $totalCovoitActif = $espaceRepository->totalCovoitActif($idUtilisateur);
                    $totalCovoitInactif = $espaceRepository->totalCovoitInactif($idUtilisateur);

                    // Récupérer les covoiturages actifs de l'utilisateur
                    $mesCovoits = $covoiturageServices->mesCovoiturages($idUtilisateur);

                    // Récupérer les historiques des covoits où l'utilisateur à participé
                    $mesCovoitsHistorique = $covoiturageServices->mesCovoituragesHistorique($idUtilisateur);

                    // Calculer pour chaque covoiturage si l'avis a déjà été donné
                    foreach ($mesCovoitsHistorique as $c) {
                        $conducteurId = $c->getConducteurId();
                        $covoitId = $c->getCovoiturageId();

                        // Ajouter une propriété à l'objet
                        $dejaAvis = $covoiturageRepository->avisDejaDonne($idUtilisateur, $covoitId, $conducteurId);
                        $c->setDejaAvis($dejaAvis);
                    }

                    /* ============================================ Gestion des voitures ============================================= */

                    // Récupérer nombre de voitures
                    $totalVoitureUtilisateur = $espaceRepository->totalVoiture($idUtilisateur);

                    //  Afficher les voitures
                    $voituresUtilisateur = $voitureServices->voitureUtilisateur(Mysql::getInstance()->getPDO(), $idUtilisateur);

                    /* ============================================ Gestion des avis ============================================= */

                    // Récupérer nombre avis 
                    $totalAvisActif = $espaceRepository->totalAvisActif();
                    $totalAvisInactif = $espaceRepository->totalAvisInactif($idUtilisateur);

                    // Récupération des avis
                    $avis = $avisServices->avis();
                    if (empty($avis)) {
                        $message = "Aucun avis à gérer.";
                    }

                    // Récupérer les infos du voyage de l'avis à traiter
                    $idAvis = $_GET['avis_id'] ?? null;
                    if ($idAvis) {
                        $infosCovoitAvis = $avisServices->infosCovoitAvis($idAvis);
                    }

                    // Récupération historique des avis
                    $avisCheck = $avisServices->historiqueAvis($idUtilisateur);

                    /* ============================================ Gestion des utilisateurs ============================================= */

                    // Récupération total utilisateur
                    $totalCompteActif = $utilisateurRepository->totalCompteActif();
                    $totalCompteSuspendu = $utilisateurRepository->totalCompteSuspendu();
                }
            } catch (\Exception $e) {
                $message = "Une erreur est survenue : " . $e->getMessage();
            }
        }

        // Afficher la vue
        $this->render("pages/espace", [
            'csrf' => $csrf ?? '',
            'radio' => $radio,
            'voitureExiste' => $voitureExiste,
            'voitureValide' => $voitureValide,
            'messageVoiture' => $messageVoiture,
            'message' => $message,
            'graphiques' => $graphiques,
            'messageCompte' => $messageCompte,
            'compteValide' => $compteValide,
            'messageSusp' => $messageSusp,
            'compteSusp' => $compteSusp,
            'comptes' => $comptes,
            'totalCovoitUtilisateur' => $totalCovoitUtilisateur,
            'totalTrajetUtilisateur' => $totalTrajetUtilisateur,
            'totalVoitureUtilisateur' => $totalVoitureUtilisateur,
            'totalCovoitActif' => $totalCovoitActif,
            'totalCovoitInactif' => $totalCovoitInactif,
            'totalAvisActif' => $totalAvisActif,
            'totalAvisInactif' => $totalAvisInactif,
            'mesCovoits' => $mesCovoits,
            'mesCovoitsHistorique' => $mesCovoitsHistorique,
            'voituresUtilisateur' => $voituresUtilisateur,
            'avis' => $avis,
            'infosCovoitAvis' => $infosCovoitAvis,
            'avisCheck' => $avisCheck,
            'totalCompteActif' => $totalCompteActif,
            'totalCompteSuspendu' => $totalCompteSuspendu,
        ]);
    }
}
