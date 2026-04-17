<?php

namespace App\Service;

use App\repository\VoitureRepository;
use App\Db\Mysql;
use PDO;
use DateTime;

class TrajetServices
{
    public string $message = '';
    public string $messageVoiture = '';
    public bool $voitureValide = false;
    public bool $voitureExiste = false;
    public string $messageTrajet = '';
    public bool $trajetValide = false;

    /* ============================================ Affichage voiture chauffeur ============================================= */

    public function ajoutVoitureUtilisateur(PDO $pdo, $idUtilisateur, $immat, $dateImmat, $modele, $couleur, $marque, $place, $energie)
    {

        $pdo = Mysql::getInstance()->getPDO();
        $modele = ucfirst(strtolower($modele));
        $marque = ucfirst(strtolower($marque));
        $couleur = strtolower($couleur);
        $energie = strtolower($energie);

        if (!$idUtilisateur) {
            $this->message  = "Erreur : aucun utilisateur connecté.";
            return false;
        }

        // Instanciation repository
        $voitureRepository = new VoitureRepository($pdo);

        // Vérification des champs obligatoires
        if (
            $immat === '' || $dateImmat === '' || $modele === '' || $couleur === '' ||
            $marque === '' || $place === '' || $energie === ''
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

        try {
            // Commencer transaction
            $pdo->beginTransaction();

            // Insertion voiture et relations
            $voitureRepository->insertVoiture($modele, $immat, $couleur, $dateImmat, $energie, $place);
            $idVoiture = $voitureRepository->lastInsertId();
            $idMarque = $voitureRepository->ajoutMarque($marque);
            $voitureRepository->insertDetient($idVoiture, $idMarque);
            $voitureRepository->insertGere($idUtilisateur, $idVoiture);

            $pdo->commit();

            $this->messageVoiture  = "Véhicule ajouté avec succès.";
            $this->voitureValide = true;
            return true;
        } catch (\PDOException $e) {
            $pdo->rollBack();
            $this->message = "Erreur lors de l'ajout'";
            return false;
        }
    }

    /* ============================================ Ajouter un trajet ============================================= */

    public function ajouterTrajet(PDO $pdo, $depart, $dateDepart, $heureDepart, $destination, $dateArrivee, $heureArrivee, $places, $prix, $creditsUtilisateur, $idUtilisateur, $idVoiture, $idTrajet)
    {

        // Convertir les dates
        $dtDepart = new DateTime("$dateDepart $heureDepart");
        $dtArrivee = new DateTime("$dateArrivee $heureArrivee");

        if ($idVoiture <= 0) {
            $this->messageTrajet  = "Veuillez sélectionner une voiture.";
            return false;
        }

        // Instanciation repository
        $voitureRepository = new VoitureRepository($pdo);

        // Vérifier si un champ est vide
        if (
            $depart === '' || $dateDepart === '' || $heureDepart === '' || $destination === '' ||
            $dateArrivee === '' || $heureArrivee === '' || $places <= 0 || $prix <= 0  ||  $idVoiture <= 0
        ) {
            $this->messageTrajet  = "Veuillez renseigner tous les champs.";
            return false;
        }

        // Vérifier si la date et heure d'arrivée sont après le départ
        if ($dtArrivee < $dtDepart) {
            $this->messageTrajet = "La date et l'heure d'arrivée doivent être après le départ.";
            return false;
        }

        // Vérifier si l'utilisateur possède assez de crédit pour proposer un trajet
        if ($creditsUtilisateur < 2) {
            $this->messageTrajet  = "Vous n'avez pas assez de crédits.";
            return false;
        }

        $depart = ucfirst(strtolower($depart));
        $destination = ucfirst(strtolower($destination));

        try {
            // Commencer transaction
            $pdo->beginTransaction();

            // 1. Vérifier que la voiture appartient à l'utilisateur via la table 'gere'
            $checkVoitureUtilisateur = $voitureRepository->checkVoitureUtilisateur($idUtilisateur, $idVoiture);
            if (!$checkVoitureUtilisateur) {
                $this->messageTrajet  = "Cette voiture ne vous appartient pas.";
                $this->trajetValide = false;
                return false;
            }

            // 2. Vérifier si l'utilisateur a déjà ce covoiturage
            $checkCovoitUtilisateur = $voitureRepository->checkCovoitUtilisateur($idUtilisateur, $dateDepart, $heureDepart, $depart, $dateArrivee, $heureArrivee, $destination);
            if ($checkCovoitUtilisateur) {
                // L'utilisateur a déjà ajouté ce trajet
                $this->messageTrajet  = "Vous avez déjà proposé ce covoiturage.";
                $this->trajetValide = false;
                return false;
            } else {
                // 3. Ajouter le covoiturage
                $idTrajet = $voitureRepository->ajouterTrajet($dateDepart, $heureDepart, $depart, $dateArrivee, $heureArrivee, $destination, $places, $prix);

                // 4. Ajouter relation utilisateur-covoiturage dans participe
                $voitureRepository->participerCovoit($idUtilisateur, $idTrajet);

                // 5. Ajouter relation covoiturage–voiture dans utilise
                $voitureRepository->utiliseVoiturecovoit($idVoiture, $idTrajet);

                // 6. Déduire 2 crédits à l'utilisateur
                $prixTrajet = 2;
                $voitureRepository->removeCredits($idUtilisateur, $prixTrajet);
            }
            $pdo->commit();

            $this->messageTrajet  = "covoiturage ajouté avec succès.";
            $this->trajetValide = true;
            return true;
        } catch (\PDOException $e) {
            $pdo->rollBack();
            $this->messageTrajet = "Erreur lors de l'ajout : " . $e->getMessage();
            return false;
        }
    }
}
