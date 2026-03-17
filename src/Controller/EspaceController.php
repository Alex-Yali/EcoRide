<?php

namespace App\Controller;

use App\Repository\EspaceRepository;
use App\Service\EspaceServices;
use App\Repository\UtilisateurRepository;
use App\db\Mysql;
use PDO;

class EspaceController extends Controller
{
    public function espace(): void
    {
        $idUtilisateur = $_SESSION['user_id'] ?? null;
        $radio = 'passager';
        $voitureExiste = false;
        $voitureValide = false;
        $moyenneUtilisateur = null;
        $infosUtilisateur = null;
        $message = '';
        $messageVoiture = '';
        $messageCompte = '';
        $graphiques = false;
        $csrf = generate_csrf_token();

        if (!$idUtilisateur) {
            $message = "Utilisateur non connecté.";
        } else {
            try {
                // Repository
                $espaceRepository = new EspaceRepository(Mysql::getInstance()->getPDO());
                $utilisateurRepository = new UtilisateurRepository();
                $espaceServices = new EspaceServices();

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
                    }
                }

                // Récupérer le statut
                $statutUtilisateur = $espaceRepository->statutUtilisateur($idUtilisateur);
                $infosUtilisateur = $utilisateurRepository->infosUtilisateur($idUtilisateur);

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

                    // Récupérer la moyenne
                    $moyenneUtilisateur = $espaceRepository->Moyenne($idUtilisateur);

                    //  Afficher les graphiques
                    $espaceServices = new EspaceServices();
                    $graphiques = $espaceServices->graphique(Mysql::getInstance()->getPDO());
                    $messageCompte = $espaceServices->messageCompte;
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
            'infosUtilisateur' => $infosUtilisateur,
            'moyenneUtilisateur' => $moyenneUtilisateur,
            'graphiques' => $graphiques,
            'messageCompte' => $messageCompte,
        ]);
    }
}
