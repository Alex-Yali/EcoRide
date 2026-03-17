<?php

namespace App\Entity;


class Possede
{
    protected ?int $utilisateur_utilisateur_id = null;
    protected ?int $role_role_id = null;

    // utilisateur_id
    public function getUtilisateurId(): ?int
    {
        return $this->utilisateur_utilisateur_id;
    }

    // role_id
    public function getRoleId(): ?int
    {
        return $this->role_role_id;
    }
}
