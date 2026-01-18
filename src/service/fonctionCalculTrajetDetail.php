<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Calcul durée du trajet
$heureDepart = new DateTime($covoitDetail['heure_depart']);
$heureArrivee = new DateTime($covoitDetail['heure_arrivee']);
$duree = $heureDepart->diff($heureArrivee);
$dureeCovoit = $duree->h . 'h' . str_pad($duree->i, 2, '0', STR_PAD_LEFT);

// Choix de l’image selon le type d’énergie
$energie = strtolower(trim($covoitDetail['energie'] ?? ''));
$image = ($energie === 'essence')
    ? './assets/images/voiture-noir.png'
    : './assets/images/voiture-electrique.png';
