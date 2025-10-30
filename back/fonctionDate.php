<?php
session_start();

// Récupération sécurisée des données de session
$date = $_POST['date'] ?? null;


$fDate = new DateTime($date); // Convertie la variable en DateTime
$fmt = new IntlDateFormatter( // Formater la date en format FR
    'fr_FR',
    IntlDateFormatter::FULL,
    IntlDateFormatter::NONE
);
$dateCovoit = $fmt->format($fDate);
?>