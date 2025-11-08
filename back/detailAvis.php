<?php
require_once 'db.php'; // connexion PDO

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$idCovoit = $_GET['id'] ?? '';

// Requête SQL pour récupérer les infos du covoiturage
$sqlDetail = "SELECT 
                u.utilisateur_id,
                u.pseudo,
                a.commentaire,
                a.note,
                (
                    SELECT AVG(a2.note)
                    FROM depose d2
                    JOIN avis a2 ON d2.avis_avis_id = a2.avis_id
                    WHERE d2.utilisateur_utilisateur_id = u.utilisateur_id
                    AND d2.statue = 'recu'
                ) AS moyenne
            FROM utilisateur u
            JOIN depose d ON u.utilisateur_id = d.utilisateur_utilisateur_id
            JOIN avis a ON d.avis_avis_id = a.avis_id
            JOIN participe pa ON pa.utilisateur_utilisateur_id = u.utilisateur_id
            JOIN covoiturage c ON c.covoiturage_id = pa.covoiturage_covoiturage_id
            WHERE d.statue = 'recu'
            AND c.covoiturage_id = :id
            ORDER BY a.avis_id ASC"; 

$stmt = $pdo->prepare($sqlDetail);
$stmt->execute(['id' => $idCovoit]);
$avis = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
