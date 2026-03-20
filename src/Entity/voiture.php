<?php

namespace App\Entity;


class Voiture
{
    protected ?int $voiture_id = null;
    protected ?string $modele = null;
    protected ?string $immatriculation = null;
    protected ?string $energie = null;
    protected ?string $couleur = null;
    protected ?string $date_premiere_immatriculation = null;
    protected ?int $nb_place = null;
    protected ?string $libelle = null;

    // voiture id
    public function getVoitureId(): ?int
    {
        return $this->voiture_id;
    }

    // modele
    public function getModele(): ?string
    {
        return $this->modele;
    }

    // immatriculation
    public function getImmatriculation(): ?string
    {
        return $this->immatriculation;
    }

    // energie voiture
    public function getEnergie(): ?string
    {
        return $this->energie;
    }

    // couleur
    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    // date 1ere immatriculation
    public function getDatePremiereImmatriculation(): ?string
    {
        return $this->date_premiere_immatriculation;
    }

    // nombre place dispo
    public function getNbPlace(): ?int
    {
        return $this->nb_place;
    }

    // image voiture
    public function getImageVoiture(): string
    {
        $energie = strtolower(trim($this->energie ?? ''));

        if ($energie === 'essence' || $energie === 'diesel') {
            return '/assets/images/voiture-noir.png';
        } else {
            return '/assets/images/voiture-electrique.png';
        }
    }

    // Libelle marque
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }
}
