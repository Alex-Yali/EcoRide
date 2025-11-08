<?php
require_once 'db.php'; // connexion PDO

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    exit("Vous devez être connecté pour voir vos véhicules.");
}

$idUtilisateur = $_SESSION['user_id'];

// Récupérer les véhicules de l'utilisateur connecté
$sql = "SELECT v.voiture_id, v.modele, v.immatriculation, v.couleur, v.date_premiere_immatriculation, v.energie, m.libelle
        FROM voiture v
        JOIN gere g ON g.voiture_voiture_id = v.voiture_id
        JOIN utilisateur u ON u.utilisateur_id = g.utilisateur_utilisateur_id
        JOIN detient d ON d.voiture_voiture_id = v.voiture_id
        JOIN marque m ON m.marque_id = d.marque_marque_id
        WHERE g.utilisateur_utilisateur_id = :idUtilisateur
        ORDER BY v.voiture_id 
";

$stmt = $pdo->prepare($sql);
$stmt->execute([':idUtilisateur' => $idUtilisateur]);
$voitures = $stmt->fetchAll(PDO::FETCH_ASSOC);