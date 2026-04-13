<?php

namespace App\Entity;


class Depose
{
    protected ?int $utilisateur_utilisateur_id = null;
    protected ?int $avis_avis_id = null;

    // utilisateur_id
    public function getUtilisateurId(): ?int
    {
        return $this->utilisateur_utilisateur_id;
    }

    // avis_id
    public function getAvisId(): ?int
    {
        return $this->avis_avis_id;
    }
}
