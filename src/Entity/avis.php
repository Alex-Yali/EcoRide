<?php

namespace App\Entity;

class Avis
{
    protected ?int $avis_id = null;
    protected ?string $commentaire = null;
    protected ?float $note = null;
    protected ?string $statut = null;
    protected ?int $chauffeur_id = null;
    protected ?int $employe_id = null;
    protected ?string $etat = null;
    protected ?int $auteur_id = null;
    protected ?string $auteur_pseudo = null;
    protected ?float $moyenne = null;
    protected ?string $date_avis = null;

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
    // auteur avis id
    public function getAuteurId(): ?int
    {
        return $this->auteur_id;
    }
    // auteur avis pseudo
    public function getAuteurPseudo(): ?string
    {
        return $this->auteur_pseudo;
    }
    // moyenne
    public function getMoyenne(): ?float
    {
        return $this->moyenne;
    }

    // date
    public function getDate(): ?string
    {
        return $this->date_avis;
    }


    /* ==================== Utilisateur ==================== */
    protected ?int $utilisateur_id = null;
    protected ?string $pseudo = null;

    public function getUtilisateurId(): ?int
    {
        return $this->utilisateur_id;
    }
    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    /* ==================== Utilisateur ==================== */
    protected ?float $prix_personne = null;

    public function getPrixPersonne(): ?float
    {
        return $this->prix_personne;
    }
}
