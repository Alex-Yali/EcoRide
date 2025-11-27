<?php
require_once 'db.php';
require_once 'infosUtilisateur.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
        //Vérifier si un champ est vide
    if ($email === '' || $password === '') {
        $message = "Veuillez renseigner l'email et le mot de passe.";
        //Vérifier si les infos sont bonnes
    } else {
        $pdoStatement = $pdo->prepare("SELECT utilisateur_id, pseudo, email, password, credits FROM utilisateur 
                                        WHERE email = :email");
        $pdoStatement->execute(['email' => $email]);
        $user = $pdoStatement->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            $message = "Email ou mot de passe incorrect.";
            //Si infos bonnes on vérifie si le mot de passe est hashé (bcrypt)
        } else {
            $dbPass = $user['password'];
            $is_hashed = (strpos($dbPass, '$2y$') === 0);
            // Mot de passe déjà haché -> vérifier avec password_verify
            if ($is_hashed) {
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
                // Mot de passe en clair : on vérifie l'égalite puis on hache et met à jour
                if ($password === $dbPass) {
                    // On hash le mot de passe
                    $newHash = password_hash($password, PASSWORD_BCRYPT);
                    // On modifie dans la base de donnée
                    $update = $pdo->prepare("UPDATE utilisateur SET password = :hash WHERE utilisateur_id = :id");
                    $update->bindValue('hash', $newHash);
                    $update->bindValue('id', $user['utilisateur_id']);
                    $update->execute();
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