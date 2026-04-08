<section class="user-infos" id="credits">
    <section class="box-user-infos">
        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#267240" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tabler-icon tabler-icon-coins">
            <path d="M9 14c0 1.657 2.686 3 6 3s6 -1.343 6 -3s-2.686 -3 -6 -3s-6 1.343 -6 3"></path>
            <path d="M9 14v4c0 1.656 2.686 3 6 3s6 -1.344 6 -3v-4"></path>
            <path d="M3 6c0 1.072 1.144 2.062 3 2.598s4.144 .536 6 0c1.856 -.536 3 -1.526 3 -2.598c0 -1.072 -1.144 -2.062 -3 -2.598s-4.144 -.536 -6 0c-1.856 .536 -3 1.526 -3 2.598"></path>
            <path d="M3 6v10c0 .888 .772 1.45 2 2"></path>
            <path d="M3 11c0 .888 .772 1.45 2 2"></path>
        </svg>
        <h3><?= htmlspecialchars($infosUtilisateur->getCredits()) ?></h3>
        <p>Crédits </p>
    </section>
    <section class="box-user-infos">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#267240" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-user-check">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
            <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
            <path d="M15 19l2 2l4 -4" />
        </svg>
        <h3><?= htmlspecialchars($totalAvisInactif['total']) ?></h3>
        <p>Avis gérés</p>
    </section>
    <section class="box-user-infos">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#267240" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-user-star">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
            <path d="M6 21v-2a4 4 0 0 1 4 -4h.5" />
            <path d="M17.8 20.817l-2.172 1.138a.392 .392 0 0 1 -.568 -.41l.415 -2.411l-1.757 -1.707a.389 .389 0 0 1 .217 -.665l2.428 -.352l1.086 -2.193a.392 .392 0 0 1 .702 0l1.086 2.193l2.428 .352a.39 .39 0 0 1 .217 .665l-1.757 1.707l.414 2.41a.39 .39 0 0 1 -.567 .411l-2.172 -1.138" />
        </svg>
        <h3><?= htmlspecialchars($totalAvisActif['total']) ?></h3>
        <p>Avis actifs</p>
    </section>
</section>
<section class="user-menu" id="user-avis">
    <section class="chauffeurLink">
        <button class="menu-btn active" type="button" data-tab="avis">
            <span>Avis en cours (<?= htmlspecialchars($totalAvisActif['total']) ?>)</span>
            <section class="souligne"></section>
        </button>
        <button class="menu-btn" type="button" data-tab="historiqueAvis">
            <span>Historique avis (<?= htmlspecialchars($totalAvisInactif['total']) ?>)</span>
            <section class="souligne"></section>
        </button>
    </section>
    <section class="avis-box">
        <section class="box content-tab" data-tab-content="avis">
            <?php require APP_ROOT . "/templates/pages/includes/avisEnCours.php" ?>
        </section>
        <section class="box content-tab" data-tab-content="historiqueAvis">
            <?php require APP_ROOT . "/templates/pages/includes/historiqueAvis.php" ?>
        </section>
    </section>
</section>