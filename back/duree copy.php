<?php
require 'db.php'; // Connexion PDO

try {
    // Requête SQL avec alias pour plus de clarté
    $sql = "
        SELECT 
            lieu_depart, 
            lieu_arrivee, 
            TIMEDIFF(heure_arrivee, heure_depart) AS duree
        FROM covoiturage;
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $durees = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Parcours des résultats
    foreach ($durees as $trajet) {
        // Récupération de la durée brute (format HH:MM:SS)
        $raw = $trajet['duree'];
        list($heures, $minutes, $secondes) = explode(':', $raw);

        // Conversion en format lisible (par ex. "4h00", "2h45")
        $dureeLisible = (int)$heures . 'h' . str_pad($minutes, 2, '0', STR_PAD_LEFT);

       echo "De {$trajet['lieu_depart']} à {$trajet['lieu_arrivee']} : durée $dureeLisible<br>";
    }

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
