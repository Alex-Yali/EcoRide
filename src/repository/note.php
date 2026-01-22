<?php
require_once __DIR__ . '/../service/db.php'; // Connexion PDO
require_once __DIR__ . '/../service/csrf.php';

$idUtilisateur = $_SESSION['user_id'];
try {
    // Requête SQL pour récupérer la moyenne des notes
    $sqlNote = "SELECT 
                AVG(a.note) AS moyenne
                FROM avis a
                WHERE a.chauffeur_id = :idUtilisateur
                AND a.statut = 'valider';
                ";

    $stmt = $pdo->prepare($sqlNote);
    $stmt->execute(['idUtilisateur' => $idUtilisateur]);
    $note = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p>Erreur : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
}
