<?php
$dbHost = '127.0.0.1';  
$dbName = 'ecoride';
$dbUser = 'alex';
$dbPass = 'Maximelukas28!';

try {
    $pdo = new PDO(
        "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,       
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
        ]
    );
} catch (PDOException $e) {
    die('Erreur BDD : ' . $e->getMessage());  
}
