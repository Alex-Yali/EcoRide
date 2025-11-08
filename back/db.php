<?php
session_start();
// Déclaration du DSN
$mysqlDsn = 'mysql:host=localhost;dbname=ecoride';
// Connexion à la base avec PDO
try {
    $pdo = new PDO($mysqlDsn,'root','Maximelukas28!');
// Gestion des erreurs    
} catch (PDOException $e) {
    echo ' Une erreur de connexion à la base de données est survenue'; 
    exit('Erreur BDD : ' . $e->getMessage()); 
}
