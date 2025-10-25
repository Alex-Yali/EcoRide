<?php
require 'db.php'; // Connexion PDO

$sql = "SELECT pseudo, note FROM depose 
        join utilisateur on utilisateur_utilisateur_id= utilisateur_id
        join avis on avis_avis_id = avis_id";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo '<pre>'; print_r($notes); echo '</pre>';