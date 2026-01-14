<?php
require_once '../src/service/db.php'; // connexion PDO

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$idCovoit = $_GET['id'] ?? '';

// Requête SQL pour récupérer les infos du covoiturage
$sqlDetail = "SELECT 
                u.utilisateur_id AS chauffeur_id,
                u.pseudo AS chauffeur_pseudo,
                ua.utilisateur_id AS auteur_id,
                ua.pseudo AS auteur_pseudo,
                a.commentaire,
                a.note,
                (
                    SELECT AVG(a2.note)
                    FROM avis a2
                    WHERE a2.chauffeur_id = u.utilisateur_id
                    AND a2.statut = 'valider'
                ) AS moyenne
            FROM utilisateur u
            JOIN avis a ON a.chauffeur_id = u.utilisateur_id
            JOIN depose d ON d.avis_avis_id = a.avis_id
            JOIN utilisateur ua ON ua.utilisateur_id = d.utilisateur_utilisateur_id  -- auteur de l'avis
            JOIN participe pa ON pa.utilisateur_utilisateur_id = u.utilisateur_id
            JOIN covoiturage c ON c.covoiturage_id = pa.covoiturage_covoiturage_id
            WHERE a.statut = 'valider'
            AND c.covoiturage_id = :id
            AND pa.chauffeur = 1
            ORDER BY a.avis_id ASC
            ";


$stmt = $pdo->prepare($sqlDetail);
$stmt->execute(['id' => $idCovoit]);
$avis = $stmt->fetchAll(PDO::FETCH_ASSOC);
