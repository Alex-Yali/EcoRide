<?php require_once 'back/detailCovoiturage.php';
require_once 'back/fonctionCalculTrajetDetail.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Details du voyage</title>
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
            <form class="box-detail" method="POST" >
                <section class="user-photo">
                    <img id="photo" src="./assets/images/homme.png" alt="photo de l'utilisateur">
                    <p><?= htmlspecialchars(ucfirst($covoit['pseudo'] ?? 'N/A')) ?><br>
                        <a href="#" class="note" data-id="<?= htmlspecialchars($covoit['covoiturage_id']) ?>">
                            <?= $covoit['moyenne'] ? round($covoit['moyenne'], 1) . ' ★' : 'Non noté' ?>
                        </a>
                    </p>
                </section>

                <section class="separateurFiltres"></section>

                <section class="user-detail">
                    <img class="user-icon" src="<?= htmlspecialchars($image) ?>" alt="icon voiture">
                    <p class="detail-size"><?= htmlspecialchars(ucfirst($covoit['marqueVoiture'] ?? 'N/A')) ?> : <?= htmlspecialchars(ucfirst($covoit['modele'] ?? 'N/A')) ?><br>
                        Energie : <?= htmlspecialchars(ucfirst($covoit['energie'] ?? 'N/A')) ?></p>
                </section>

                <section class="separateurFiltres"></section>

                <section class="user-detail">
                    <img class="user-icon" src="./assets/images/pattes.png" alt="icon pate animal">
                    <p class="detail-size">Animaux : autorisés</p>
                </section>

                <section class="separateurFiltres"></section>

                <section class="user-detail">
                    <img class="user-icon" src="./assets/images/fumeur.png" alt="icon fumer">
                    <p class="detail-size">Non fumeur</p>
                </section>
            </form>
            <section class="box-covoit">
                <section class="info-covoit">
                    <p id="date-covoit"><?= htmlspecialchars(ucfirst($dateDetailCovoit ?? '')) ?></p>
                    <section class="time-covoit">
                        <section class="start-time">
                            <p><?= htmlspecialchars(ucfirst($covoit['lieu_depart'])) ?><br><?= date('H:i', strtotime($covoit['heure_depart'])) ?></p>
                        </section>
                        <section>
                            <p class="duree"><?= htmlspecialchars($dureeCovoit) ?></p>
                            <section class="ligne"></section>
                        </section>
                        <section class="end-time">
                            <p><?= htmlspecialchars(ucfirst($covoit['lieu_arrivee'])) ?><br><?= date('H:i', strtotime($covoit['heure_arrivee'])) ?></p>
                        </section>
                        <section class="nbr-place">
                            <p><?= htmlspecialchars($covoit['nb_place']) ?> places</p>
                        </section>
                    </section>
                </section>
                <section class="participe">
                    <section class="prix-place">
                        <p>1 passager</p>
                        <p><?= htmlspecialchars($covoit['prix_personne']) ?> crédits</p>
                    </section>
                    <button id="btnReserve" class="button" type="button">Participer</button>
                </section>
                <section class="valid">
                    <p id="valid-size">Souhaitez vous utiliser <?= htmlspecialchars($covoit['prix_personne']) ?> crédits pour réserver votre place sur ce voyage ?</p>
                    <section id="notif-valid">
                        <a href="detail.php?id=<?= urlencode($covoit['covoiturage_id']) ?>">Retour</a>
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
    <script src="./assets/js/pages/detail.js" type="module"></script>
        <!-- Vérification si l'utilisateur est connecté ou non  -->
    <script> const isConect = <?php echo isset($_SESSION['user_pseudo']) ? 'true' : 'false'; ?>; </script>
</body>
</html>