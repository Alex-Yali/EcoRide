<?php
require_once __DIR__ . '/../service/db.php';
require_once __DIR__ . '/../service/csrf.php';
$message = '';
$startCredit = 20;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        // Vérification CSRF
        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            $message = "Erreur CSRF : Requête invalide.";
            return;
        }

        $pseudo = $_POST['pseudo'] ?? '';
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        //Vérifier si un champ est vide
        if ($pseudo === '' || $email === '' || $password === '' || $passwordConfirm === '') {
            $message = "Veuillez renseigner le pseudo, l'email et le mot de passe.";

            //Vérifier la longueur du pseudo
        } elseif (mb_strlen($pseudo) > 10) {
            $message = "Le pseudo ne doit pas dépasser 10 caractères.";

            //Vérifier si le mot de passe respect notre demande
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{9,}$/', $password)) {
            $message = "Le mot de passe doit contenir au moins 9 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.";
        } elseif ($passwordConfirm !== $password) {
            $message = "Les mots de passe ne correspondent pas";

            //Vérifier si l'utilisateur existe déjà
        } else {
            $sqlDejaInscri = "SELECT * FROM utilisateur WHERE email = :email OR pseudo = :pseudo LIMIT 1";
            $stmtDejaInscri = $pdo->prepare($sqlDejaInscri);
            $stmtDejaInscri->execute([
                'email' => $email,
                'pseudo' => $pseudo,
            ]);
            $user = $stmtDejaInscri->fetch(PDO::FETCH_ASSOC);

            //Cas utilisateur existe déjà
            if ($user) {
                $message = "Un utilisateur avec ce pseudo ou cet email existe déjà.";

                //Cas utilisateur n'existe pas
            } else {
                // Hashage du mot de passe en utilisant BCRYPT
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                // Ajout utilisateur table utilisateur
                $sqlInscri = "INSERT INTO utilisateur (pseudo, email, password, credits) VALUES (:pseudo, :email, :password, :credits)";
                $stmtInscri = $pdo->prepare($sqlInscri);
                $stmtInscri->bindValue(':email', $email);
                $stmtInscri->bindValue(':pseudo', $pseudo);
                $stmtInscri->bindValue(':credits', $startCredit);
                $stmtInscri->bindValue(':password', $hashedPassword);

                if ($stmtInscri->execute()) {
                    $user_id = $pdo->lastInsertId();

                    // Ajout role table possede
                    $sqlAddRole = "INSERT INTO possede (utilisateur_utilisateur_id, role_role_id)
                                VALUES (:utilisateur, :role)";
                    $stmtAddRole = $pdo->prepare($sqlAddRole);
                    $stmtAddRole->execute([
                        ':utilisateur' => $user_id,
                        ':role' => 3
                    ]);

                    // Stockage session
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['user_pseudo'] = $pseudo;
                    $_SESSION['email'] = $email;
                    $_SESSION['user_credits'] = $startCredit;

                    // Message de bienvenue
                    $_SESSION['inscription_ok'] = true;

                    header('Location: espace.php');
                    exit;
                } else {
                    $message = "Impossible de s'inscrire";
                }
            }
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        $message  = "Erreur lors de l’ajout : " . $e->getMessage();
    }
}
