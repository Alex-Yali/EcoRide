<?php

namespace App\Controller;

class PageController extends Controller
{

    public function accueil(): void
    {
        $csrf = generate_csrf_token();
        $this->render("pages/accueil", [
            "csrf" => $csrf ?? '',
        ]);
    }

    public function avis(): void {}

    public function avisEnCours(): void
    {
        $this->render("pages/avisEnCours");
    }

    public function contact(): void
    {
        $this->render("pages/contact");
    }

    public function detail(): void
    {
        $this->render("pages/detail");
    }

    public function historique(): void
    {
        $this->render("pages/historique");
    }

    public function historiqueAvis(): void
    {
        $this->render("pages/historiqueAvis");
    }

    public function mdp(): void
    {
        $this->render("pages/mdp");
    }

    public function mention(): void
    {
        $this->render("pages/mention");
    }

    public function mesCovoiturages(): void
    {
        $this->render("pages/mesCovoiturages");
    }

    public function rechercher(): void
    {
        $csrf = generate_csrf_token();

        // Afficher la vue
        $this->render("pages/rechercher", [
            "csrf" => $csrf ?? '',
        ]);
    }

    public function trajet(): void
    {
        $this->render("pages/trajet");
    }

    public function vehicule(): void
    {
        $this->render("pages/vehicule");
    }
}
