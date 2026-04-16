<?php

namespace App\Entity;


class Utilise
{
    protected ?int $voiture_voiture_id = null;
    protected ?int $covoiturage_covoiturage_id = null;

    // voiture_voiture_id
    public function getVoitureId(): ?int
    {
        return $this->voiture_voiture_id;
    }

    // covoiturage_covoiturage_id
    public function getCovoiturageId(): ?int
    {
        return $this->covoiturage_covoiturage_id;
    }
}
