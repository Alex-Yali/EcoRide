<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Proposer un covoiturage</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/pages/trajet.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wdth,wght@0,75..100,700;1,75..100,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <?php
    require 'includes/headerConect.php'
    ?>
    <main>
        <!-- Proposer trajet -->
        <h1 class="gros-titre">Saisir un voyage :</h1>
        <form id="trajet">
            <section class="ajouter-trajet">
                <!-- Départ -->
                <section class="nav-cat">
                    <img src="./assets/images/Cars 1.png" class="icon" alt="image voiture">
                    <input id="depart2" type="text" placeholder="Départ">
                </section>
                <!-- Destination -->
                <section class="nav-cat">
                    <img src="./assets/images/ping.png" class="icon" alt="image destination">
                    <input id="destination2" type="text" placeholder="Destination">
                </section>
                <!-- Calendrier -->
                <section class="nav-cat">
                <img src="./assets/images/calendrier gris.png" class="icon" alt="image calendrier">
                <input id="date2" type="date" placeholder="Choisir une date" aria-label="Date" />
                </section>
                <!-- Nombre place -->
                <section class="nav-cat">
                    <img src="./assets/images/compte gris.png" class="icon" alt="image personne">
                    <label><input type="number" name="places" id="places2" placeholder="Nombre place" required></label>
                </section>
                <!-- Prix -->
                <section class="nav-cat">
                    <img src="./assets/images/prix gris.png" class="icon" alt="image piece">
                    <label>
                        <input type="number" name="prix" id="prix2" placeholder="Saisir prix" required>
                        <span class="info-icon" title="2 crédits seront déduit de votre solde">ℹ️</span>
                    </label>
                </section>
                <!-- Véhicule -->
                <section class="nav-cat">
                <img src="./assets/images/ajouter voiture gris.png" class="icon" alt="image ajout voiture">
                <select id="cars2" name="nav" required>
                    <option value="" disabled selected hidden>Véhicules</option>
                    <option value="vehicule 1">Véhicule 1</option>
                </select>
                <img src="./assets/images/icon plus.png" id="icon-plus" alt="icon plus">
                </section>
                <!-- Bouton -->
                <button id="btnTrajet" type="submit">Publier</button>
            </section>
            <img src="./assets/images/voiture 1.jpg" id="voiture" alt="image voiture orange">
        </form>
    </main>
    <!-- Footer -->
    <?php
    require 'includes/footer.php'
    ?>
        <!-- JS  -->
    <script src="./assets/js/main.js" type="module"></script>
    <script src="./assets/js/pages/trajet.js" type="module"></script>
</body>
</html>