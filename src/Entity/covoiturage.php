<?php

namespace App\Entity;

use DateTime;

class Covoiturage
{
    protected ?int $covoiturage_id = null;
    protected ?string $date_depart = null;
    protected ?string $heure_depart = null;
    protected ?string $lieu_depart = null;
    protected ?string $date_arrivee = null;
    protected ?string $heure_arrivee = null;
    protected ?string $lieu_arrivee = null;
    protected ?string $statut = null;
    protected ?int $nb_place = null;
    protected ?float $prix_personne = null;
    protected ?int $voiture_id = null;
    protected ?string $modele = null;
    protected ?string $energie = null;
    protected ?string $pseudo = null;
    protected ?float $moyenne = null;

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

    // covoiturage id
    public function getCovoiturageId(): ?int
    {
        return $this->covoiturage_id;
    }

    // date depart
    public function getDateDepart(): ?string
    {
        return $this->date_depart;
    }

    // heure depart
    public function getHeureDepart(): ?string
    {
        return $this->heure_depart;
    }

    // lieu depart
    public function getLieuDepart(): ?string
    {
        return $this->lieu_depart;
    }

    // date arrivee
    public function getDateArrivee(): ?string
    {
        return $this->date_arrivee;
    }

    // heure arrivee
    public function getHeureArrivee(): ?string
    {
        return $this->heure_arrivee;
    }

    // lieu arrivee
    public function getLieuArrivee(): ?string
    {
        return $this->lieu_arrivee;
    }

    // statut
    public function getStatut(): ?string
    {
        return $this->statut;
    }

    // nombre place
    public function getNbPlace(): ?int
    {
        return $this->nb_place;
    }

    // prix par personne
    public function getPrixPersonne(): ?float
    {
        return $this->prix_personne;
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

    // id voiture
    public function getVoitureId(): ?int
    {
        return $this->voiture_id;
    }

    // modele voiture
    public function getModele(): ?string
    {
        return $this->modele;
    }

    // energie utilisé 
    public function getEnergie(): ?string
    {
        return $this->energie;
    }

    // pseudo
    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    // moyenne 
    public function getMoyenne(): ?float
    {
        return $this->moyenne;
    }
}
