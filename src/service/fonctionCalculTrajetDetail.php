<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Création des DateTime complètes
$depart = new DateTime($covoitDetail['date_depart'] . ' ' . $covoitDetail['heure_depart']);
$arrivee = new DateTime($covoitDetail['date_arrivee'] . ' ' . $covoitDetail['heure_arrivee']);

// Calcul durée totale en minutes
$totalMinutes = ($arrivee->getTimestamp() - $depart->getTimestamp()) / 60;

// Convertir en heures et minutes
$heures = floor($totalMinutes / 60);
$minutes = $totalMinutes % 60;
$dureeCovoit = $heures . 'h' . str_pad($minutes, 2, '0', STR_PAD_LEFT);

// Choix de l’image selon le type d’énergie
$energie = strtolower(trim($covoitDetail['energie'] ?? ''));
$image = ($energie === 'essence' || $energie === 'diesel')
    ? './assets/images/voiture-noir.png'
    : './assets/images/voiture-electrique.png';
