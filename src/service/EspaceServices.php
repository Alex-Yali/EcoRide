<?php

namespace App\Service;

use App\Repository\EspaceRepository;
use App\Repository\VoitureRepository;
use App\db\Mysql;
use PDO;

class EspaceServices
{
    public string $message = '';
    public string $messageVoiture = '';
    public string $messageCompte = '';
    public bool $voitureValide = false;
    public bool $voitureExiste = false;

    /* ============================================ Gestion radio utilisateur ============================================= */


    public function radioUtilisateur(int $userId): string
    {
        $espaceRepository = new EspaceRepository(Mysql::getInstance()->getPDO());

        // Récupérer l'utilisateur
        $statutUtilisateur = $espaceRepository->statutUtilisateur($userId);
        if (!$statutUtilisateur) {
            $this->message = "Utilisateur introuvable.";
            return '';
        }

        // Valeurs actuelles converties en int
        $passager = (int) $statutUtilisateur->getPassager();
        $chauffeur = (int) $statutUtilisateur->getChauffeur();

        // Déterminer le radio actuel
        if ($passager && !$chauffeur) {
            $radio = 'passager';
        } elseif (!$passager && $chauffeur) {
            $radio = 'chauffeur';
        } elseif ($passager && $chauffeur) {
            $radio = 'lesDeux';
        } else {
            $radio = '';
        }

        // Si formulaire POST et CSRF valide on met à jour
        $userStatut = $_POST['user-statut'] ?? null;
        $csrf = $_POST['csrf_token'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf_token($csrf) && $userStatut) {


            $passager = ($userStatut === 'passager' || $userStatut === 'lesDeux') ? 1 : 0;
            $chauffeur = ($userStatut === 'chauffeur' || $userStatut === 'lesDeux') ? 1 : 0;

            // Mettre à jour la BDD
            $espaceRepository->switchStatutUtilisateur($userId, $passager, $chauffeur);

            // Mettre à jour le radio
            $radio = $userStatut;
        }
        return $radio;
    }

    public function ajouterVoiture(array $post, ?int $userId, PDO $pdo): bool
    {

        if (!$userId) {
            $this->message = "Erreur : aucun utilisateur connecté.";
            return false;
        }

        // Instanciation repository
        $espaceRepository = new EspaceRepository(Mysql::getInstance()->getPDO());
        $voitureRepository = new VoitureRepository($pdo);

        // Vérifier si l'utilisateur a déjà une voiture
        if ($espaceRepository->checkVoitureUtilisateur($userId)) {
            $this->voitureValide = false;
            $this->voitureExiste = true;
            $this->messageVoiture = "Vous avez déjà enregistré un véhicule.";
            return false;
        }

        $immat = trim($post['immatriculation'] ?? '');
        $dateImmat = trim($post['dateImmat'] ?? '');
        $modele = trim($post['modele'] ?? '');
        $couleur = trim($post['couleur'] ?? '');
        $marque = trim($post['marque'] ?? '');
        $energie = trim($post['energie'] ?? '');
        $place = trim($post['place'] ?? '');
        $tabac = ucfirst(trim($post['tabac'] ?? ''));
        $animal = ucfirst(trim($post['animal'] ?? ''));
        $autre = ucfirst(trim($post['ajoutPref'] ?? ''));

        // Vérification des champs obligatoires
        if (
            $immat === '' || $dateImmat === '' || $modele === '' || $couleur === '' ||
            $marque === '' || $place === '' || $energie === '' || $tabac === '' || $animal === ''
        ) {
            $this->messageVoiture = "Veuillez renseigner tous les champs.";
            return false;
        }

        // Vérification doublon immatriculation
        if ($voitureRepository->checkImmatriculation($immat)) {
            $this->voitureValide = false;
            $this->messageVoiture = "Un véhicule avec cette immatriculation existe déjà.";
            return false;
        }

        // Insertion voiture et relations
        $voitureRepository->insertVoiture($modele, $immat, $couleur, $dateImmat, $energie, $place);
        $idVoiture = $voitureRepository->lastInsertId();
        $idMarque = $voitureRepository->ajoutMarque($marque);
        $voitureRepository->insertDetient($idVoiture, $idMarque);
        $voitureRepository->insertGere($userId, $idVoiture);
        $voitureRepository->updatePreferencesMongo($userId, $tabac, $animal, $autre);

        $this->voitureValide = true;
        $this->messageVoiture = "Véhicule ajouté avec succès.";
        return true;
    }

    public function voitureExiste(int $userId, PDO $pdo): bool
    {
        $espaceRepository = new EspaceRepository($pdo);
        return $espaceRepository->checkVoitureUtilisateur($userId);
    }

    /* ============================================ Graphiques ============================================= */

    public function graphique(PDO $pdo)
    {
        $idUtilisateur = $_SESSION['user_id'] ?? null;

        if (!$idUtilisateur) {
            $this->messageCompte  = "Erreur : aucun utilisateur connecté.";
            return false;
        }

        // Insertion graphiques
        $espaceRepository = new EspaceRepository($pdo);
        return $espaceRepository->graphique();
    }
}
