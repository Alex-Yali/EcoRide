<?php
require_once __DIR__ . '/../src/service/init.php';
require '../src/controller/detailAvis.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Avis conducteur</title>

    <!-- Styles -->
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/pages/avis.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wdth,wght@0,75..100,700;1,75..100,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        <!-- Messages d'erreur -->
        <?php require '../src/service/messagesErreur.php'; ?>

        <section class="detail">
            <!-- Infos utilisateur -->
            <?php if (!empty($avis)): ?>

                <!-- Commentaire -->
                <section class="commentaire">
                    <?php foreach ($avis as $index => $a): ?>
                        <section id="utilisateur">
                            <img id="photo" src="./assets/images/homme.png" alt="photo de l'utilisateur">
                            <?= htmlspecialchars(ucfirst($a['auteur_pseudo'] ?? 'N/A')) ?>
                        </section>
                        <section class="box-avis">
                            <section>
                                Note : <?= htmlspecialchars(ucfirst($a['note'] ?? 'N/A')) ?>
                            </section>
                            <section class="com">
                                <?= htmlspecialchars(ucfirst($a['commentaire'] ?? 'N/A')) ?>
                            </section>
                        </section>
                        <?php if ($index < count($avis) - 1): ?>
                            <section class="separateurFiltres"></section>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </section>

            <?php else: ?>
                <p class="message-vide">Ce conducteur n'a pas encore d'avis</p>
            <?php endif; ?>
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