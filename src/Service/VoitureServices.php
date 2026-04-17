<?php

namespace App\Service;

use App\repository\VoitureRepository;
use PDO;

class VoitureServices
{
    public string $message = '';

    /* ============================================ Affichage voiture chauffeur ============================================= */

    public function voitureUtilisateur(PDO $pdo, $idUtilisateur)
    {

        if (!$idUtilisateur) {
            $this->message  = "Erreur : aucun utilisateur connecté.";
            return false;
        }

        // Récupérer les véhicules de l'utilisateur connecté
        $voitureRepository = new VoitureRepository($pdo);
        return $voitureRepository->voitureUtilisateur($idUtilisateur);
    }
}
