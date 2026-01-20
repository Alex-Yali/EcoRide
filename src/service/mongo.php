<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$host = $_ENV['MONGO_HOST'];
$port = $_ENV['MONGO_PORT'];
$dbName = $_ENV['MONGO_DB'];

try {
    $mongoClient = new MongoDB\Client("mongodb://$host:$port");

    $db = $mongoClient->$dbName;
    $collectionPreferences = $db->preferences;
} catch (Throwable $e) {
    die("Erreur MongoDB : " . $e->getMessage());
}
