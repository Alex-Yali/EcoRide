<?php require 'back/detailAvis.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Avis conducteur</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/pages/avis.css">
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
        <h1 class="gros-titre">Avis du conducteur :</h1>
        <section class="detail">
            <?php foreach ($avis as $a): ?>
                    <!-- Infos utilisateur -->
                    <aside class="box-user">
                        <section class="user-photo">
                            <img id="photo" src="./assets/images/homme.png" alt="photo de l'utilisateur">
                            <p>
                                <?= htmlspecialchars(ucfirst($a['pseudo'] ?? 'N/A')) ?><br>
                                <?= round($a['moyenne'], 1) ?> ★
                            </p>
                        </section>
                    </aside>

                    <!-- Commentaire -->
                    <section class="box-avis">
                        <?= htmlspecialchars(ucfirst($a['commentaires'] ?? 'N/A')) ?>
                    </section>
            <?php endforeach; ?>
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