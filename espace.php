<?php
require 'back/infosUtilisateur.php';
require 'back/switchPassagerChauffeur.php';

// Exécuter le traitement d'ajout du véhicule uniquement si le formulaire est envoyé
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['formType'] ?? '') === 'ajoutVoiture') {
    require 'back/ajoutVoiture.php';
}
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
</head>
<body>
    <!-- Header -->
    <?php require 'includes/header.php'; ?>

    <main>
        <h1 class="gros-titre">Mon espace :</h1>

        <!-- Message de succès / erreur -->
        <?php if (!empty($message)): ?>
            <p style="color: <?= ($voitureValide ?? false) ? 'green' : 'red' ?>; text-align:center;">
                <?= htmlspecialchars($message) ?>
            </p>
        <?php endif; ?>

        <section class="user-box">
            <form id="user-type" method="POST">
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
                            <span id="first-name"><?= htmlspecialchars($pseudoUtilisateur) ?></span>
                        </section>
                        <section class="user-info">
                            <img src="./assets/images/pile-de-pieces.png" alt="image pièces">
                            <span>Crédits restants : <?= htmlspecialchars($creditsUtilisateur) ?></span>
                        </section>
                    </section>
                    <nav class="passagerLink">
                        <ul>
                            <li><a href="./mesCovoiturages.php" target="_blank">Covoiturages en cours</a></li>
                            <li><a href="./historique.php" target="_blank">Historique covoiturages</a></li>
                        </ul>
                    </nav>
                </section>
            </section>

        <!-- Section Chauffeur -->
        <?php elseif ($radio === 'chauffeur'): ?>
            <?php if (!isset($_SESSION['form_submitted'])): ?>
                <section id="chauffeur-info">
                    <h2>Informations chauffeur</h2>
                    <form action="espace.php" method="POST">
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
                                <span id="first-name"><?= htmlspecialchars($pseudoUtilisateur) ?></span>
                            </section>
                            <section class="user-info">
                                <img src="./assets/images/pile-de-pieces.png" alt="image pièces">
                                <span>Crédits restants : <?= htmlspecialchars($creditsUtilisateur) ?></span>
                            </section>
                        </section>
                        <nav class="chauffeurLink">
                            <ul>
                                <li><a href="./trajet.php">Saisir un voyage</a></li>
                                <li><a href="./mesCovoiturages.php" target="_blank">Covoiturages en cours</a></li>
                                <li><a href="./historique.php" target="_blank">Historique covoiturages</a></li>
                                <li><a href="./vehicule.php" target="_blank">Mes véhicules</a></li>
                            </ul>
                        </nav>
                    </section>
                </section>
            <?php endif; ?>

        <!-- Section Les deux -->
        <?php elseif ($radio === 'lesDeux'): ?>
            <?php if (!isset($_SESSION['form_submitted'])): ?>
                <section id="chauffeur-info">
                    <h2>Informations chauffeur</h2>
                    <form action="espace.php" method="POST">
                        <!-- même formulaire que ci-dessus -->
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
                <!-- Profil chauffeur pour 'lesDeux' -->
                <section id="chauffeur-profil">
                    <section class="user-menu">
                        <section class="user-id">
                            <section class="user-name">
                                <img src="./assets/images/compte noir.png" alt="image compte noir">
                                <span id="first-name"><?= htmlspecialchars($pseudoUtilisateur) ?></span>
                            </section>
                            <section class="user-info">
                                <img src="./assets/images/pile-de-pieces.png" alt="image pièces">
                                <span>Crédits restants : <?= htmlspecialchars($creditsUtilisateur) ?></span>
                            </section>
                        </section>
                        <nav class="chauffeurLink">
                            <ul>
                                <li><a href="./trajet.php">Saisir un voyage</a></li>
                                <li><a href="./mesCovoiturages.php" target="_blank">Covoiturages en cours</a></li>
                                <li><a href="./historique.php" target="_blank">Historique covoiturages</a></li>
                                <li><a href="./vehicule.php" target="_blank">Mes véhicules</a></li>
                            </ul>
                        </nav>
                    </section>
                </section>
            <?php endif; ?>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <?php require 'includes/footer.php'; ?>

    <!-- JS -->
    <script src="./assets/js/main.js" type="module"></script>
</body>
</html>