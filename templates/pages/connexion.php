<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Connexion</title>

    <!-- Styles -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/pages/connexion.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wdth,wght@0,75..100,700;1,75..100,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Header -->
    <?php require APP_ROOT . "/templates/pages/includes/header.php" ?>

    <main>
        <h1 class="gros-titre">Je me connecte :</h1>

        <!-- Formulaire -->
        <form id="formulaire" action="/connexion/" method="POST">

            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf  ?? ''); ?>">

            <section class="input-group">
                <input type="email" id="email" name="email" placeholder="Email@mail.com" value="<?= htmlspecialchars($email ?? '') ?>" required>
                <span class="iconForm"></span>
                <span class="error">Le mail n'est pas au bon format</span>
            </section>

            <section class="input-group">
                <input type="password" name="password" id="password" placeholder="Mot de passe" required>
                <span id="togglePassword" class="eye-icon"><img src="/assets/images/oeil-ouvert.png" class="oeil" alt="oeil ouvert"></span>
            </section>

            <!-- Affichage du message d'erreur -->
            <?php require APP_ROOT . "/src/Service/messagesErreur.php" ?>
            <p class="errorMessage"></p>

            <button id="btnConect" class="button" type="button">Se connecter</button>

            <a title="Mot de passe oublié ?" href="/mdp/" class="lien-membre">Mot de passe oublié ?</a>
            <a title="Vous n'êtes pas encore inscrit ?" href="/inscription/" class="lien-membre">Vous n'êtes pas encore inscrit ?</a>
        </form>
    </main>

    <!-- Footer -->
    <?php require APP_ROOT . "/templates/pages/includes/footer.php" ?>

    <script src="/assets/js/main.js" type="module"></script>
    <script src="/assets/js/pages/connexion.js" type="module"></script>
</body>

</html>