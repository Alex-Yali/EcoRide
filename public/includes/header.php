<?php
require_once __DIR__ .  '/../../src/repository/infosUtilisateur.php';
?>

<header>
    <!-- Barre de navigation -->
    <nav class="nav-head">
        <section>
            <a title="Page d'accueil EcoRide" href="./index.php">
                <img src="./assets/images/logo.png" class="logo" alt="logo du site">
            </a>
        </section>
        <section class="nav-links">
            <a title="Rechercher" href="./rechercher.php" class="rechercher">
                <img src="./assets/images/loupe.png" class="loupe">
                <span>Covoiturage</span>
            </a>
            <!-- Image menu deroulant  -->
            <section title="Menu" id="menu">
                <img src="./assets/images/icon compte.png" id="menu-img" alt="menu déroulant">
                <!-- Menu deroulant  -->
                <section id="menu-box" class="hidden">

                    <?php if (!empty($idUtilisateur)): ?>
                        <?php if ($roleUtilisateur === 'utilisateur'): ?>
                            <a title="Profil" href="./espace.php" class="menu-link">
                                <span>Profil</span>
                                <img src="./assets/images/caret-vers-le-bas.png" class="caret-right">
                            </a>
                            <a title="Contact" href="./contact.php" class="menu-link">
                                <span>Contact</span>
                                <img src="./assets/images/caret-vers-le-bas.png" class="caret-right">
                            </a>
                        <?php elseif ($roleUtilisateur === 'employe' || 'admin'): ?>
                            <a title="Profil" href="./espace.php" class="menu-link">
                                <span>Profil</span>
                                <img src="./assets/images/caret-vers-le-bas.png" class="caret-right">
                            </a>
                        <?php endif; ?>
                        <a title="Deconnexion" href="../src/controller/deconnexion.php" class="menu-link">
                            <span>Se déconnecter</span>
                            <img src="./assets/images/caret-vers-le-bas.png" class="caret-right">
                        </a>
                    <?php else: ?>
                        <a title="Inscription" href="./inscription.php" class="menu-link">
                            <span>Inscription</span>
                            <img src="./assets/images/caret-vers-le-bas.png" class="caret-right">
                        </a>
                        <a title="Connexion" href="./connexion.php" class="menu-link">
                            <span>Connexion</span>
                            <img src="./assets/images/caret-vers-le-bas.png" class="caret-right">
                        </a>
                        <a title="Contact" href="./contact.php" class="menu-link">
                            <span>Contact</span>
                            <img src="./assets/images/caret-vers-le-bas.png" class="caret-right">
                        </a>
                    <?php endif; ?>

                </section>
            </section>
        </section>
    </nav>
</header>