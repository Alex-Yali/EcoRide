<?php
require_once 'db.php';

$message = '';
$startCredit = 20;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = $_POST['pseudo'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

        //Vérifier si un champ est vide
    if ($pseudo === '' || $email === '' || $password === '') {
        $message = "Veuillez renseigner le pseudo, l'email et le mot de passe.";
        //Vérifier si le mot de passe respect notre demande
    }elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{9,}$/', $password)) {
        $message = "Le mot de passe doit contenir au moins 9 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.";
        //Vérifier si l'utilisateur existe déjà
    }else {
        $stmt = $pdo->prepare('SELECT * FROM utilisateur WHERE email = :email OR pseudo = :pseudo LIMIT 1');
        $stmt->execute([
            'email' => $email,
            'pseudo' => $pseudo,
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
            //Cas utilisateur existe déjà
        if ($user) {
            $message = "Un utilisateur avec ce pseudo ou cet email existe déjà.";
            //Cas utilisateur n'existe pas
        }else {
             // Hashage du mot de passe en utilisant BCRYPT
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            
            $statement = $pdo->prepare('INSERT INTO utilisateur (pseudo, email, password, credits) VALUES (:pseudo, :email, :password, :credits)');
            $statement->bindValue(':email', $email);
            $statement->bindValue(':pseudo', $pseudo);
            $statement->bindValue(':credits', $startCredit);
            $statement->bindValue(':password', $hashedPassword); 
            if ($statement->execute()) {
                $user_id = $pdo->lastInsertId(); //Récupère l’ID du nouvel utilisateur
                $_SESSION['user_id'] = $user_id; //Stocke les informations essentielles en session
                $_SESSION['user_pseudo'] = $pseudo;
                $_SESSION['email'] = $email;
                $_SESSION['user_credits'] = $startCredit;
                header('Location: espace.php');
            exit;
            }else {
                $message = "Impossible de s'inscrire";
            }
        }
    }
}
?>