<?php

require_once '../src/service/db.php'; // connexion PDO

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier que l'utilisateur est connecté
if (!empty($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Récupérer pseudo, crédits et rôle
    $stmt = $pdo->prepare('SELECT u.pseudo, u.credits, u.utilisateur_id, r.libelle FROM utilisateur u
                            JOIN possede p ON p.utilisateur_utilisateur_id = u.utilisateur_id
                            JOIN role r ON p.role_role_id = r.role_id
                            WHERE u.utilisateur_id = :user_id');
    $stmt->execute(['user_id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $pseudoUtilisateur = ucfirst($user['pseudo']);
        $creditsUtilisateur = $user['credits'];
        $idUtilisateur = $user['utilisateur_id'];
        $roleUtilisateur = $user['libelle'];
    } else {
        session_destroy();
    }
} else {
    // Utilisateur non connecté
    $idUtilisateur = null;
}
