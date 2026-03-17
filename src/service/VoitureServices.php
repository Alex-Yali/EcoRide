<?php

namespace App\Service;

use App\Repository\VoitureRepository;
use PDO;

class VoitureServices
{
    public string $message = '';

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
