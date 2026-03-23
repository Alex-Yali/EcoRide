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
    "/detail/" => ["controller" => "App\Controller\CovoiturageController", "action" => "detail"],
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
    "/trajet/" => ["controller" => "App\Controller\PageController", "action" => "trajet"],

    // Espace voitures
    "/vehicule/" => ["controller" => "App\Controller\VoitureController", "action" => "vehicule"],

];
