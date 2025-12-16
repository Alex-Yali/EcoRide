<?php
require_once __DIR__ . '/../vendor/autoload.php';

try {
    $mongoClient = new MongoDB\Client("mongodb://localhost:27017");

    // Sélection DB + collection
    $db = $mongoClient->ecoride;
    $collectionPreferences = $db->preferences;

} catch (Exception $e) {
    die("Erreur MongoDB : " . $e->getMessage());
}
