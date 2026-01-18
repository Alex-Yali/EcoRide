<?php
require '../src/repository/infosUtilisateur.php';
require '../src/repository/gestionAvis.php';
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Historique avis</title>

    <!-- Styles -->
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/pages/avisEnCours.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wdth,wght@0,75..100,700;1,75..100,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Header -->
    <?php
    require 'includes/header.php';
    ?>
    <main>
        <h1 class="gros-titre">Historique des avis :</h1>
        <section class="user-menu">
            <!-- Info employe -->
            <section class="user-id">
                <section class="user-name">
                    <img src="./assets/images/compte noir.png" alt="image compte noir">
                    <span id="first-name"><?= htmlspecialchars($pseudoUtilisateur) ?></span>
                </section>
            </section>
            <!-- Avis -->
            <section class="boxAvis">
                <?php if (empty($avisCheck)): ?>
                    <p>Vous n'avez géré aucun avis.</p>
                <?php else: ?>
                    <?php foreach ($avisCheck as $a): ?>
                        <section class="commentaire">
                            <section class="utilisateur">
                                <img id="photo" src="./assets/images/homme.png" alt="photo de l'utilisateur">
                                <?= htmlspecialchars(ucfirst($a['auteur_pseudo'] ?? 'N/A')) ?>
                            </section>
                            <section class="avis">
                                <section class="note">
                                    Note : <?= htmlspecialchars(ucfirst($a['note'] ?? 'N/A')) ?>
                                </section>
                                <section class="com">
                                    <?= htmlspecialchars(ucfirst($a['commentaire'] ?? 'N/A')) ?>
                                </section>
                                <?php if ($a['statut'] === 'valider'): ?>
                                    <span style="color: #267240;">Avis validé</span>
                                <?php else: ?>
                                    <span style="color: red;">Avis refusé</span>
                                <?php endif; ?>
                            </section>
                            <input type="hidden" name="avis_id" value="<?= $a['avis_id'] ?>">
                        </section>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>
        </section>
    </main>
    <!-- Footer -->
    <?php
    require 'includes/footer.php';
    ?>
    <!-- JS  -->
    <script src="./assets/js/main.js" type="module"></script>
</body>

</html>