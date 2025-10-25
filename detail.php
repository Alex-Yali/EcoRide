<?php require_once 'back/infosCovoiturage.php';
require_once 'back/fonctionDate.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Details</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/pages/detail.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wdth,wght@0,75..100,700;1,75..100,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <?php
    require 'includes/header.php'
    ?>
    <main>
        <!-- Box filtres -->
         <h1 class="gros-titre">Détail du voyage :</h1>
         <section class="detail">
            <aside class="box-detail">
                <section class="user-photo">
                    <img id="photo" src="./assets/images/homme.png" alt="photo de l'utilisateur">
                    <p><?= htmlspecialchars($covoits['pseudo'] ?? 'N/A') ?><br>
                        ★ <?= htmlspecialchars($c['note'] ?? 'N/A') ?>
                    </p>
                </section>
                <section class="user-detail">
                    <img class="user-icon" src="./assets/images/voiture-noir.png" alt="icon voiture">
                    <p class="detail-size">Renault : Mégane 4 <br> Essence</p>
                </section>
                <section class="user-detail">
                    <img class="user-icon" src="./assets/images/pattes.png" alt="icon pate animal">
                    <p class="detail-size">Animaux : autorisés</p>
                </section>
                <section class="user-detail">
                    <img class="user-icon" src="./assets/images/fumeur.png" alt="icon fumer">
                    <p class="detail-size">Non fumeur</p>
                </section>
            </aside>
            <section class="box-covoit">
                <section class="info-covoit">
                    <p id="date-covoit">Jeudi 26 juin</p>
                    <section class="time-covoit">
                        <section class="start-time">
                            <p>Paris</p>
                            <p>08:20</p>
                        </section>
                        <section>
                            <p class="duree">5h10</p>
                            <section class="ligne"></section>
                        </section>
                        <section class="end-time">
                            <p>Lyon</p>
                            <p>13:30</p>
                        </section>
                        <section class="nbr-place">
                            <p>3 places</p>
                        </section>
                    </section>
                </section>
                <section class="participe">
                    <section class="prix-place">
                        <p>1 passager</p>
                        <p>5 crédits</p>
                    </section>
                    <button id="btnReserve" class="button" type="button">Participer</button>
                </section>
                <section class="valid">
                    <p id="valid-size">Souhaitez vous utiliser 5 crédits pour réserver votre place sur ce voyage ?</p>
                    <section id="notif-valid">
                        <a href="./detail.php">Retour</a>
                        <button id="btnValid" class="button" type="submit">Oui</button>
                    </section>
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
        <!-- Vérification si l'utilisateur est connecté ou non  -->
    <script> const isConect = <?php echo isset($_SESSION['user_pseudo']) ? 'true' : 'false'; ?>; </script>
    <script src="./assets/js/pages/detail.js" type="module"></script>
</body>
</html>