<?php
require_once 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";
$idUtilisateur = $_SESSION['user_id'] ?? null;
$rating  = trim($_POST['rating'] ?? '');

try { 
    $sqlMesCovoit = "SELECT 
                        u.utilisateur_id,
                        u.pseudo,
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
                        v.energie,
                        c.statut,
                        u_conducteur.pseudo AS conducteur_pseudo, -- Récupère le pseudo du conducteur
                        (
                            SELECT AVG(a.note)  -- Récupère la moyenne du conducteur
                            FROM depose d
                            JOIN avis a ON d.avis_avis_id = a.avis_id
                            WHERE d.utilisateur_utilisateur_id = u_conducteur.utilisateur_id
                            AND d.statut = 'recu'
                        ) AS conducteur_moyenne
                    FROM covoiturage c
                    JOIN participe pa ON pa.covoiturage_covoiturage_id = c.covoiturage_id
                    JOIN utilisateur u ON u.utilisateur_id = pa.utilisateur_utilisateur_id
                    LEFT JOIN participe p_conducteur 
                        ON p_conducteur.covoiturage_covoiturage_id = c.covoiturage_id
                        AND p_conducteur.chauffeur = 1
                    LEFT JOIN utilisateur u_conducteur ON u_conducteur.utilisateur_id = p_conducteur.utilisateur_utilisateur_id
                    JOIN utilise ut ON ut.covoiturage_covoiturage_id = c.covoiturage_id -- On relie le covoiturage à la voiture qu’il utilise 
                    JOIN voiture v ON v.voiture_id = ut.voiture_voiture_id -- Récupère les infos de la voiture utilisée
                    WHERE pa.utilisateur_utilisateur_id = :idUtilisateur -- Récupères que les covoiturages auxquels l’utilisateur connecté participe
                    AND c.statut IN ('Terminer','Annuler','Valider')
                    ORDER BY c.date_depart ASC, c.heure_depart ASC
                    ";

$stmtMesCovoit = $pdo->prepare($sqlMesCovoit);
$stmtMesCovoit->execute(['idUtilisateur' => $idUtilisateur]);
$mesCovoit = $stmtMesCovoit->fetchAll(PDO::FETCH_ASSOC);


if (!empty($mesCovoit)) {
    $fmt = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);

    foreach ($mesCovoit as &$covoit) { // & pour modifier directement l'élément
        $fDate = new DateTime($covoit['date_depart']);
        $covoit['date_formatee'] = ucfirst($fmt->format($fDate)); // nouvelle clé
    }
    unset($covoit); 
} else {
        $message = "<p>Aucun covoiturage trouvé.</p>";
    } 


} catch (PDOException $e) {
    echo "<p>Erreur : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
}