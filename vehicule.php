    <?php
    require 'back/infosUtilisateur.php';
    require 'back/mesVoitures.php';
    ?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Mes véhicules</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/pages/mesVehicules.css">
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
        <h1 class="gros-titre">Mes véhicules :</h1>
            <assectionide class="user-menu">
                <!-- Info utilisateur -->
                <section class="user-id">
                    <section class="user-name">
                        <img src="./assets/images/compte noir.png" alt="image compte noir">
                        <span id="first-name"><?= htmlspecialchars ($pseudoUtilisateur) ?></span>
                    </section>
                    <section class="user-info">
                        <img src="./assets/images/pile-de-pieces.png" alt="image pieces noir">
                        <span>Crédits restants : <?= htmlspecialchars ($creditsUtilisateur) ?></span>
                    </section>
                </section>
                <!-- Voitures -->
                <?php if (empty($voitures)): ?>
                        <p>Vous n'avez aucun véhicule associé à votre compte.</p>
                    <?php else: ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Marque</th>
                                    <th>Modèle</th>
                                    <th>Immatriculation</th>
                                    <th>Couleur</th>
                                    <th>Énergie</th>
                                    <th>Date 1ère immatriculation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($voitures as $voiture): ?>
                                    <tr>
                                        <td><?= htmlspecialchars(ucfirst($voiture['libelle'] ?? 'N/A')) ?></td>
                                        <td><?= htmlspecialchars(ucfirst($voiture['modele'] ?? 'N/A')) ?> </td>
                                        <td><?= htmlspecialchars(ucfirst($voiture['immatriculation'] ?? 'N/A')) ?></td>
                                        <td><?= htmlspecialchars(ucfirst($voiture['couleur'] ?? 'N/A')) ?></td>
                                        <td><?= htmlspecialchars(ucfirst($voiture['energie'] ?? 'N/A')) ?></td>
                                        <td><?= htmlspecialchars(ucfirst($voiture['date_premiere_immatriculation'] ?? 'N/A')) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
    </main>
    <!-- Footer -->
    <?php
    require 'includes/footer.php'
    ?>
        <!-- JS  -->
    <script src="./assets/js/main.js" type="module"></script>
</body>
</html>