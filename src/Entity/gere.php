<?php

namespace App\Entity;


class Gere
{
    protected ?int $utilisateur_utilisateur_id = null;
    protected ?int $voiture_voiture_id = null;

    // utilisateur_id
    public function getUtilisateurId(): ?int
    {
        return $this->utilisateur_utilisateur_id;
    }

    // voiture_id
    public function getVoitureId(): ?int
    {
        return $this->voiture_voiture_id;
    }
}
