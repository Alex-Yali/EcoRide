<?php

namespace App\Entity;


class Participe
{
    protected ?int $utilisateur_utilisateur_id = null;
    protected ?int $covoiturage_covoiturage_id = null;
    protected ?bool $passager = null;
    protected ?bool $chauffeur = null;

    // utilisateur_id
    public function getUtilisateurId(): ?int
    {
        return $this->utilisateur_utilisateur_id;
    }

    // covoiturage_id
    public function getCovoiturageId(): ?int
    {
        return $this->covoiturage_covoiturage_id;
    }

    // passager bool
    public function getPassager(): ?bool
    {
        return $this->passager;
    }

    // chauffeur bool
    public function getChauffeur(): ?bool
    {
        return $this->chauffeur;
    }
}
