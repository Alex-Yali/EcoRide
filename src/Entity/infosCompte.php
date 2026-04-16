<?php

namespace App\Entity;

class infosCompte
{
    protected ?int $utilisateur_id = null;
    protected ?string $pseudo = null;
    protected ?string $email = null;
    protected ?string $libelle = null;
    protected ?string $statut = null;

    // utilisateur id
    public function getUtilisateurId(): ?int
    {
        return $this->utilisateur_id;
    }

    // email
    public function getEmail(): ?string
    {
        return $this->email;
    }

    // pseudo
    public function getPseudo(): ?string
    {
        return ucfirst($this->pseudo);
    }

    // libelle role
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    // statut
    public function getStatut(): string
    {
        return $this->statut;
    }
}
