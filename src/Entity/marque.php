<?php

namespace App\Entity;


class Marque
{
    protected ?int $marque_id = null;
    protected ?string $libelle = null;

    // marque_id
    public function getMarqueId(): ?int
    {
        return $this->marque_id;
    }

    // libelle marque
    public function getMarque(): ?string
    {
        return $this->libelle;
    }
}
