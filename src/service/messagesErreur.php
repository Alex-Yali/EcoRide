<!-- Affichage du message d'erreur -->

<?php if (!empty($message)) : ?>
    <p style="color:red; font-size:0.8rem; margin:0; text-align:center;padding-top:1rem;">
        <?= htmlspecialchars($message) ?>
    </p>
<?php endif; ?>