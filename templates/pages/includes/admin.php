<section class="user-infos" id="credits">
    <section class="box-user-infos">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#267240" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tabler-icon tabler-icon-coins">
            <path d="M9 14c0 1.657 2.686 3 6 3s6 -1.343 6 -3s-2.686 -3 -6 -3s-6 1.343 -6 3"></path>
            <path d="M9 14v4c0 1.656 2.686 3 6 3s6 -1.344 6 -3v-4"></path>
            <path d="M3 6c0 1.072 1.144 2.062 3 2.598s4.144 .536 6 0c1.856 -.536 3 -1.526 3 -2.598c0 -1.072 -1.144 -2.062 -3 -2.598s-4.144 -.536 -6 0c-1.856 .536 -3 1.526 -3 2.598"></path>
            <path d="M3 6v10c0 .888 .772 1.45 2 2"></path>
            <path d="M3 11c0 .888 .772 1.45 2 2"></path>
        </svg>
        <h3><?= htmlspecialchars($graphiques['totalCredits']['totalCredits'] ?? 0) ?></h3>
        <p>Total crédits plateforme </p>
    </section>
    <section class="box-user-infos">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#267240" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-user">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
        </svg>
        <h3><?= htmlspecialchars($totalCompteActif['total']) ?></h3>
        <p>Comptes actifs </p>
    </section>
    <section class="box-user-infos">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#267240" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-user-x">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
            <path d="M6 21v-2a4 4 0 0 1 4 -4h3.5" />
            <path d="M22 22l-5 -5" />
            <path d="M17 22l5 -5" />
        </svg>
        <h3><?= htmlspecialchars($totalCompteSuspendu['total']) ?></h3>
        <p>Comptes suspendus</p>
    </section>
</section>
<section class="user-menu" id="user-avis">
    <section class="chauffeurLink">
        <button class="menu-btn active" type="button" data-tab="creerCompte">
            <span>Créer compte employé</span>
            <section class="souligne"></section>
        </button>
        <button class="menu-btn" type="button" data-tab="suspendreCompte">
            <span>Suspendre un compte</span>
            <section class="souligne"></section>
        </button>
    </section>
    <section class="compte-box">
        <section class="box content-tab" data-tab-content="creerCompte">
            <section class="compte">
                <form method="POST" class="compteForm" action="">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf); ?>">
                    <input type="hidden" name="formType" value="ajoutCompte">

                    <label>Pseudo : </label>
                    <section class="sectionCreationCompte modal-group">
                        <input type="text" name="pseudo" id="pseudo" value="<?= htmlspecialchars($pseudo ?? '') ?>" required>
                        <span class="iconModal"></span>
                        <span class="error">Le pseudo doit être inférieur à 10 caractères</span>
                    </section>

                    <label>Email : </label>
                    <section class="sectionCreationCompte modal-group">
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>
                        <span class="iconModal"></span>
                        <span class="error">Le mail n'est pas au bon format</span>
                    </section>

                    <label>Mot de passe : </label>
                    <section class="sectionCreationCompte modal-group">
                        <input type="password" name="password" id="password" required>
                        <span id="togglePassword" class="eye-icon"><img src="/assets/images/oeil-ouvert.png" class="oeil" alt="oeil ouvert"></span>
                    </section>

                    <section class="progression">
                        <section class="strength-meter">
                            <section id="strength-bar" class="strength-bar"></section>
                        </section>
                        <small id="strength-text"></small>
                    </section>

                    <label>Confirmer mot de passe : </label>
                    <section class="sectionCreationCompte modal-group">
                        <input type="password" name="password_confirm" id="password_confirm" required>
                        <span id="togglePasswordConfirm" class="eye-icon"><img src="/assets/images/oeil-ouvert.png" class="oeil" alt="oeil ouvert"></span>
                        <span class="error">Les mots de passe ne sont pas identiques</span>
                    </section>

                    <button class="button" id="btnCompte" type="submit">Créer</button>
                </form>
            </section>
        </section>

        <section class="box content-tab" data-tab-content="suspendreCompte">
            <section id="suspCompte">
                <section class="compte">
                    <form class="compteListe" action="" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf); ?>">
                        <select id="liste" name="compte" required>
                            <option value="" disabled selected hidden>Compte à suspendre</option>

                            <?php if (!empty($comptes)) : ?>
                                <?php foreach ($comptes as $c): ?>
                                    <option value="<?= htmlspecialchars($c->getUtilisateurId()) ?>">
                                        <?= htmlspecialchars(ucfirst($c->getPseudo() ?? 'N/A')) ?>
                                        - <?= htmlspecialchars(ucfirst($c->getEmail() ?? '')) ?>
                                        - <?= htmlspecialchars(ucfirst($c->getLibelle() ?? '')) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <button class="button" id="btnSupCompte" type="submit">Suspendre</button>
                    </form>
                </section>
            </section>
        </section>
    </section>
</section>