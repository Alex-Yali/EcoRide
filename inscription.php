<?php
session_start();
require_once 'back/db.php';

$message = '';
$startCredit = 20;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = $_POST['pseudo'] ?? '';
    $_SESSION['user_pseudo'] = $pseudo;
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
        //Cas utilisateur existe déjà ou non
        if ($user) {
            $message = "Un utilisateur avec ce pseudo ou cet email existe déjà.";
        } else {
            $stmt = $pdo->prepare('INSERT INTO utilisateur (pseudo, email, password, credits) VALUES (:pseudo, :email, :password, :credits)');
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt->execute([
                'pseudo' => $pseudo,
                'email' => $email,
                'credits'=> $startCredit,
                'password' => $hashedPassword
            ]);
            $user_id = $pdo->lastInsertId(); //Récupère l’ID du nouvel utilisateur
            $_SESSION['user_id'] = $user_id; //Stocke les informations essentielles en session
            $_SESSION['user_pseudo'] = $pseudo;
            $_SESSION['email'] = $email;
            $_SESSION['user_credits'] = $startCredit;
            header('Location: espace.php');
            exit;
}}
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Inscription</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/pages/inscription.css">
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
        <h1 class="gros-titre">Inscription :</h1>
        <!-- Formulaire -->
        <form id="formulaire" action="inscription.php" method="POST">
            <section>
                <label for="pseudo"></label><input type="text" name="pseudo" id="pseudo" placeholder="Pseudo" required>
            </section>
            <section>
                <label for="email"></label><input type="email" id="email" name="email" placeholder="Email@mail.com" required>
            </section>
            <section>
                <label for="password"></label><input type="password" name="password" id="password" placeholder="Mot de passe" required>
            </section>

            <!-- Affichage du message d'erreur -->
            <?php require 'back/messagesErreur.php'; ?> 

            <button id="btnInscri" class="button" type="submit">S'inscrire</button>
            <a title="Deja inscrit ?" href="./connexion.php" class="lien-membre">Vous êtes déja membre ?</a>
        </form>
        <div id="user-data" data-pseudo="<?php echo htmlspecialchars($pseudo); ?>"></div>
    </main>
    <footer>
    <!-- Footer -->
    <?php
    require 'includes/footer.php'
    ?>
        <!-- JS  -->
    <script src="./assets/js/main.js" type="module"></script>
    <!--<script src="./assets/js/pages/inscription.js" type="module"></script>-->
</body>
</html>