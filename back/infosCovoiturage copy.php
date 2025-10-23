<?php
require 'db.php'; // Connexion PDO

// Récupération des paramètres de recherche
$depart   = $_POST['depart'] ?? $_GET['depart'] ?? null;
$arrivee  = $_POST['arrivee'] ?? $_GET['arrivee'] ?? null;
$date     = $_POST['date'] ?? $_GET['date'] ?? null;
$maxTime  = $_POST['maxTime'] ?? null;
$maxPrix  = $_POST['maxPrix'] ?? null;
$note     = $_POST['note'] ?? null;
$ecolo    = $_POST['ecolo'] ?? null;

// Requête SQL de base
$sql = "SELECT * FROM covoiturage WHERE 1=1";
$params = [];

// Filtres conditionnels

if ($maxTime) {
    $sql .= " AND duree <= ?";
    $params[] = $maxTime;
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

?>
