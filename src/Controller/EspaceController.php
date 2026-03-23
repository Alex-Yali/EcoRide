<?php

namespace App\Controller;

use App\Repository\EspaceRepository;
use App\Service\EspaceServices;
use App\db\Mysql;
use App\Repository\UtilisateurRepository;
use App\Service\UtilisateurServices;

class EspaceController extends Controller
{
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
        $csrf = generate_csrf_token();

        if (!$idUtilisateur) {
            $message = "Utilisateur non connecté.";
        } else {
            try {
                // Repository
                $espaceRepository = new EspaceRepository(Mysql::getInstance()->getPDO());
                $espaceServices = new EspaceServices();
                $utilisateurRepository = new UtilisateurRepository();
                $utilisateurServices = new UtilisateurServices();

                // Gérer le switch de statut + ajout voiture
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

                    //  Afficher les graphiques
                    $espaceServices = new EspaceServices();
                    $graphiques = $espaceServices->graphique(Mysql::getInstance()->getPDO());
                    $message = $espaceServices->message;
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
        ]);
    }
}
