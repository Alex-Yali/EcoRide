<?php

namespace App\Entity;

class InfosCovoitAvis
{
    protected ?int $covoiturage_id = null;
    protected ?string $passager_pseudo = null;
    protected ?string $passager_email = null;
    protected ?string $chauffeur_pseudo = null;
    protected ?string $chauffeur_email = null;
    protected ?string $date_depart = null;
    protected ?string $lieu_depart = null;
    protected ?string $date_arrivee = null;
    protected ?string $lieu_arrivee = null;

    public function getCovoiturageId(): ?int
    {
        return $this->covoiturage_id;
    }
    public function getPassagerPseudo(): ?string
    {
        return $this->passager_pseudo;
    }
    public function getPassagerEmail(): ?string
    {
        return $this->passager_email;
    }
    public function getChauffeurPseudo(): ?string
    {
        return $this->chauffeur_pseudo;
    }
    public function getChauffeurEmail(): ?string
    {
        return $this->chauffeur_email;
    }
    public function getDateDepart(): ?string
    {
        return $this->date_depart;
    }
    public function getLieuDepart(): ?string
    {
        return $this->lieu_depart;
    }
    public function getDateArrivee(): ?string
    {
        return $this->date_arrivee;
    }
    public function getLieuArrivee(): ?string
    {
        return $this->lieu_arrivee;
    }
}
