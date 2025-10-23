<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Accueil</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/pages/accueil.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wdth,wght@0,75..100,700;1,75..100,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <?php
    require 'includes/header.php'
    ?>
    <main> 
        <h1>Un trajet partagé, un monde allégé</h1>
    <!-- Barre de recherche -->
    <?php
    require 'includes/barreRecherche.php'
    ?>
    <!-- Affichage du message d'erreur -->
    <?php require 'back/messagesErreur.php'; ?> 
        <!-- Image et texte description site -->
        <section class="descri">
            <section class="image-descri">
                <img src="./assets/images/voiture 2.png" class="image-voiture" alt="image voiture">
                <section class="text-box">
                    <h2>EcoRide</h2>
                    <p class="text">EcoRide est une startup française dédiée à la promotion du covoiturage écologique. Notre plateforme propose des trajets respectueux de l'environnement, en privilégiant l'utilisation de véhicules écologiques, comme les voitures électriques.</p>
                </section>
            </section>
            <section class="image-descri">
                <img src="./assets/images/voiture 4.jpg" class="image-voiture" alt="image voiture">
                <section class="text-box">
                    <h2>Voyagez moins chère</h2>
                    <p class="text">Voyager à plusieurs permet de diviser les coûts du carburant, des péages ou des frais d'entretien du véhicule. Que vous soyez conducteur ou passager, le covoiturage est une solution économique et pratique pour tous..</p>
                </section>
            </section>
            <section class="image-descri">
                <img src="./assets/images/voiture 3.jpg" class="image-voiture" alt="image voiture">
                <section class="text-box">
                    <h2>Fiabilité</h2>
                    <p class="text">Avec notre onglet “détail”, il est possible d’accéder en un clic à toutes les informations dont vous avez besoin pour votre voyage (avis, modèle véhicule, préférence conducteur).</p>
                </section>
            </section>
        </section> 

        <!-- Image et texte foret --> 
            <img src="./assets/images/route foret.jpg" class="image-foret" alt="image route foret">
    </main>
    <!-- Footer -->
    <?php
    require 'includes/footer.php'
    ?>
        <!-- JS  -->
    <script src="./assets/js/main.js"></script>
</body>
</html>