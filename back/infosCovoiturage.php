<?php
require_once 'db.php';

$covoits = [];
$message = "";

// Recherche principale
$depart  = trim($_POST['depart'] ?? '');
$arrivee = trim($_POST['arrivee'] ?? '');
$date    = trim($_POST['date'] ?? '');

if (!empty($depart) && !empty($arrivee) && !empty($date)) {
    $sql = "SELECT 
                u.utilisateur_id,
                u.pseudo,
                AVG(a.note) AS moyenne,
                pa.role,
                c.covoiturage_id,
                c.lieu_depart,
                c.date_depart,
                c.heure_depart,
                c.lieu_arrivee,
                c.date_arrivee,
                c.heure_arrivee,
                c.nb_place,
                c.prix_personne,
                v.voiture_id,
                v.modele,
                v.energie
            FROM utilisateur u
            JOIN participe pa ON pa.utilisateur_utilisateur_id = u.utilisateur_id
            JOIN covoiturage c ON c.covoiturage_id = pa.covoiturage_covoiturage_id
            JOIN gere ge ON ge.utilisateur_utilisateur_id = u.utilisateur_id
            JOIN voiture v ON v.voiture_id = ge.voiture_voiture_id
            LEFT JOIN depose d ON u.utilisateur_id = d.utilisateur_utilisateur_id
            LEFT JOIN avis a ON d.avis_avis_id = a.avis_id
            WHERE c.lieu_depart = :depart
              AND c.lieu_arrivee = :arrivee
              AND c.date_depart = :date
              AND pa.role = 'conducteur'
            GROUP BY u.utilisateur_id, c.covoiturage_id, v.voiture_id
            ORDER BY c.heure_depart";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':depart'  => $depart,
        ':arrivee' => $arrivee,
        ':date'    => $date
    ]);
    $covoits = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Mise en place des filtres
    $maxPrix = trim($_POST['maxPrix'] ?? '');
    $maxTime = trim($_POST['maxTime'] ?? '');
    $rating  = trim($_POST['rating'] ?? '');
    $ecolo   = trim($_POST['ecolo'] ?? '');

    $covoits = array_filter($covoits, function($c) use ($maxPrix, $maxTime, $rating, $ecolo) {
        // Filtre prix
        if ($maxPrix && $c['prix_personne'] > $maxPrix) return false; // Si un prix maximum ($maxPrix) est défini et que le prix du covoit dépasse cette limite,on rejette ce covoiturage (return false)
                                                                      
        // Filtre durée
        if ($maxTime) {
            $dureeMinutes = (strtotime($c['heure_arrivee']) - strtotime($c['heure_depart'])) / 3600; 
            if ($dureeMinutes > $maxTime) return false;
        }
        // On calcule la durée du trajet avec les heures de départ et d’arrivée, strtotime() transforme une heure en timestamp UNIX (nombre de secondes).                                                                             
        // La différence entre arrivée et départ donne la durée en secondes, on divise par 3600 pour obtenir la durée en heures.
        // Si la durée est plus longue que $maxTime, le covoit est rejeté.

        // Filtre note moyenne
        if ($rating && ($c['moyenne'] ?? 0) < $rating) return false; //Si un minimum de note ($rating) est demandé, et que la note moyenne du conducteur ($c['moyenne']) est inférieure,le covoiturage est rejeté.



        // Filtre écologique
        $energie = strtolower(trim($c['energie']));
        if ($ecolo === 'oui' && $energie !== 'électrique') return false;
        if ($ecolo === 'non' && $energie === 'électrique') return false;

        return true;
    });

    $covoits = array_values($covoits); // ré-indexer

    if ($covoits) {
        $message = count($covoits) . " covoiturage(s) trouvé(s).";
    }

} else {
    $message = "Merci de remplir les champs de départ, arrivée et date.";
}

 // Réinitialiser les filtres
if (isset($_POST['btnReset'])) {
    $_POST['maxPrix'] = $_POST['maxTime'] = $_POST['rating'] = $_POST['ecolo'] = '';
}
?>
