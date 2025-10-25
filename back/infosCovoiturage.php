<?php
require 'db.php'; // Connexion PDO

// Récupération des paramètres de recherche
$depart   = $_POST['depart'] ?? $_GET['depart'] ?? null;
$arrivee  = $_POST['arrivee'] ?? $_GET['arrivee'] ?? null;
$date     = $_POST['date'] ?? $_GET['date'] ?? null;
$maxTime  = $_POST['maxTime'] ?? null; // en heures
$maxPrix  = $_POST['maxPrix'] ?? null;
$note     = $_POST['note'] ?? null;
$ecolo    = $_POST['ecolo'] ?? null;

// Requête SQL de base avec alias clair pour la durée
$sql = "SELECT 
        covoiturage_id,
        lieu_depart,
        lieu_arrivee,
        heure_depart,
        heure_arrivee,
        nb_place,
        prix_personne,
        TIMEDIFF(heure_arrivee, heure_depart) AS duree_trajet
    FROM covoiturage
    WHERE 1=1";
$params = [];

// Filtres conditionnels
if ($depart) {
    $sql .= " AND lieu_depart = ?";
    $params[] = $depart;
}

if ($arrivee) {
    $sql .= " AND lieu_arrivee = ?";
    $params[] = $arrivee;
}

if ($date) {
    $sql .= " AND date_depart = ?";
    $params[] = $date;
}

if ($maxTime) {
    // Comparaison en TIME : convertit heures en secondes puis en TIME
    $sql .= " AND TIMEDIFF(heure_arrivee, heure_depart) <= SEC_TO_TIME(?)";
    $params[] = $maxTime * 3600;
}

if ($maxPrix) {
    $sql .= " AND prix_personne <= ?";
    $params[] = $maxPrix;
}

if ($note) {
    $sql .= " AND note >= ?";
    $params[] = $note;
}

if ($ecolo) {
    $sql .= " AND voyage_ecologique = ?";
    $params[] = $ecolo;
}

// Préparation et exécution de la requête
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$covoits = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Debug optionnel pour vérifier les résultats
 //echo '<pre>'; print_r($covoits); echo '</pre>';
?>
