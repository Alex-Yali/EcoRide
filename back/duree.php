<?php
require_once 'db.php'; // Connexion PDO

try {
    // Récupération des trajets
    $stmt = $pdo->prepare('SELECT lieu_depart, lieu_arrivee, heure_depart, heure_arrivee FROM covoiturage');
    $stmt->execute();
    $trajets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($trajets as $trajet) {
        $heureDepart = new DateTime($trajet['heure_depart']);
        $heureArrivee = new DateTime($trajet['heure_arrivee']);

        $interval = $heureDepart->diff($heureArrivee);

        $dureeLisible = $interval->h . 'h' . str_pad($interval->i, 2, '0', STR_PAD_LEFT);

        //echo "De {$trajet['lieu_depart']} à {$trajet['lieu_arrivee']} : durée $dureeLisible<br>";
    }

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>


