<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Contact</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/pages/contact.css">
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
        <!-- Formulaire -->
         <form action="" method="post" id="contact-form">
            <h1>Contactez-nous :</h1>
            <section class="contact-section">
                <label for="name">Nom :</label><input type="text" name="nom" id="nom" required>
            </section>
            <section class="contact-section">
                <label for="name">Prénom :</label><input type="text" name="nom" id="prenom" required>
            </section>
            <section class="contact-section">
                <label for="name">Adresse email :</label><input type="mail" name="mail" id="email" required>
            </section>
            <section class="contact-section">
                <label for="name">Message :</label><textarea name="message" id="message"></textarea>
            </section>
            <button id="btnContact" class="button" type="submit">Envoyer</button>
         </form>
    </main>
    <!-- Footer -->
    <?php
    require 'includes/footer.php'
    ?>
        <!-- JS  -->
    <script src="./assets/js/main.js" type="module"></script>
    <script src="./assets/js/pages/contact.js" type="module"></script>
</body>
</html>