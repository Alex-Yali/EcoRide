<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Mon espace</title>

    <!-- Styles -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/pages/espace.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wdth,wght@0,75..100,700;1,75..100,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <!-- Header -->
    <?php require APP_ROOT . "/templates/pages/includes/header.php" ?>

    <main>
        <section class="box-espace">
            <section class="box-user">
                <section class="box-titre">
                    <h1 class="titre">Bonjour, <?= htmlspecialchars($infosUtilisateur->getPseudo()) ?></h1>
                    <p class="sous-titre">Bienvenue sur votre espace EcoRide</p>
                </section>
                <!-- Section Radio Utilisateur -->
                <?php foreach ($roleUtilisateur as $role): ?>
                    <?php if ($role === 'utilisateur'): ?>
                        <section class="user-box">
                            <form id="user-type" action="/espace/" method="POST">

                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf); ?>">
                                <input type="hidden" name="formType" value="switchRadio">

                                <fieldset>
                                    <legend>Je suis :</legend>
                                    <label><input type="radio" name="user-statut" value="passager" <?= $radio === 'passager' ? 'checked' : '' ?>> Passager</label>
                                    <label><input type="radio" name="user-statut" value="chauffeur" <?= $radio === 'chauffeur' ? 'checked' : '' ?>> Chauffeur</label>
                                    <label><input type="radio" name="user-statut" value="lesDeux" <?= $radio === 'lesDeux' ? 'checked' : '' ?>> Les deux</label>
                                </fieldset>
                                <button id="btnRole" class="button" type="submit">Valider</button>
                            </form>
                        </section>
                        <?php if (($radio === 'chauffeur' || $radio === 'lesDeux') && $voitureExiste): ?>
                            <a class="button" id="btn-trajet" href="/trajet/"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tabler-icon tabler-icon-plus ">
                                    <path d="M12 5l0 14"></path>
                                    <path d="M5 12l14 0"></path>
                                </svg> Saisir un voyage</a>
                        <?php endif ?>
            </section>
            <!-- Message de succès / erreur -->
            <?php require APP_ROOT . "/src/Service/MessagesErreur.php" ?>

            <section class="user-infos" id="credits">
                <section class="box-user-infos">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#267240" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tabler-icon tabler-icon-coins ">
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#267240" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tabler-icon tabler-icon-car ">
                        <path d="M5 17a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                        <path d="M15 17a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                        <path d="M5 17h-2v-6l2 -5h9l4 5h1a2 2 0 0 1 2 2v4h-2m-4 0h-6m-6 -6h15m-6 0v-5"></path>
                    </svg>
                    <h3><?= htmlspecialchars($totalTrajetUtilisateur['total']) ?></h3>
                    <p>Trajets proposés</p>
                </section>
                <section class="box-user-infos">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#267240" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tabler-icon tabler-icon-calendar ">
                        <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12"></path>
                        <path d="M16 3v4"></path>
                        <path d="M8 3v4"></path>
                        <path d="M4 11h16"></path>
                        <path d="M11 15h1"></path>
                        <path d="M12 15v3"></path>
                    </svg>
                    <h3><?= htmlspecialchars($totalCovoitUtilisateur['total']) ?></h3>
                    <p>Réservations actives</p>
                </section>

            </section>
            <!-- Section Chauffeur -->
            <?php if ($radio === 'chauffeur' || $radio === 'lesDeux'): ?>
                <?php if (!$voitureExiste): ?>
                    <section id="chauffeur-info">
                        <h2>Informations chauffeur</h2>
                        <form action="/espace/" method="POST" id="form">

                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf); ?>">
                            <input type="hidden" name="formType" value="ajoutVoiture">

                            <section class="voitureInfo">
                                <label>Plaque d’immatriculation :
                                    <input type="text" id="immat" name="immatriculation" required>
                                </label>
                                <label>Date de 1ère immatriculation :
                                    <input type="text" id="dateImmat" name="dateImmat" required>
                                </label>
                                <label>Marque :
                                    <input type="text" id="marque" name="marque" required>
                                </label>
                                <label>Modèle :
                                    <input type="text" id="modele" name="modele" required>
                                </label>
                                <label>Couleur :
                                    <input type="text" id="couleur" name="couleur" required>
                                </label>
                                <label>Places dispo :
                                    <input type="number" id="place" name="place" min="1" max="4" required>
                                </label>
                                <section class="energie">
                                    <label for="energie">Énergie utilisée :</label>
                                    <input list="typeEnergie" id="energie" name="energie" placeholder="Choisir énergie" required>
                                    <datalist id="typeEnergie">
                                        <option value="Essence">
                                        <option value="Diesel">
                                        <option value="Électrique">
                                    </datalist>
                                </section>
                            </section>

                            <section class="separateurFiltres"></section>
                            <section class="pref">
                                <h2>Préférences :</h2>
                                <section class="user-pref">
                                    <fieldset>
                                        <legend>Tabac</legend>
                                        <label><input type="radio" name="tabac" value="Fumeur" checked> Fumeur</label>
                                        <label><input type="radio" name="tabac" value="Non fumeur"> Non fumeur</label>
                                    </fieldset>
                                    <fieldset>
                                        <legend>Animal</legend>
                                        <label><input type="radio" name="animal" value="Animaux acceptés" checked> Autorisé</label>
                                        <label><input type="radio" name="animal" value="Animaux refusés"> Non autorisé</label>
                                    </fieldset>
                                    <label id="ajoutPref">Autre préférence :
                                        <input type="text" name="ajoutPref" placeholder="Ex: Musique, silence...">
                                    </label>
                                </section>
                                <button id="btnInfo" class="button" type="submit">Enregistrer</button>
                            </section>
                        </form>
                    </section>
                <?php else: ?>
                    <!-- Profil Chauffeur -->
                    <section class="user-menu">
                        <nav class="chauffeurLink">
                            <ul>
                                <li><a href="/mesCovoiturages/">Covoiturages en cours (<?= htmlspecialchars($totalCovoitActif['total']) ?>)</a></li>
                                <li><a href="/historique/">Historique covoiturages (<?= htmlspecialchars($totalCovoitInactif['total']) ?>)</a></li>
                                <li><a href="/vehicule/">Mes véhicules (<?= htmlspecialchars($totalVoitureUtilisateur['total']) ?>)</a></li>
                            </ul>
                        </nav>
                    </section>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($radio === 'passager'): ?>
                <!-- Profil Passager -->
                <section class="user-menu">
                    <nav class="passagerLink">
                        <ul>
                            <li><a href="/mesCovoiturages/">Covoiturages en cours (<?= htmlspecialchars($totalCovoitActif['total']) ?>)</a></li>
                            <li><a href="/historique/">Historique covoiturages (<?= htmlspecialchars($totalCovoitInactif['total']) ?>)</a></li>
                        </ul>
                    </nav>
                </section>
            <?php endif ?>
        </section>

        <!-- Section Employe -->
    <?php elseif ($role === 'employe'): ?>
        <section class="user-menu">
            <nav class="passagerLink">
                <ul>
                    <li><a href="/avisEnCours/">Avis en cours (<?= htmlspecialchars($totalAvisActif['total']) ?>)</a></li>
                    <li><a href="/historiqueAvis/">Historique avis (<?= htmlspecialchars($totalAvisInactif['total']) ?>)</a></li>
                </ul>
            </nav>
        </section>

        <!-- Section Admin -->
    <?php elseif ($role === 'admin'): ?>
        <section class="user-menu">
            <nav class="passagerLink">
                <ul>
                    <li><button class="modal-open-btn" id="openAjoutModal">Créer compte employé</button></li>
                    <li><button class="modal-open-btn" id="openSuspModal">Suspendre un compte</button></li>
                </ul>
            </nav>
        </section>

        <p id="total-credit">Total des crédits gagné par la plateforme : <?= htmlspecialchars($graphiques['totalCredits']['totalCredits'] ?? 0) ?></p>


        <!-- Graphiques -->
        <section class="graphique">
            <canvas id="graphique1"></canvas>
            <canvas id="graphique2"></canvas>
        </section>

        <!-- Modal création compte employé -->
        <section id="ajoutCompte" class="modal">
            <section class="compte">
                <button class="close" data-modal="ajoutCompte">x</button>
                <h2>Création compte employé</h2>

                <form method="POST" class="modal-content" action="">

                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf); ?>">
                    <input type="hidden" name="formType" value="ajoutCompte">

                    <section class="modal-group">
                        <label>Pseudo : </label>
                        <input type="text" name="pseudo" id="pseudo" value="<?= htmlspecialchars($pseudo ?? '') ?>" required>
                        <span class="iconModal"></span>
                        <span class="error">Le pseudo doit être inférieur à 10 caractères</span>
                    </section>

                    <section class="modal-group">
                        <label>Email : </label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>
                        <span class="iconModal"></span>
                        <span class="error">Le mail n'est pas au bon format</span>
                    </section>

                    <section class="modal-group">
                        <label>Mot de passe : </label>
                        <input type="password" name="password" id="password" required>
                        <span id="togglePassword" class="eye-icon"><img src="/assets/images/oeil-ouvert.png" class="oeil" alt="oeil ouvert"></span>
                    </section>

                    <section class="progression">
                        <section class="strength-meter">
                            <section id="strength-bar" class="strength-bar"></section>
                        </section>
                        <small id="strength-text"></small>
                    </section>

                    <section class="modal-group">
                        <label>Confirmer mot de passe : </label>
                        <input type="password" name="password_confirm" id="password_confirm" required>
                        <span id="togglePasswordConfirm" class="eye-icon"><img src="/assets/images/oeil-ouvert.png" class="oeil" alt="oeil ouvert"></span>
                        <span class="error">Les mots de passe ne sont pas identiques</span>
                    </section>

                    <button class="button" id="btnCompte" type="submit">Créer</button>
                </form>
            </section>
        </section>

        <!-- Modal suppression compte -->
        <section id="suspCompte" class="modal">
            <section class="compte">
                <button class="close" data-modal="suspCompte">x</button>
                <h2>Suspension compte</h2>

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
    <?php endif; ?>
<?php endforeach; ?>
    </main>

    <!-- Footer -->
    <?php require APP_ROOT . "/templates/pages/includes/footer.php" ?>

    <!-- Message bienvenue -->
    <?php if (!empty($_SESSION['inscription_ok'])): ?>
        <script>
            alert("Bienvenue ! Vous disposez dès maintenant de 20 crédits.");
        </script>
    <?php unset($_SESSION['inscription_ok']);
    endif; ?>

    <!-- JS -->
    <script src="/assets/js/main.js" type="module"></script>
    <script src="/assets/js/pages/espace.js" type="module"></script>

    <!-- Script graphique -->
    <script>
        // Graphique 1
        const date = <?php echo json_encode($graphiques['graph1']['dates']); ?>;
        const total = <?php echo json_encode($graphiques['graph1']['total']); ?>;

        // Convertir les dates en format JJ/MM/AAAA
        const dateFormatees = date.map(d => {
            const obj = new Date(d);
            return obj.toLocaleDateString('fr-FR');
        });

        new Chart(document.getElementById("graphique1"), {
            type: 'bar',
            data: {
                labels: dateFormatees,
                datasets: [{
                    label: "Nombre de covoiturages par date",
                    data: total,
                    borderWidth: 2,
                    categoryPercentage: 0.7, // espace pris par les barres dans la catégorie
                    barPercentage: 0.9 // largeur interne des barres
                }]
            },
            options: {
                responsive: false,
                scales: {
                    y: {
                        beginAtZero: true, // commence l'axe Y à zéro
                        ticks: {
                            stepSize: 1, // Valeurs tous les 1
                            precision: 0 //  Pas de décimales
                        },
                    }
                }
            }
        });

        // Graphique 2
        const date2 = <?php echo json_encode($graphiques['graph2']['dates']); ?>;
        const totalCredit = <?php echo json_encode($graphiques['graph2']['totalCredits']); ?>;

        // Convertir les dates en format JJ/MM/AAAA
        const dateFormatees2 = date2.map(d => {
            const obj = new Date(d);
            return obj.toLocaleDateString('fr-FR');
        });

        new Chart(document.getElementById("graphique2"), {
            type: 'bar',
            data: {
                labels: dateFormatees2,
                datasets: [{
                    label: "Nombre de crédit par jours",
                    data: totalCredit,
                    borderWidth: 2,
                    categoryPercentage: 0.7, // espace pris par les barres dans la catégorie
                    barPercentage: 0.9 // largeur interne des barres
                }]
            },
            options: {
                responsive: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 2,
                            precision: 0
                        },
                    }
                }
            }
        });
    </script>
</body>

</html>