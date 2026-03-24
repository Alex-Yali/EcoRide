<?php

namespace App\Controller;

use App\db\Mysql;
use App\Repository\UtilisateurRepository;
use App\Service\TrajetServices;
use App\Service\VoitureServices;

class TrajetController extends Controller
{
    public function trajet()
    {
        $idUtilisateur = $_SESSION['user_id'] ?? null;
        $message = '';
        $voituresUtilisateur = false;
        $voitureValide = false;
        $trajetValide = false;
        $messageTrajet = '';
        $idVoiture = null;
        $idTrajet = null;
        $csrf = generate_csrf_token();

        if (!$idUtilisateur) {
            $message = "Utilisateur non connecté.";
        } else {
            try {
                // Services
                $voitureServices = new VoitureServices();
                $trajetServices = new TrajetServices();
                $utilisateurRepository = new UtilisateurRepository();

                // Récuperer les véhicules du chauffeur
                $voituresUtilisateur = $voitureServices->voitureUtilisateur(Mysql::getInstance()->getPDO(), $idUtilisateur);

                // Récupérer les crédits de l'utilisateur
                $utilisateur = $utilisateurRepository->infosUtilisateur($idUtilisateur);
                $creditsUtilisateur = $utilisateur->getCredits();

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                    // Vérification CSRF
                    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
                        $message = "Erreur CSRF : requête invalide.";
                    } else {

                        if (($_POST['formType'] ?? '') === 'ajoutVoiture') {
                            // Recherche principale
                            $immat = trim($_POST['immatriculation'] ?? '');
                            $dateImmat = trim($_POST['dateImmat'] ?? '');
                            $modele = trim($_POST['modele'] ?? '');
                            $couleur = trim($_POST['couleur'] ?? '');
                            $marque = trim($_POST['marque'] ?? '');
                            $energie = trim($_POST['energie'] ?? '');
                            $place = trim($_POST['place'] ?? '');

                            // Ajouter voiture et stocker en session pour les messages après rechargement de la page
                            $voitureValide = $trajetServices->ajoutVoitureUtilisateur(Mysql::getInstance()->getPDO(), $idUtilisateur, $immat, $dateImmat, $modele, $couleur, $marque, $place, $energie);
                            $_SESSION['messageVoiture'] = $trajetServices->messageVoiture;
                            $_SESSION['voitureValide'] = $voitureValide;
                            $message = $trajetServices->message;

                            // Recharger la page apres ajout
                            header('Location: /trajet/');
                            exit;
                        }

                        if (($_POST['formType'] ?? '') === 'ajoutTrajet') {
                            // Recherche principale
                            $depart = trim($_POST['depart'] ?? '');
                            $dateDepart = trim($_POST['dateDepart'] ?? '');
                            $heureDepart = trim($_POST['heureDepart'] ?? '');
                            $destination = trim($_POST['destination'] ?? '');
                            $dateArrivee = trim($_POST['dateArrivee'] ?? '');
                            $heureArrivee = trim($_POST['heureArrivee'] ?? '');
                            $places = (int) $_POST['places'];
                            $prix = (float) $_POST['prix'];
                            $voiture = $_POST['voiture'] ?? '';

                            // Ajouter trajet
                            $trajetValide = $trajetServices->ajouterTrajet(Mysql::getInstance()->getPDO(), $depart, $dateDepart, $heureDepart, $destination, $dateArrivee, $heureArrivee, $places, $prix, $creditsUtilisateur, $voiture, $idUtilisateur, $idVoiture, $idTrajet);
                            $message = $trajetServices->message;
                            $messageTrajet = $trajetServices->messageTrajet;
                        }
                    }
                }
            } catch (\Exception $e) {
                $message = "Une erreur est survenue : " . $e->getMessage();
            }

            // Afficher la vue
            $this->render("pages/trajet", [
                'csrf' => $csrf ?? '',
                'voituresUtilisateur' => $voituresUtilisateur,
                'message' => $message,
                'trajetValide' => $trajetValide,
                'messageTrajet' => $messageTrajet,
            ]);
        }
    }
}
