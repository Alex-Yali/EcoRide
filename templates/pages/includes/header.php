<header>
    <!-- Barre de navigation -->
    <nav class="nav-head">
        <section>
            <a title="Page d'accueil EcoRide" href="/">
                <img src="/assets/images/logo.png" class="logo" alt="logo du site">
            </a>
        </section>
        <section class="nav-links">
            <a title="Rechercher" href="/rechercher/" class="rechercher">
                <img src="/assets/images/loupe.png" class="loupe">
                <span>Covoiturage</span>
            </a>
            <!-- Image menu deroulant  -->
            <section title="Menu" id="menu">
                <img src="/assets/images/icon compte.png" id="menu-img" alt="menu déroulant">
                <!-- Menu deroulant  -->
                <section id="menu-box" class="hidden">
                    <?php if (!empty($idUtilisateur) && !empty($roleUtilisateur)): ?>
                        <?php foreach ($roleUtilisateur as $role): ?>
                            <?php if ($role === 'utilisateur'): ?>
                                <a title="Profil" href="/espace/" class="menu-link">
                                    <span>Profil</span>
                                    <img src="/assets/images/caret-vers-le-bas.png" class="caret-right">
                                </a>
                                <a title="Contact" href="/contact/" class="menu-link">
                                    <span>Contact</span>
                                    <img src="/assets/images/caret-vers-le-bas.png" class="caret-right">
                                </a>
                            <?php elseif ($role === 'employe' || $role === 'admin'): ?>
                                <a title="Profil" href="/espace/" class="menu-link">
                                    <span>Profil</span>
                                    <img src="/assets/images/caret-vers-le-bas.png" class="caret-right">
                                </a>
                            <?php endif; ?>
                            <a title="Deconnexion" href="/deconnexion/" class="menu-link">
                                <span>Se déconnecter</span>
                                <img src="/assets/images/caret-vers-le-bas.png" class="caret-right">
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <a title="Inscription" href="/inscription/" class="menu-link">
                            <span>Inscription</span>
                            <img src="/assets/images/caret-vers-le-bas.png" class="caret-right">
                        </a>
                        <a title="Connexion" href="/connexion/" class="menu-link">
                            <span>Connexion</span>
                            <img src="/assets/images/caret-vers-le-bas.png" class="caret-right">
                        </a>
                        <a title="Contact" href="/contact/" class="menu-link">
                            <span>Contact</span>
                            <img src="/assets/images/caret-vers-le-bas.png" class="caret-right">
                        </a>
                    <?php endif; ?>
                </section>
            </section>
        </section>
    </nav>
</header>