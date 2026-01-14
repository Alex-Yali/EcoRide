<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Modification mot de passe</title>

    <!-- Styles -->
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/pages/mdp.css">

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
        <h1>Veuillez entrer l'adresse e-mail associée à votre compte afin <br> de recevoir un lien de réinitialisation de votre mot de passe :</h1>
        <!-- Formulaire -->
        <form id="formulaire">
            <section>
                <label for="email"></label><input type="email" id="email" name="email" placeholder="Email@mail.com" required>
            </section>
            <button id="btnMdp" class="button" type="submit">Envoyer le lien</button>
            <a title="Deja inscrit ?" href="./connexion.php" class="lien-membre">Vous êtes déja membre ?</a>
        </form>
    </main>
    <!-- Footer -->
    <?php
    require 'includes/footer.php'
    ?>
        <!-- JS  -->
    <script src="./assets/js/main.js" type="module"></script>
</body>
</html>