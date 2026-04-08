<?php

namespace App\Service;

use App\db\Mysql;
use App\Repository\AvisRepository;

class AvisServices
{

    public string $message = '';

    /* ============================================ Afficher avis ============================================= */

    public function avis(): array
    {
        $avisRepository = new AvisRepository();

        // Récupération des avis
        $avis = $avisRepository->avis();
        return $avis;
    }

    /* ============================================ Afficher infos covoit avis ============================================= */
    public function infosCovoitAvis($idAvis)
    {
        $avisRepository = new AvisRepository();

        // Récupérer les infos du voyage de l'avis à traiter
        $infosCovoitAvis = $avisRepository->infosCovoitAvis($idAvis);
        return $infosCovoitAvis;
    }

    /* ============================================ Valider avis ============================================= */
    public function validerAvis($idAvis, $idUtilisateur): void
    {
        $avisRepository = new AvisRepository();

        // Récupérer les infos de l'avis en cours de validation
        $infosAvis = $avisRepository->infosAvisValide($idAvis);

        $etat = $infosAvis['etat'];
        $prixParPersonne = $infosAvis['prix_personne'];
        $idChauffeur = $infosAvis['chauffeur_id'];

        try {
            // Commencer transaction
            $pdo = Mysql::getInstance()->getPDO();
            $pdo->beginTransaction();

            // Valider l'avis
            $validerAvis = $avisRepository->validerAvis($idAvis, $idUtilisateur);

            if (!$validerAvis) {
                throw new \Exception("Avis déjà traité ou invalide");
            }

            // Ajouter crédits uniquement si etat = 'nok'
            if ($etat === 'nok') {
                $avisRepository->ajouterCredits($prixParPersonne, $idChauffeur);
            }

            $pdo->commit();
        } catch (\PDOException $e) {
            $pdo->rollBack();
            $this->message = "Erreur lors de l'ajout";
        }
    }

    /* ============================================ Refuser avis ============================================= */
    public function refuserAvis($idAvis, $idUtilisateur): void
    {
        $avisRepository = new AvisRepository();

        $avisRepository->refuserAvis($idAvis, $idUtilisateur);
    }

    /* ============================================ Historique avis ============================================= */
    public function historiqueAvis($idUtilisateur)
    {
        $avisRepository = new AvisRepository();
        $avisCheck = $avisRepository->historiqueAvis($idUtilisateur);
        return $avisCheck;
    }


    /* ============================================ Afficher avis chauffeur ============================================= */
    public function afficherAvis($idCovoit)
    {
        $avisRepository = new AvisRepository();
        $avisChauffeur = $avisRepository->afficherAvis($idCovoit);

        foreach ($avisChauffeur as &$avis) {
            if (!empty($avis['date_avis'])) {
                $date = new \DateTime($avis['date_avis']);
                $avis['date_formattee'] = $date->format('d/m/Y');
            } else {
                $avis['date_formattee'] = 'Date inconnue';
            }
        }

        return $avisChauffeur;
    }
}
