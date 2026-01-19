<?php
require_once __DIR__ . '/../src/service/init.php';
require '../src/repository/infosUtilisateur.php';
require '../src/service/switchPassagerChauffeur.php';
require '../src/repository/ajoutCompte.php';
require '../src/repository/graphique.php';
require '../src/repository/supCompte.php';
require '../src/service/csrf.php';
require '../src/repository/ajoutVoiture.php';
$csrf = generate_csrf_token();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Mon espace</title>

    <!-- Styles -->
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/pages/espace.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wdth,wght@0,75..100,700;1,75..100,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <!-- Header -->
    <?php require 'includes/header.php'; ?>

    <main>
        <h1 class="gros-titre">Mon espace :</h1>

        <!-- Section Utilisateur -->
        <?php if ($roleUtilisateur === 'utilisateur'): ?>

            <!-- Message de succès / erreur -->
            <?php if (!empty($messageVoiture)): ?>
                <p style="color: <?= ($voitureValide ?? false) ? 'green' : 'red' ?>; text-align:center;">
                    <?= htmlspecialchars($messageVoiture) ?>
                </p>
            <?php endif; ?>

            <section class="user-box">
                <form id="user-type" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf); ?>">
                    <fieldset>
                        <legend>Je suis :</legend>
                        <label><input type="radio" name="user-role" value="passager" <?php if ($radio === 'passager') echo 'checked'; ?>> Passager</label>
                        <label><input type="radio" name="user-role" value="chauffeur" <?php if ($radio === 'chauffeur') echo 'checked'; ?>> Chauffeur</label>
                        <label><input type="radio" name="user-role" value="lesDeux" <?php if ($radio === 'lesDeux') echo 'checked'; ?>> Les deux</label>
                    </fieldset>
                    <button id="btnRole" class="button" type="submit">Valider</button>
                </form>
            </section>

            <!-- Section Passager -->
            <?php if ($radio === 'passager'): ?>
                <section id="user-profil">
                    <section class="user-menu">
                        <section class="user-id">
                            <section class="user-name">
                                <img src="./assets/images/compte noir.png" alt="image compte noir">
                                <span class="pseudo"><?= htmlspecialchars($pseudoUtilisateur) ?></span>
                            </section>
                            <section class="user-info">
                                <img src="./assets/images/pile-de-pieces.png" alt="image pièces">
                                <span>Crédits restants : <?= htmlspecialchars($creditsUtilisateur) ?></span>
                            </section>
                        </section>
                        <nav class="passagerLink">
                            <ul>
                                <li><a href="./mesCovoiturages.php">Covoiturages en cours</a></li>
                                <li><a href="./historique.php">Historique covoiturages</a></li>
                            </ul>
                        </nav>
                    </section>
                </section>

                <!-- Section Chauffeur -->
            <?php elseif ($radio === 'chauffeur' || $radio === 'lesDeux'): ?>
                <?php if (!$voitureExiste): ?>
                    <section id="chauffeur-info">
                        <h2>Informations chauffeur</h2>
                        <form action="espace.php" method="POST" id="form">

                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf); ?>">
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
                                    <input type="number" id="place" name="place" min="1" required>
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
                                <input type="hidden" name="formType" value="ajoutVoiture">
                                <button id="btnInfo" class="button" type="submit">Enregistrer</button>
                            </section>
                        </form>
                    </section>
                <?php else: ?>

                    <!-- Profil Chauffeur -->
                    <section id="chauffeur-profil">
                        <section class="user-menu">
                            <section class="user-id">
                                <section class="user-name">
                                    <img src="./assets/images/compte noir.png" alt="image compte noir">
                                    <span class="pseudo"><?= htmlspecialchars($pseudoUtilisateur) ?></span>
                                </section>
                                <section class="user-info">
                                    <img src="./assets/images/pile-de-pieces.png" alt="image pièces">
                                    <span>Crédits restants : <?= htmlspecialchars($creditsUtilisateur) ?></span>
                                </section>
                            </section>
                            <nav class="chauffeurLink">
                                <ul>
                                    <li><a href="./trajet.php">Saisir un voyage</a></li>
                                    <li><a href="./mesCovoiturages.php">Covoiturages en cours</a></li>
                                    <li><a href="./historique.php">Historique covoiturages</a></li>
                                    <li><a href="./vehicule.php">Mes véhicules</a></li>
                                </ul>
                            </nav>
                        </section>
                    </section>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Section Employe -->
        <?php elseif ($roleUtilisateur === 'employe'): ?>
            <section class="user-menu">
                <section class="user-id">
                    <section class="user-name">
                        <img src="./assets/images/compte noir.png" alt="image compte noir">
                        <span class="pseudo"><?= htmlspecialchars($pseudoUtilisateur) ?></span>
                    </section>
                </section>
                <nav class="passagerLink">
                    <ul>
                        <li><a href="./avisEnCours.php">Avis en cours</a></li>
                        <li><a href="./historiqueAvis.php">Historique avis</a></li>
                    </ul>
                </nav>
            </section>

            <!-- Section Admin -->
        <?php elseif ($roleUtilisateur === 'admin'): ?>
            <section class="user-menu">
                <section class="user-id">
                    <section class="user-name">
                        <img src="./assets/images/compte noir.png" alt="image compte noir">
                        <span class="pseudo"><?= htmlspecialchars($pseudoUtilisateur) ?></span>
                    </section>
                </section>
                <nav class="passagerLink">
                    <ul>
                        <li><a href="#modal">Créer compte employé</a></li>
                        <li><a href="#supCompte">Suspendre un compte</a></li>
                    </ul>
                </nav>
            </section>

            <p id="credit">Total des crédits gagné par la plateforme : <?= htmlspecialchars($totalCredits['totalCredits']) ?></p>

            <!-- Graphiques -->
            <section class="graphique">
                <canvas id="graphique1"></canvas>
                <canvas id="graphique2"></canvas>
            </section>

            <!-- Modal création compte employé -->
            <section id="modal" class="modal">
                <section class="compte">
                    <a href="#" class="close">x</a>
                    <h2>Création compte employé</h2>

                    <!-- Messages d'erreur ajout compte -->
                    <?php if (!empty($messageCompte)): ?>
                        <p style="color: <?= ($compteValide ?? false) ? 'green' : 'red' ?>; text-align:center; margin:0;">
                            <?= htmlspecialchars($messageCompte) ?>
                        </p>
                    <?php endif; ?>


                    <form method="POST" class="modal-content">

                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf); ?>">

                        <label>Email :
                            <input type="email" name="email" required>
                        </label>

                        <label>Mot de passe :
                            <input type="password" name="password" required>
                        </label>

                        <label>Pseudo :
                            <input type="text" name="pseudo" required>
                        </label>

                        <label>Crédits :
                            <input type="number" name="credits" min="1" required>
                        </label>

                        <input type="hidden" name="formType" value="ajoutCompte">

                        <button class="button" id="btnCompte" type="submit">Créer</button>

                    </form>
                </section>
            </section>

            <!-- Modal suppression compte -->
            <section id="supCompte" class="modal">
                <section class="compte">
                    <a href="#" class="close">x</a>
                    <h2>Suspension compte</h2>

                    <!-- Messages compte -->
                    <?php if (!empty($messageSup)): ?>
                        <p style="color: <?= ($compteSup ?? false) ? 'green' : 'red' ?>; text-align:center; margin:0;">
                            <?= htmlspecialchars($messageSup) ?>
                        </p>
                    <?php endif; ?>
                    <form class="compteListe" action="" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf); ?>">
                        <select id="liste" name="compte" required>
                            <option value="" disabled selected hidden>Compte à suspendre</option>

                            <?php if (!empty($compte)) : ?>
                                <?php foreach ($compte as $c): ?>
                                    <option value="<?= htmlspecialchars($c['utilisateur_id']) ?>">
                                        <?= htmlspecialchars(ucfirst($c['pseudo'] ?? 'N/A')) ?>
                                        - <?= htmlspecialchars(ucfirst($c['email'] ?? '')) ?>
                                        - <?= htmlspecialchars(ucfirst($c['libelle'] ?? '')) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <button class="button" id="btnSupCompte" type="submit">Suspendre</button>
                    </form>
                </section>
            </section>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <?php require 'includes/footer.php'; ?>

    <!-- Message bienvenue -->
    <?php if (!empty($_SESSION['inscription_ok'])): ?>
        <script>
            alert("Bienvenue ! Vous disposez dès maintenant de 20 crédits.");
        </script>
    <?php unset($_SESSION['inscription_ok']);
    endif; ?>

    <!-- JS -->
    <script src="./assets/js/main.js" type="module"></script>
    <!-- Script graphique -->
    <script>
        // Graphique 1
        const date = <?php echo json_encode($date); ?>;
        const total = <?php echo json_encode($total); ?>;

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
        const date2 = <?php echo json_encode($date2); ?>;
        const totalCredit = <?php echo json_encode($totalCredit); ?>;

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