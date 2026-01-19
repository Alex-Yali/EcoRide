<?php
require_once __DIR__ . '/../src/service/init.php';
require '../src/controller/backInscris.php';
require_once '../src/service/csrf.php';
$csrf = generate_csrf_token();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Inscription</title>

    <!-- Styles -->
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/pages/inscription.css">

    <!-- Google Fonts -->
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
        <form id="formulaire" action="" method="POST">

            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf); ?>">
            <section>
                <label for="pseudo"></label><input type="text" name="pseudo" id="pseudo" placeholder="Pseudo" required>
            </section>
            <section>
                <label for="email"></label><input type="email" id="email" name="email" placeholder="Email@mail.com" required>
            </section>
            <section>
                <label for="password"></label><input type="password" name="password" id="password" placeholder="Mot de passe" required>
            </section>
            <section>
                <label for="password"></label><input type="password" name="password_confirm" id="password" placeholder=" Confirmer mot de passe" required>
            </section>

            <!-- Affichage du message d'erreur -->
            <?php require '../src/service/messagesErreur.php'; ?>

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
        <script src="./assets/js/pages/inscription.js" type="module"></script>
</body>

</html>