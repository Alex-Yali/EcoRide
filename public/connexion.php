<?php
require_once __DIR__ . '/../src/service/init.php';
require_once __DIR__ . '/../src/controller/backConnexion.php';
require_once __DIR__ . '/../src/service/csrf.php';
$csrf = generate_csrf_token();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Connexion</title>

    <!-- Styles -->
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/pages/connexion.css">

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
        <h1 class="gros-titre">Je me connecte :</h1>

        <!-- Formulaire -->
        <form id="formulaire" action="" method="POST">

            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf); ?>">

            <section>
                <input type="email" id="email" name="email" placeholder="Email@mail.com" required>
            </section>

            <section>
                <input type="password" name="password" id="password" placeholder="Mot de passe" required>
            </section>

            <!-- Affichage du message d'erreur -->
            <?php require '../src/service/messagesErreur.php'; ?>

            <button id="btnConect" class="button" type="submit">Se connecter</button>

            <a title="Mot de passe oublié ?" href="./mdp.php" class="lien-membre">Mot de passe oublié ?</a>
            <a title="Vous n'êtes pas encore inscrit ?" href="./inscription.php" class="lien-membre">Vous n'êtes pas encore inscrit ?</a>
        </form>
    </main>

    <?php require 'includes/footer.php'; ?>
    <script src="./assets/js/main.js" type="module"></script>
</body>

</html>