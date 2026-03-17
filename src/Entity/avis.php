<?php

namespace App\Entity;

class Avis
{
    protected ?int $avis_id = null;
    protected ?string $commentaire = null;
    protected ?float $note = null;
    protected ?string $statut = null;
    protected ?int $covoiturage_id = null;
    protected ?int $chauffeur_id = null;
    protected ?int $employe_id = null;
    protected ?string $etat = null;

    // avis_id
    public function getAvisId(): ?int
    {
        return $this->avis_id;
    }

    // commentaires
    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    // note
    public function getNote(): ?float
    {
        return $this->note;
    }

    // statut
    public function getStatut(): ?string
    {
        return $this->statut;
    }

    // covoiturage_id
    public function getCovoiturageId(): ?int
    {
        return $this->covoiturage_id;
    }

    // chauffeur_id
    public function getChauffeurId(): ?int
    {
        return $this->chauffeur_id;
    }

    // employe_id
    public function getEmployeId(): ?int
    {
        return $this->employe_id;
    }

    // etat
    public function getEtat(): ?string
    {
        return $this->etat;
    }
}
