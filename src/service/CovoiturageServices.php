<?php

namespace App\Service;

use DateTime;
use App\Repository\CovoiturageRepository;

class CovoiturageServices
{
    public string $message = '';
    public string $messageCovoit = '';
    public bool $covoitValide = false;

    // Rechercher les covoiturages dispo
    public function searchCovoiturage($depart, $arrivee, $date, $idUtilisateur): array
    {
        $covoiturageRepository = new CovoiturageRepository();

        // Vérification des champs
        if (empty($depart) || empty($arrivee) || empty($date)) {
            $this->message = "Merci de remplir les champs de départ, arrivée et date.";
            return [];
        }

        // Recherche covoiturage exact
        $resultats = $covoiturageRepository->findCovoiturage(
            $depart,
            $arrivee,
            $date,
            $idUtilisateur
        );

        // Si aucun covoiturage exact, on cherche le plus proche
        if (empty($resultats)) {
            $resultats = $covoiturageRepository->findCovoiturageProche(
                $depart,
                $arrivee,
                $date,
                $idUtilisateur
            );
            // On remplace la liste principale par le covoiturage futur s'il existe
            if (!empty($resultats)) {
                $this->covoitValide = true;
                $this->messageCovoit = "Pas de covoiturages à la date demandée. Voici le covoiturage le plus proche après cette date :";
            } else {
                $this->covoitValide = false;
                $this->messageCovoit = "Aucun covoiturage trouvé à cette date ni après.";
                return [];
            }
        } else {
            $this->covoitValide = true;
            $this->messageCovoit = count($resultats) . " covoiturage(s) trouvé(s) à la date sélectionnée.";
        }

        // Application des filtres
        $resultats = $this->applyFilters($resultats);

        return $resultats ?? [];
    }

    // Appliquer les filtres
    public function applyFilters(array $filtres): array
    {

        // Réinitialiser les filtres
        if (isset($_POST['btnReset'])) {
            $_POST['maxPrix'] = $_POST['maxTime'] = $_POST['rating'] = $_POST['ecolo'] = '';
        }

        // Mise en place des filtres
        $maxPrix = trim($_POST['maxPrix'] ?? '');
        $maxTime = trim($_POST['maxTime'] ?? '');
        $rating  = trim($_POST['rating'] ?? '');
        $ecolo   = trim($_POST['ecolo'] ?? '');

        $filtres = array_filter($filtres, function ($c) use ($maxPrix, $maxTime, $rating, $ecolo) {

            // Filtre prix
            if ($maxPrix && $c->getPrixPersonne() > $maxPrix) {
                return false;
            }

            // Filtre durée
            if ($maxTime) {
                $dureeHeures = $c->getDureeMinutes() / 60;
                if ($dureeHeures > $maxTime) {
                    return false;
                }
            }

            // Filtre note
            if ($rating && ($c->getMoyenne() ?? 0) < $rating) {
                return false;
            }

            // Filtre écologique
            $energie = strtolower(trim($c?->getEnergie() ?? ''));

            if ($ecolo === 'oui' && $energie !== 'electrique') return false;
            if ($ecolo === 'non' && $energie === 'electrique') return false;

            return true;
        });

        return array_values($filtres);
    }

    public function formatDate(array $dateCovoit): ?string
    {
        if (empty($dateCovoit)) {
            return null;
        }

        $fDate = new DateTime($dateCovoit[0]->getDateDepart());

        $fmt = new \IntlDateFormatter(
            'fr_FR',
            \IntlDateFormatter::FULL,
            \IntlDateFormatter::NONE,
            'Europe/Paris'
        );

        return mb_convert_case($fmt->format($fDate), MB_CASE_TITLE, "UTF-8");
    }
}
