<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Mes véhicules</title>

    <!-- Styles -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/pages/mesVehicules.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wdth,wght@0,75..100,700;1,75..100,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Header -->
    <?php require APP_ROOT . "/templates/pages/includes/header.php" ?>

    <main>
        <h1 class="gros-titre">Mes véhicules :</h1>
        <section class="user-menu">
            <!-- Info utilisateur -->
            <section class="user-id">
                <section class="user-name">
                    <img src="/assets/images/compte noir.png" alt="image compte noir">
                    <span id="first-name"><?= htmlspecialchars($infosUtilisateur->getPseudo()) ?></span>
                </section>
                <section class="user-info">
                    <img src="/assets/images/pile-de-pieces.png" alt="image pieces noir">
                    <span>Crédits restants : <?= htmlspecialchars($infosUtilisateur->getCredits()) ?></span>
                </section>
            </section>
            <!-- Voitures -->
            <?php if (empty($voituresUtilisateur)): ?>
                <p>Vous n'avez aucun véhicule associé à votre compte.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Marque</th>
                            <th>Modèle</th>
                            <th>Immat</th>
                            <th>Couleur</th>
                            <th>Énergie</th>
                            <th>Date 1ère immat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($voituresUtilisateur as $voiture): ?>
                            <tr>
                                <td><?= htmlspecialchars(ucfirst($voiture->getLibelle() ?? 'N/A')) ?></td>
                                <td><?= htmlspecialchars(ucfirst($voiture->getModele() ?? 'N/A')) ?> </td>
                                <td><?= htmlspecialchars(ucfirst($voiture->getImmatriculation() ?? 'N/A')) ?></td>
                                <td><?= htmlspecialchars(ucfirst($voiture->getCouleur() ?? 'N/A')) ?></td>
                                <td><?= htmlspecialchars(ucfirst($voiture->getEnergie() ?? 'N/A')) ?></td>
                                <td><?= htmlspecialchars(ucfirst($voiture->getDatePremiereImmatriculation() ?? 'N/A')) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </main>
    <!-- Footer -->
    <?php require APP_ROOT . "/templates/pages/includes/footer.php" ?>

    <!-- JS  -->
    <script src="/assets/js/main.js" type="module"></script>
</body>

</html>