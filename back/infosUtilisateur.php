<?php 

require_once 'back/db.php'; // connexion PDO

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier que l'utilisateur est connecté
if (empty($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

// Récupérer l'id de l'utilisateur depuis la session
$user_id = $_SESSION['user_id'];

// Requête pour récupérer le pseudo et les crédits à jour
$stmt = $pdo->prepare('SELECT pseudo, credits, utilisateur_id FROM utilisateur WHERE utilisateur_id = :user_id');
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $pseudoUtilisateur = htmlspecialchars(ucfirst($user['pseudo']), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $creditsUtilisateur = htmlspecialchars($user['credits'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $idUtilisateur = htmlspecialchars($user['utilisateur_id'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
} else {
    // utilisateur non trouvé → déconnexion
    session_destroy();
    header('Location: connexion.php');
    exit;
}

?>