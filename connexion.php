<?php
session_start();
require_once 'back/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $message = "Veuillez renseigner l'email et le mot de passe.";
    } else {
        $pdoStatement = $pdo->prepare('SELECT utilisateur_id, pseudo, email, password, credits FROM utilisateur WHERE email = :email LIMIT 1');
        $pdoStatement->execute(['email' => $email]);
        $user = $pdoStatement->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $message = "Email ou mot de passe incorrect.";
        } else {
            $dbPass = $user['password'];

            // Détection simple d'un hash bcrypt/argon2 (ajoute d'autres préfixes si besoin)
            $is_hashed = (strpos($dbPass, '$2y$') === 0) || (strpos($dbPass, '$argon2') === 0);

            if ($is_hashed) {
                // Mot de passe déjà haché -> vérifier avec password_verify
                if (password_verify($password, $dbPass)) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['utilisateur_id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_pseudo'] = $user['pseudo'];
                    $_SESSION['user_credits'] = $user['credits'];
                    header('Location: espace.php');
                    exit;
                } else {
                    $message = "Email ou mot de passe incorrect.";
                }
            } else {
                // Mot de passe en clair en base : on vérifie l'égalite puis on hache et met à jour
                if ($password === $dbPass) {
                    // Hachage sécurisé via PHP
                    $newHash = password_hash($password, PASSWORD_DEFAULT);

                    $update = $pdo->prepare("UPDATE utilisateur SET password = :hash WHERE utilisateur_id = :id");
                    $update->execute(['hash' => $newHash, 'id' => $user['utilisateur_id']]);

                    // Ensuite connecter l'utilisateur
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['utilisateur_id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_pseudo'] = $user['pseudo'];
                    $_SESSION['user_credits'] = $user['credits'];
                    header('Location: espace.php');
                    exit;
                } else {
                    $message = "Email ou mot de passe incorrect.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Connexion</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/pages/connexion.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wdth,wght@0,75..100,700;1,75..100,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <?php
    require 'includes/header.php'
    ?>
    <main>
        <h1 class="gros-titre">Je me connecte :</h1>

        <!-- Formulaire -->
        <form id="formulaire" action="" method="POST">
            <section>
                <input type="email" id="email" name="email" placeholder="Email@mail.com" required>
            </section>

            <section>
                <input type="password" name="password" id="password" placeholder="Mot de passe" required>
            </section>

            <!-- Affichage du message d'erreur -->
            <?php require 'back/messagesErreur.php'; ?> 

            <button id="btnConect" class="button" type="submit">Se connecter</button>

            <a title="Mot de passe oublié ?" href="./mdp.php" class="lien-membre">Mot de passe oublié ?</a>
            <a title="Vous n'êtes pas encore inscrit ?" href="./inscription.php" class="lien-membre">Vous n'êtes pas encore inscrit ?</a>
        </form>
    </main>

    <?php require 'includes/footer.php'; ?>
    <script src="./assets/js/main.js" type="module"></script>
</body>
</html>