<?php

namespace App\Entity;

class Utilisateur
{
    protected ?int $utilisateur_id = null;
    protected ?string $nom = null;
    protected ?string $prenom = null;
    protected ?string $email = null;
    protected ?string $password = null;
    protected ?string $telephone = null;
    protected ?string $adresse = null;
    protected ?string $date_naissance = null;
    protected ?string $photo = null;
    protected ?string $pseudo = null;
    protected ?int $credits = null;
    protected ?bool $passager = null;
    protected ?bool $chauffeur = null;
    protected ?string $statut = null;

    // utilisateur id
    public function getUtilisateurId(): ?int
    {
        return $this->utilisateur_id;
    }

    // nom
    public function getNom(): ?string
    {
        return $this->nom;
    }

    // prenom
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    // email
    public function getEmail(): ?string
    {
        return $this->email;
    }

    // password
    public function getPassword(): ?string
    {
        return $this->password;
    }

    // telephone
    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    // adresse
    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    // date de naissance
    public function getDateNaissance(): ?string
    {
        return $this->date_naissance;
    }

    // photo utilisateur
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    // pseudo
    public function getPseudo(): ?string
    {
        return ucfirst($this->pseudo);
    }

    // credit
    public function getCredits(): ?int
    {
        return $this->credits;
    }
    public function setCredits(?int $credits): void
    {
        $this->credits = max(0, (int)$credits);
    }

    // passager
    public function getPassager(): ?bool
    {
        return $this->passager;
    }
    public function setPassager(int|bool $value): void
    {
        $this->passager = (int)$value;
    }
    // chauffeur
    public function getChauffeur(): ?bool
    {
        return $this->chauffeur;
    }
    public function setChauffeur(int|bool $value): void
    {
        $this->chauffeur = (int)$value;
    }

    // statut
    public function getStatut(): ?string
    {
        return $this->statut;
    }
    public function setStatut(?string $value): void
    {
        $this->statut = $value;
    }
}
