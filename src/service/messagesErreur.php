    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Styles -->
        <link rel="stylesheet" href="/assets/css/style.css">
    </head>

    <body>

        <!-- Messages d'erreur globaux -->
        <?php if (!empty($message)) : ?>
            <p class="<?= 'errorMessage' ?>">
                <?= htmlspecialchars($message) ?>
            </p>
        <?php endif; ?>

        <!-- Messages d'erreur recherche covoiturage -->
        <?php if (!empty($messageCovoit)) : ?>
            <p class="<?= ($covoitValide ?? false) ? 'successMessageCovoit' : 'errorMessageCovoit' ?>">
                <?= htmlspecialchars($messageCovoit) ?>
            </p>
        <?php endif; ?>

        <!-- Messages d'erreur ajout voiture -->
        <?php if (!empty($messageVoiture)) : ?>
            <p class="<?= ($voitureValide  ?? false) ? 'successMessageCovoit' : 'errorMessageCovoit' ?>">
                <?= htmlspecialchars($messageVoiture) ?>
            </p>
        <?php endif; ?>

        <!-- Messages d'erreur création compte employé -->
        <?php if (!empty($messageCompte)) : ?>
            <p class="<?= ($compteValide  ?? false) ? 'successMessageCovoit' : 'errorMessageCovoit' ?>">
                <?= htmlspecialchars($messageCompte) ?>
            </p>
        <?php endif; ?>

        <!-- Messages d'erreur suspension compte -->
        <?php if (!empty($messageSusp)): ?>
            <p style="color: <?= ($compteSusp ?? false) ? 'green' : 'red' ?>; text-align:center; margin:0;">
                <?= htmlspecialchars($messageSusp) ?>
            </p>
        <?php endif; ?>

    </body>

    </html>