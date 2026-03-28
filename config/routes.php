<?php
return [
    "/" => ["controller" => "App\Controller\PageController", "action" => "accueil"],

    // Espace authentification
    "/connexion/" => ["controller" => "App\Controller\AuthController", "action" => "connexion"],
    "/inscription/" => ["controller" => "App\Controller\AuthController", "action" => "inscription"],
    "/deconnexion/" => ["controller" => "App\Controller\AuthController", "action" => "deconnexion"],

    "/avis/" => ["controller" => "App\Controller\PageController", "action" => "avis"],
    "/contact/" => ["controller" => "App\Controller\PageController", "action" => "contact"],

    // Espace covoiturage
    "/covoiturage/" => ["controller" => "App\Controller\CovoiturageController", "action" => "covoiturage"],
    "/covoiturage/detail/" => ["controller" => "App\Controller\CovoiturageController", "action" => "participerCovoit"],
    "/mesCovoiturages/" => ["controller" => "App\Controller\CovoiturageController", "action" => "mesCovoiturages"],
    "/historique/" => ["controller" => "App\Controller\CovoiturageController", "action" => "mesCovoituragesHistorique"],

    // Espace utilisateur
    "/espace/" => ["controller" => "App\Controller\EspaceController", "action" => "espace"],
    "/mdp/" => ["controller" => "App\Controller\PageController", "action" => "mdp"],

    // Espace avis
    "/avisEnCours/" => ["controller" => "App\Controller\AvisController", "action" => "avis"],
    "/historiqueAvis/" => ["controller" => "App\Controller\AvisController", "action" => "historiqueAvis"],

    "/mention/" => ["controller" => "App\Controller\PageController", "action" => "mention"],
    "/rechercher/" => ["controller" => "App\Controller\PageController", "action" => "rechercher"],

    // Espace voitures
    "/vehicule/" => ["controller" => "App\Controller\VoitureController", "action" => "vehicule"],

    // Espace trajet
    "/trajet/" => ["controller" => "App\Controller\TrajetController", "action" => "trajet"],

];
