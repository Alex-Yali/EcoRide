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

<?php if (!empty($_SESSION['messageVoiture'])): ?>
    <p class="<?= !empty($_SESSION['voitureValide'] ?? false) ? 'successMessageCovoit' : 'errorMessageCovoit' ?>">
        <?= htmlspecialchars($_SESSION['messageVoiture']); ?>
    </p>

    <?php unset($_SESSION['messageVoiture'], $_SESSION['voitureValide']); ?>
<?php endif; ?>

<!-- Messages d'erreur création compte employé -->
<?php if (!empty($messageCompte)) : ?>
    <p class="<?= ($compteValide  ?? false) ? 'successMessageCovoit' : 'errorMessageCovoit' ?>">
        <?= htmlspecialchars($messageCompte) ?>
    </p>
<?php endif; ?>

<!-- Messages d'erreur suspension compte -->
<?php if (!empty($messageSusp)): ?>
    <p class="<?= ($compteSusp ?? false) ? 'successMessageCovoit' : 'errorMessageCovoit' ?>">
        <?= htmlspecialchars($messageSusp) ?>
    </p>
<?php endif; ?>

<!-- Messages d'erreur ajout covoit -->
<?php if (!empty($messageTrajet)): ?>
    <p class="<?= ($trajetValide ?? false) ? 'successMessageCovoit' : 'errorMessageCovoit' ?>">
        <?= htmlspecialchars($messageTrajet) ?>
    </p>
<?php endif; ?>