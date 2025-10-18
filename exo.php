<!-- Une agence immobilière doit gérer des biens immobiliers. Chaque bien possède un prix et une adresse, 
 ainsi qu'un identifiant numérique permettant de récupérer ces informations.

Un bien est également composé d'un certain nombre de pièces. Ces pièces seront gérées dans une table à part, 
et sont composées d'un identifiant numérique, d'un nom (cuisine, salon...) et d'une surface.

Chaque pièce n'appartenant qu'à un seul bien, elles possèdent également une référence vers le bien auquel elles sont rattachées.

Concevez une base de données permettant de gérer ces données, puis créez un script en PHP permettant de créer la base de données et les tables. -->
<?php
try {
    $pdo = new PDO('mysql:host=localhost', 'root', 'Maximelukas28!');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if ($pdo->exec('DROP DATABASE IF EXISTS realEstate') !== false) {
        if ($pdo->exec('CREATE DATABASE realEstate') !== false) {
            $realEstate = new PDO('mysql:dbname=realEstate;host=localhost', 'root', 'Maximelukas28!');
            if ($realEstate->exec('CREATE TABLE realEstates (
   id INT(11) PRIMARY KEY AUTO_INCREMENT,
   address VARCHAR(500),
   price  DECIMAL (20,2)
)') !== false) {
            if ($realEstate->exec('CREATE TABLE rooms (
   id INT(11) PRIMARY KEY AUTO_INCREMENT,
   realEstateId INT(11),
   name VARCHAR(100),
   surface DECIMAL (20,2),
   FOREIGN KEY (realEstateId) REFERENCES realEstates(id)
)') !== false) {
                    echo 'Installation terminée !';
                } else {
                    echo 'Impossible de créer la table rooms<br>';
                }
            } else {
                echo 'Impossible de créer la table realEstates<br>';
            }
        } else {
            echo 'Impossible de créer la base<br>';
        }
    } else {
        echo 'Impossible de supprimer la base<br>';
    }
} catch (PDOException $exception){
    die($exception->getMessage());
}
