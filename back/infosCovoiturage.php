<?php
require_once 'db.php';

$covoits = [];
$message = "";

// Recherche principale
$depart  = trim($_POST['depart'] ?? '');
$arrivee = trim($_POST['arrivee'] ?? '');
$date    = trim($_POST['date'] ?? '');

if (!empty($depart) && !empty($arrivee) && !empty($date)) {
    $sql = "(SELECT 
                    u.utilisateur_id,
                    u.pseudo,
                    (
                        SELECT AVG(a.note)
                        FROM depose d
                        JOIN avis a ON d.avis_avis_id = a.avis_id
                        WHERE d.utilisateur_utilisateur_id = u.utilisateur_id
                        AND d.statue = 'recu'
                    ) AS moyenne,
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
                WHERE c.lieu_depart = :depart
                AND c.lieu_arrivee = :arrivee
                AND c.date_depart = :date
                AND pa.role = 'conducteur'
                AND c.nb_place > 0
            )
            UNION ALL
            (SELECT 
                    u.utilisateur_id,
                    u.pseudo,
                    (
                        SELECT AVG(a.note)
                        FROM depose d
                        JOIN avis a ON d.avis_avis_id = a.avis_id
                        WHERE d.utilisateur_utilisateur_id = u.utilisateur_id
                        AND d.statue = 'recu'
                    ) AS moyenne,
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
                WHERE c.lieu_depart = :depart
                AND c.lieu_arrivee = :arrivee
                AND c.date_depart > :date
                AND pa.role = 'conducteur'
                AND c.nb_place > 0
                ORDER BY c.date_depart ASC, c.heure_depart ASC
                LIMIT 1
            )";

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
        if ($ecolo === 'oui' && $energie !== 'electrique') return false;
        if ($ecolo === 'non' && $energie === 'electrique') return false;

        return true;
    });

    $covoits = array_values($covoits); // ré-indexer

} else {
    $message = "Merci de remplir les champs de départ, arrivée et date.";
}

 // Réinitialiser les filtres
if (isset($_POST['btnReset'])) {
    $_POST['maxPrix'] = $_POST['maxTime'] = $_POST['rating'] = $_POST['ecolo'] = '';
}

// Conversion et affichage de la date en français

if (!empty($covoits)) {
    $covoit = $covoits[0]; // on prend le premier covoiturage filtré

    // Conversion et affichage de la date en français
    $fDate = new DateTime($covoit['date_depart']);
    $fmt = new IntlDateFormatter(
        'fr_FR',
        IntlDateFormatter::FULL,
        IntlDateFormatter::NONE,
    );
    $dateCovoit = mb_convert_case($fmt->format($fDate), MB_CASE_TITLE, "UTF-8");
    // Gestion du message selon la date
        if ($covoit['date_depart'] === $date) {
            $message = count($covoits) . " covoiturage(s) trouvé(s) à la date sélectionnée.";
        } else {
            $message = "Pas de covoiturages à la date demandée. Voici le covoiturage le plus proche :";
        }
}
?>
