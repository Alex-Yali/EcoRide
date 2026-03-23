<?php

namespace App\Service;

use App\Repository\CovoiturageRepository;
use App\db\Mysql;

class CovoiturageServices
{
    public string $message = '';
    public string $messageCovoit = '';
    public bool $covoitValide = false;

    /* ============================================ Recherche covoits ============================================= */

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

    /* ============================================ Covoit utilisateur participe ============================================= */

    public function mesCovoiturages($idUtilisateur): array
    {
        $covoiturageRepository = new CovoiturageRepository();

        // Récupération des covoiturages où l'utilisateur participe
        $mesCovoits = $covoiturageRepository->mesCovoiturages($idUtilisateur);
        return $mesCovoits;
    }

    public function gestionStatutCovoit($idUtilisateur, $covoiturage_id, $action)
    {
        $covoiturageRepository = new CovoiturageRepository();

        // Vérifier rôle utilisateur
        $participant = $covoiturageRepository->roleUtilisateurCovoit($idUtilisateur, $covoiturage_id);

        if (!$participant) {
            return "Action non autorisée.";
        }

        // Déterminer statut
        $statut = match ($action) {
            'demarrer' => 'Demarrer',
            'terminer' => 'Terminer',
            'annuler' => 'Annuler',
            default => null
        };
        if (!$statut) {
            return "Action invalide.";
        }
        // Mise à jour statut
        if ($statut === 'Demarrer' || $statut === 'Terminer') {
            $covoiturageRepository->majStatut($statut, $covoiturage_id);
        }

        // Trajet terminé par le chauffeur"
        if ($statut === 'Terminer' && $participant['chauffeur'] == 1) {

            // Récupérer infos du covoiturage
            $infosCovoit = $covoiturageRepository->infosCovoiturages($covoiturage_id);

            // Récupérer les passagers
            $passagers = $covoiturageRepository->passagersCovoiturages($covoiturage_id);

            // Envoi d’email aux passagers
            foreach ($passagers as $p) {
                $to = $p['email'];
                $subject = "Arrivée à destination";
                $messageMail = "
                    Bonjour {$p['pseudo']},<br><br>
                    Votre covoiturage de <b>{$infosCovoit['lieu_depart']}</b> à <b>{$infosCovoit['lieu_arrivee']}</b><br>
                    du <b>{$infosCovoit['date_depart']}</b à <b>{$infosCovoit['heure_depart']}</b> est arrivé à destination.<br><br>
                    Merci d’avoir voyagé avec EcoRide !<br>
                    Vous pouvez maintenant laisser un avis sur votre conducteur dans l'historique de vos covoiturages dans votre espace .<br><br>
                    <hr>
                    <i>L’équipe EcoRide</i>";
                @mail($to, $subject, $messageMail);
            }
        }

        // Si Annulation
        if ($statut === 'Annuler') {
            try {
                // Commencer transaction
                $pdo = Mysql::getInstance()->getPDO();
                $pdo->beginTransaction();

                // Récupérer le prix du covoit par personne
                $infosCovoit = $covoiturageRepository->infosCovoiturages($covoiturage_id);
                $prix = $infosCovoit['prix_personne'] ?? 0;

                // Le chauffeur annule le trajet
                if ($participant['chauffeur'] == 1) {

                    // Mise à jour statut
                    $covoiturageRepository->majStatut('Annuler', $covoiturage_id);

                    // Récupérer les passagers
                    $passagers = $covoiturageRepository->passagersCovoiturages($covoiturage_id);

                    foreach ($passagers as $p) {
                        // 1️ Remboursement du passager
                        $covoiturageRepository->rembourserUtilisateur($prix, $p['utilisateur_id']);

                        // 2️ Envoi d’un email d’annulation
                        $to = $p['email'];
                        $subject = "Annulation du covoiturage";
                        $messageMail = "
                        Bonjour {$p['pseudo']},<br><br>
                        Le conducteur a annulé le covoiturage prévu de 
                        <b>{$infosCovoit['lieu_depart']}</b> à <b>{$infosCovoit['lieu_arrivee']}</b><br>
                        le <b>{$infosCovoit['date_depart']}</b> à <b>{$infosCovoit['heure_depart']}</b>.<br><br>
                        Vos crédits ont été remboursés automatiquement.<br><br>
                        Merci de votre compréhension.<br>
                        <hr>
                        <i>L’équipe EcoRide</i>";

                        @mail($to, $subject, $messageMail);
                    }

                    // 3 Remettre toutes les places disponibles
                    $nbPlacesTotales = count($passagers) + $infosCovoit['nb_place'];
                    $covoiturageRepository->incrementerPlacesTotales($nbPlacesTotales, $covoiturage_id);
                }

                //  Le passager annule sa participation au trajet
                if ($participant['chauffeur'] == 0) {

                    if ($prix > 0) {

                        // 1️ Remboursement du passager
                        $covoiturageRepository->rembourserUtilisateur($prix, $idUtilisateur);

                        // 2️ Libérer une place (+1)
                        $covoiturageRepository->incrementerPlace($covoiturage_id);

                        // 3️ Supprimer la participation du passager
                        $covoiturageRepository->supprimerParticipation($idUtilisateur, $covoiturage_id);
                    }
                }
                $pdo->commit();
            } catch (\Exception $e) {
                $pdo->rollBack();
                return "Erreur lors de l'annulation.";
            }
            return "";
        }
    }

    /* ============================================ Historique covoit utilisateur participe ============================================= */

    public function mesCovoituragesHistorique($idUtilisateur): array
    {
        $covoiturageRepository = new CovoiturageRepository();

        // Récupérer les historiques des covoiturages où l'utilisateur a participer
        $mesCovoitsHistorique = $covoiturageRepository->mesCovoituragesHistorique($idUtilisateur);
        return $mesCovoitsHistorique;
    }

    public function traiterAvis($post, $idUtilisateur): void
    {
        $covoiturageRepository = new CovoiturageRepository();

        // Récupération des données du formulaire
        $avis = $_POST['avis'] ?? '';
        $rating = $_POST['rating'] ?? '';
        $commentaire = trim($_POST['commentaire'] ?? '');
        $covoiturage_id = intval($_POST['covoiturage_id'] ?? 0);

        // Récupérer les historiques des covoiturages où l'utilisateur a participer
        $mesCovoitsHistorique = $covoiturageRepository->mesCovoituragesHistorique($idUtilisateur);

        $prixParPersonne = 0;
        $conducteur_id = 0;

        // Chercher le covoiturage correspondant
        foreach ($mesCovoitsHistorique as $c) {
            if ($c->getCovoiturageId() == $covoiturage_id) {
                $prixParPersonne = $c->getPrixPersonne();
                $conducteur_id =  $c->getConducteurId();
                break;
            }
        }

        // Vérifier si l'avis a déjà été donné
        $avisDejaDonne = $covoiturageRepository->avisDejaDonne($idUtilisateur, $covoiturage_id, $conducteur_id);

        if (!$avisDejaDonne && $prixParPersonne > 0) {

            $etatAvis = ($avis === 'Oui') ? 'ok' : 'nok';

            try {
                // Commencer transaction
                $pdo = Mysql::getInstance()->getPDO();
                $pdo->beginTransaction();

                // Ajouter l'avis
                $idAvis = $covoiturageRepository->ajouterAvis($commentaire, $rating, $conducteur_id, $covoiturage_id, $etatAvis);

                // Récuperer utilisateur depose avis
                $covoiturageRepository->ajouterDepose($idUtilisateur, $idAvis);

                // Ajouter crédits uniquement si avis = Oui (etat = ok)
                if ($etatAvis === 'ok') {
                    $covoiturageRepository->ajouterCredits($prixParPersonne, $conducteur_id);
                }

                $pdo->commit();
            } catch (\PDOException $e) {
                $pdo->rollBack();
                $this->message = "Erreur lors de l'ajout";
            }
        }
    }
}
