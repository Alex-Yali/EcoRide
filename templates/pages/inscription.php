<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Inscription</title>

    <!-- Styles -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/pages/inscription.css">

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
        <h1 class="gros-titre">Inscription :</h1>
        <!-- Formulaire -->
        <form id="formulaire" action="/inscription/" method="POST">

            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf ?? ''); ?>">
            <section class="input-group">
                <input type="text" name="pseudo" id="pseudo" placeholder="Pseudo" value="<?= htmlspecialchars($pseudo ?? '') ?>" required>
                <span class="iconForm"></span>
                <span class="error">Le pseudo doit être inférieur à 10 caractères</span>
            </section>
            <section class="input-group">
                <input type="email" id="email" name="email" placeholder="Email@mail.com" value="<?= htmlspecialchars($email ?? '') ?>" required>
                <span class="iconForm"></span>
                <span class="error">Le mail n'est pas au bon format</span>
            </section>
            <section class="input-group">
                <label for="password"></label><input type="password" name="password" id="password" placeholder="Mot de passe" required>
                <span id="togglePassword" class="eye-icon"><img src="/assets/images/oeil-ouvert.png" class="oeil" alt="oeil ouvert"></span>
            </section>
            <section class="progression">
                <section class="strength-meter">
                    <section id="strength-bar" class="strength-bar"></section>
                </section>
                <small id="strength-text"></small>
            </section>
            <section class="input-group">
                <label for="password"></label><input type="password" name="password_confirm" id="password_confirm" placeholder=" Confirmer mot de passe" required>
                <span id="togglePasswordConfirm" class="eye-icon"><img src="/assets/images/oeil-ouvert.png" class="oeil" alt="oeil ouvert"></span>
                <span class="error">Les mots de passe ne sont pas identiques</span>
            </section>

            <!-- Affichage du message d'erreur -->
            <?php require APP_ROOT . "/src/Service/MessagesErreur.php" ?>

            <button id="btnInscri" class="button" type="submit">S'inscrire</button>
            <a title="Deja inscrit ?" href="/connexion/" class="lien-membre">Vous êtes déja membre ?</a>
        </form>
        <div id="user-data" data-pseudo="<?php echo htmlspecialchars($pseudo); ?>"></div>
    </main>
    <footer>
        <!-- Footer -->
        <?php require APP_ROOT . "/templates/pages/includes/footer.php" ?>

        <!-- JS  -->
        <script src="/assets/js/main.js" type="module"></script>
        <script src="/assets/js/pages/inscription.js" type="module"></script>
</body>

</html>