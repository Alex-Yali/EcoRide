<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Calcul durée du trajet
$depart = new DateTime($covoitDetail['date_depart'] . ' ' . $covoitDetail['heure_depart']);
$arrivee = new DateTime($covoitDetail['date_arrivee'] . ' ' . $covoitDetail['heure_arrivee']);

// Calcul durée
if ($arrivee < $depart) {
    // Si arrivée avant départ (trajet nuit), ajouter 1 jour
    $arrivee->modify('+1 day');
}

$duree = $depart->diff($arrivee);
$dureeCovoit = $duree->h . 'h' . str_pad($duree->i, 2, '0', STR_PAD_LEFT);

// Choix de l’image selon le type d’énergie
$energie = strtolower(trim($covoitDetail['energie'] ?? ''));
$image = ($energie === 'essence' || $energie === 'diesel')
    ? './assets/images/voiture-noir.png'
    : './assets/images/voiture-electrique.png';
