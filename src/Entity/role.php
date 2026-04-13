<?php

namespace App\Entity;


class Role
{
    protected ?int $role_id = null;
    protected ?string $libelle = null;

    // role_id
    public function getRoleId(): ?int
    {
        return $this->role_id;
    }

    // libelle role
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }
}
