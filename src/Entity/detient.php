<?php

namespace App\Entity;


class Detient
{
    protected ?int $voiture_voiture_id = null;
    protected ?int $marque_marque_id = null;

    // voiture_id
    public function getVoitureId(): ?int
    {
        return $this->voiture_voiture_id;
    }

    // marque_id
    public function getMarqueId(): ?int
    {
        return $this->marque_marque_id;
    }
}
