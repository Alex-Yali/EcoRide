<?php
require_once 'db.php';

if (session_status() === PHP_SESSION_NONE) { // Démarre la session PHP si elle n’est pas déjà active
    session_start();
}

$covoits = [];
$message = "";
$idUtilisateur = $_SESSION['user_id'] ?? 0; // Récupère l’ID de l’utilisateur connecté

// Recherche principale
$depart  = trim($_POST['depart'] ?? '');
$arrivee = trim($_POST['arrivee'] ?? '');
$date    = trim($_POST['date'] ?? '');

if (!empty($depart) && !empty($arrivee) && !empty($date)) {
    $sqlDateExacte = " SELECT DISTINCT
                            u.utilisateur_id,
                            u.pseudo,
                            (
                                SELECT AVG(a.note)
                                FROM avis a                               
                                WHERE a.chauffeur_id = u.utilisateur_id
                                AND a.statut = 'valider'
                            ) AS moyenne,
                            c.covoiturage_id,
                            c.lieu_depart,
                            c.date_depart,
                            c.heure_depart,
                            c.lieu_arrivee,
                            c.date_arrivee,
                            c.heure_arrivee,
                            c.nb_place,
                            c.prix_personne,
                            c.statut,
                            v.voiture_id,
                            v.modele,
                            v.energie
                        FROM utilisateur u
                        JOIN participe pa ON pa.utilisateur_utilisateur_id = u.utilisateur_id
                        JOIN covoiturage c ON c.covoiturage_id = pa.covoiturage_covoiturage_id
                        JOIN utilise ut ON ut.covoiturage_covoiturage_id = c.covoiturage_id -- On relie le covoiturage à la voiture qu’il utilise 
                        JOIN voiture v ON v.voiture_id = ut.voiture_voiture_id -- Puis on récupère les infos de la voiture utilisée
                        WHERE c.lieu_depart = :depart
                        AND c.lieu_arrivee = :arrivee
                        AND c.date_depart = :date
                        AND c.nb_place > 0 -- Trajets où il reste des places
                        AND pa.chauffeur = 1 -- Récupérer les covoiturages côté conducteur
                        AND (c.statut IS NULL OR c.statut NOT IN ('Demarrer','Terminer')) -- Récupérer les covoiturages non demarrer ni terminer ni annuler
                        AND c.covoiturage_id NOT IN ( -- Empêche l’affichage des covoiturages où l’utilisateur est déjà chauffeur ou passager
                            SELECT covoiturage_covoiturage_id
                            FROM participe
                            WHERE utilisateur_utilisateur_id = :idUtilisateur
                        )
                        ";

            
    $stmtDateExacte = $pdo->prepare($sqlDateExacte);
    $params = [
        ':depart'  => $depart,
        ':arrivee' => $arrivee,
        ':date'    => $date,
        ':idUtilisateur' => $idUtilisateur ?? 0 // si personne n’est connecté, on met 0
    ];
    $stmtDateExacte->execute($params);
    $covoitsDateExacte = $stmtDateExacte->fetchAll(PDO::FETCH_ASSOC);

    if (empty($covoitsDateExacte)) {
        $sqlDateProche = "SELECT 
                            u.utilisateur_id,
                            u.pseudo,
                            (
                                SELECT AVG(a.note)
                                FROM avis a                               
                                WHERE a.chauffeur_id = u.utilisateur_id
                                AND a.statut = 'valider'
                            ) AS moyenne,
                            c.covoiturage_id,
                            c.lieu_depart,
                            c.date_depart,
                            c.heure_depart,
                            c.lieu_arrivee,
                            c.date_arrivee,
                            c.heure_arrivee,
                            c.nb_place,
                            c.prix_personne,
                            c.statut,
                            v.voiture_id,
                            v.modele,
                            v.energie
                        FROM utilisateur u
                        JOIN participe pa ON pa.utilisateur_utilisateur_id = u.utilisateur_id
                        JOIN covoiturage c ON c.covoiturage_id = pa.covoiturage_covoiturage_id
                        JOIN utilise ut ON ut.covoiturage_covoiturage_id = c.covoiturage_id -- On relie le covoiturage à la voiture qu’il utilise 
                        JOIN voiture v ON v.voiture_id = ut.voiture_voiture_id -- Puis on récupère les infos de la voiture utilisée
                        WHERE c.lieu_depart = :depart
                        AND c.lieu_arrivee = :arrivee
                        AND c.date_depart > :date -- Covoits futurs
                        AND c.nb_place > 0 -- Trajets où il reste des places
                        AND pa.chauffeur = 1 -- Récupérer les covoiturages côté conducteur
                        AND (c.statut IS NULL OR c.statut NOT IN ('Demarrer','Terminer')) -- Récupérer les covoiturages non demarrer ni terminer ni annuler
                        AND c.covoiturage_id NOT IN ( -- Empêche l’affichage des covoiturages où l’utilisateur est déjà chauffeur ou passager
                            SELECT covoiturage_covoiturage_id
                            FROM participe
                            WHERE utilisateur_utilisateur_id = :idUtilisateur
                        )
                        ORDER BY c.date_depart ASC, c.heure_depart ASC
                        LIMIT 1 ";
        $stmtDateProche = $pdo->prepare($sqlDateProche);
        $paramsProche = [
            ':depart'  => $depart,
            ':arrivee' => $arrivee,
            ':date'    => $date,
            ':idUtilisateur' => $idUtilisateur ?? 0
        ];
        $stmtDateProche->execute($paramsProche);

        $covoitsDateProche = $stmtDateProche->fetchAll(PDO::FETCH_ASSOC);
        // On remplace la liste principale par le covoiturage futur s'il existe
        if (!empty($covoitsDateProche)) {
            $covoitsDateExacte = $covoitsDateProche;
            $message = "Pas de covoiturages à la date demandée. Voici le covoiturage le plus proche après cette date :";
        } else {
            $message = "Aucun covoiturage trouvé à cette date ni après.";
        }
    } else {
        $message = count($covoitsDateExacte) . " covoiturage(s) trouvé(s) à la date sélectionnée.";
    }

} else {
    $message = "Merci de remplir les champs de départ, arrivée et date.";
}

    // Mise en place des filtres
    $maxPrix = trim($_POST['maxPrix'] ?? '');
    $maxTime = trim($_POST['maxTime'] ?? '');
    $rating  = trim($_POST['rating'] ?? '');
    $ecolo   = trim($_POST['ecolo'] ?? '');

    $covoitsDateExacte = array_filter($covoitsDateExacte, function($c) use ($maxPrix, $maxTime, $rating, $ecolo) {
        // Filtre prix
        if ($maxPrix && $c['prix_personne'] > $maxPrix) return false; // Si un prix maximum ($maxPrix) est défini et que le prix du covoit dépasse cette limite,on rejette ce covoiturage (return false)
                                                                      
        // Filtre durée
        if ($maxTime) {
            $dureeHeures = (strtotime($c['heure_arrivee']) - strtotime($c['heure_depart'])) / 3600; 
            if ($dureeHeures > $maxTime) return false;
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

    $covoitsDateExacte = array_values($covoitsDateExacte); // ré-indexer

// Réinitialiser les filtres
if (isset($_POST['btnReset'])) {
    $_POST['maxPrix'] = $_POST['maxTime'] = $_POST['rating'] = $_POST['ecolo'] = '';
}

    // Conversion et affichage de la date en français

if (!empty($covoitsDateExacte)) {
    $covoit = $covoitsDateExacte[0]; // on prend le premier covoiturage filtré

    // Conversion et affichage de la date en français
    $fDate = new DateTime($covoit['date_depart']);
    $fmt = new IntlDateFormatter(
        'fr_FR',
        IntlDateFormatter::FULL,
        IntlDateFormatter::NONE,
    );
    $dateCovoit = mb_convert_case($fmt->format($fDate), MB_CASE_TITLE, "UTF-8");
}
?>
