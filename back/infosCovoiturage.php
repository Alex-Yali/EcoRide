<?php
require 'db.php'; // Connexion PDO

// Récupération des paramètres de recherche
$depart   = $_POST['depart'] ??  null;
$arrivee  = $_POST['arrivee'] ??  null;
$date     = $_POST['date'] ??  null;

$sql = "SELECT pseudo,
        lieu_depart,
        heure_depart,
        lieu_arrivee,
        heure_arrivee,
        nb_place,
        prix_personne 
        FROM participe 
        join utilisateur on utilisateur_utilisateur_id= utilisateur_id
        join covoiturage on covoiturage_covoiturage_id = covoiturage_id
        WHERE conducteur = 'oui' ";


// Préparation et exécution de la requête
$stmt = $pdo->prepare($sql);
$stmt->execute();
$covoits = $stmt->fetchAll(PDO::FETCH_ASSOC);

//echo '<pre>'; print_r($covoits); echo '</pre>';  //debug
?>
