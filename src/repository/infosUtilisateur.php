<?php
require_once __DIR__ . '/../service/db.php'; // connexion PDO

$user = null;

// Vérifier que l'utilisateur est connecté
if (!empty($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Récupérer pseudo, crédits et rôle
    $stmt = $pdo->prepare('SELECT u.pseudo, u.credits, u.utilisateur_id, r.libelle, u.passager FROM utilisateur u
                            JOIN possede p ON p.utilisateur_utilisateur_id = u.utilisateur_id
                            JOIN role r ON p.role_role_id = r.role_id
                            WHERE u.utilisateur_id = :user_id');
    $stmt->execute(['user_id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $pseudoUtilisateur = ucfirst($user['pseudo']);
        $creditsUtilisateur = max(0, $user['credits']);
        $idUtilisateur = $user['utilisateur_id'];
        $roleUtilisateur = $user['libelle'];
    } else {
        session_destroy();
    }
} else {
    // Utilisateur non connecté
    $idUtilisateur = null;
}
