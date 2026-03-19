<?php

namespace App\Entity;

use DateTime;

class Covoiturage
{

    // --------------- Participant --------------- //

    // utilisateur connecté id
    protected ?int $utilisateur_id = null;
    public function getUtilisateurId(): int
    {
        return $this->utilisateur_id;
    }

    // pseudo
    protected ?string $pseudo = null;
    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    // --------------- Covoiturage --------------- //

    // covoiturage id
    protected ?int $covoiturage_id = null;
    public function getCovoiturageId(): ?int
    {
        return $this->covoiturage_id;
    }

    // lieu depart
    protected ?string $lieu_depart = null;
    public function getLieuDepart(): ?string
    {
        return $this->lieu_depart;
    }

    // date depart
    protected ?string $date_depart = null;
    public function getDateDepart(): ?string
    {
        return $this->date_depart;
    }

    // date formattée
    public function getDateFormatted(): string
    {
        $date = new DateTime($this->date_depart);

        $fmt = new \IntlDateFormatter(
            'fr_FR',
            \IntlDateFormatter::FULL,
            \IntlDateFormatter::NONE,
            'Europe/Paris'
        );

        return mb_convert_case($fmt->format($date), MB_CASE_TITLE, "UTF-8");
    }

    // heure depart
    protected ?string $heure_depart = null;
    public function getHeureDepart(): ?string
    {
        return $this->heure_depart;
    }

    // lieu arrivee
    protected ?string $lieu_arrivee = null;
    public function getLieuArrivee(): ?string
    {
        return $this->lieu_arrivee;
    }

    // date arrivee
    protected ?string $date_arrivee = null;
    public function getDateArrivee(): ?string
    {
        return $this->date_arrivee;
    }

    // heure arrivee
    protected ?string $heure_arrivee = null;
    public function getHeureArrivee(): ?string
    {
        return $this->heure_arrivee;
    }

    // nombre place
    protected ?int $nb_place = null;
    public function getNbPlace(): ?int
    {
        return $this->nb_place;
    }

    // prix par personne
    protected ?float $prix_personne = null;
    public function getPrixPersonne(): ?float
    {
        return $this->prix_personne;
    }

    // statut
    protected ?string $statut = null;
    public function getStatut(): ?string
    {
        return $this->statut;
    }

    // --------------- Voiture --------------- //

    // id voiture
    protected ?int $voiture_id = null;
    public function getVoitureId(): ?int
    {
        return $this->voiture_id;
    }

    // modele voiture
    protected ?string $modele = null;
    public function getModele(): ?string
    {
        return $this->modele;
    }

    // energie utilisée
    protected ?string $energie = null;
    public function getEnergie(): ?string
    {
        return $this->energie;
    }

    // image voiture
    public function getImageVoiture(): string
    {
        $energie = strtolower(trim($this->energie ?? ''));

        if ($energie === 'essence' || $energie === 'diesel') {
            return '/assets/images/voiture-noir.png';
        } else {
            return '/assets/images/voiture-electrique.png';
        }
    }

    // --------------- Conducteur --------------- //

    // pseudo conducteur
    protected ?string $conducteur_pseudo = null;
    public function getConducteurPseudo(): ?string
    {
        return $this->conducteur_pseudo;
    }

    // moyenne conducteur
    protected ?float $conducteur_moyenne = null;
    public function getConducteurMoyenne(): ?float
    {
        return $this->conducteur_moyenne;
    }

    // conducteur id
    protected ?int $conducteur_id = null;
    public function getConducteurId(): ?int
    {
        return $this->conducteur_id;
    }

    // --------------- Avis --------------- //

    // Etat de l'avis
    protected ?bool $dejaAvis = false;
    public function getDejaAvis(): bool
    {
        return $this->dejaAvis;
    }

    public function setDejaAvis(bool $dejaAvis): void
    {
        $this->dejaAvis = $dejaAvis;
    }

    // moyenne
    protected ?float $moyenne = null;
    public function getMoyenne(): ?float
    {
        return $this->moyenne;
    }

    // heures formatées
    public function getHeureDepartFormat(): string
    {
        return date('H:i', strtotime($this->heure_depart));
    }

    public function getHeureArriveeFormat(): string
    {
        return date('H:i', strtotime($this->heure_arrivee));
    }

    // durée trajet
    public function getDureeMinutes(): int
    {
        $depart = new DateTime($this->date_depart . ' ' . $this->heure_depart);
        $arrivee = new DateTime($this->date_arrivee . ' ' . $this->heure_arrivee);

        return ($arrivee->getTimestamp() - $depart->getTimestamp()) / 60;
    }

    public function getDureeFormatted(): string
    {
        $minutes = $this->getDureeMinutes();
        $heures = floor($minutes / 60);
        $mins = $minutes % 60;

        return $heures . 'h' . sprintf("%02d", $mins);
    }
}
