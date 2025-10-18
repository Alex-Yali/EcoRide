<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Covoiturages en cours</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/pages/mescovoiturages.css">
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
        <h1 class="gros-titre">Covoiturages en cours :</h1>
            <section class="box-covoit">
                <p id="date-covoit">Jeudi 26 juin</p>
                <section class="info-covoit">
                    <section class="time-covoit">
                        <section class="start-time">
                            <p>Paris<br>08:20</p>
                        </section>
                        <section>
                            <p class="duree">5h10</p>
                            <section class="ligne"></section>
                        </section>
                        <section class="end-time">
                            <p>Lyon<br>13:30</p>
                        </section>
                        <section class="nbr-place">
                            <p>3 places</p>
                        </section>
                        <section class="prix-place">
                            <p>5 crédits</p>
                        </section>
                    </section>
                    <section class="perso-covoit">
                        <section class="perso">
                            <img class="icon-perso" src="./assets/images/voiture-noir.png" alt="icon voiture noir">
                            <img class="icon-perso" src="./assets/images/homme.png" alt="icon homme">
                            <section class="perso-avis">
                                <p>Alex<br>★ 4,6</p>
                            </section>
                        </section>
                        <button id="btnDetail" type="submit">Détails</button>
                    </section>
                </section>
            </section>
    </main>
    <!-- Footer -->
    <?php
    require 'includes/footer.php'
    ?>
        <!-- JS  -->
    <script src="./assets/js/main.js" type="module"></script>
</body>
</html>