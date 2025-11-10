    <?php
    require 'back/infosUtilisateur.php';
    require 'back/ajoutVoiture.php';
    require 'back/switchPassagerChauffeur.php';
    ?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Mon espace</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/pages/espace.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wdth,wght@0,75..100,700;1,75..100,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <?php
    require 'includes/header.php'
    ?>
    <main>
        <h1 class="gros-titre">Mon espace :</h1>
        <!-- Messages d'erreur -->
        <?php require 'back/messagesErreur.php'; ?> 
        <section class="user-box">
            <form id="user-type" method="POST">
                <fieldset>
                    <legend>Je suis :</legend>
                    <label><input type="radio" name="user-role" value="passager" <?PHP if ($radio === 'passager') echo 'checked'; ?>>Passager</label>
                    <label><input type="radio" name="user-role" value="chauffeur" <?PHP if ($radio === 'chauffeur') echo 'checked'; ?>>Chauffeur</label>
                    <label><input type="radio" name="user-role" value="lesDeux" <?PHP if ($radio === 'lesDeux') echo 'checked'; ?>>Les deux </label>
                </fieldset>
                <button id="btnRole" class="button" type="submit">Valider</button>
            </form>
        </section>
        <!-- Section passager -->
        <?PHP if ($radio === 'passager') :?>
        <section id="user-profil">
            <section class="user-menu">
                <section class="user-id">
                    <section class="user-name">
                        <img src="./assets/images/compte noir.png" alt="image compte noir">
                        <span id="first-name"><?= htmlspecialchars ($displayPseudo) ?></span>
                    </section>
                    <section class="user-info">
                        <img src="./assets/images/pile-de-pieces.png" alt="image pieces noir">
                        <span>Crédits restants : <?= htmlspecialchars ($displayCredits) ?></span>
                    </section>
                </section>
                    <nav class="passagerLink">
                        <ul>
                            <li><a href="./mesCovoiturages.php" target="_blank">Covoiturages en cours</a>
                                <img src="./assets/images/caret-vers-le-bas.png" id="caret-right" alt="image caret vers la droite">
                            </li>
                            <li><a href="#">Historique covoiturages</a>
                                <img src="./assets/images/caret-vers-le-bas.png" id="caret-right" alt="image caret vers la droite">
                            </li>
                        </ul>
                    </nav>
            </section>
        </section>
        <!-- Section chauffeur -->
        <?PHP elseif ($radio === 'chauffeur') :?>
            <?php if (!isset($voitureValide) || !$voitureValide): ?>
                <section id="chauffeur-info">
                <h2>Informations chauffeur</h2>
                    <form action="espace.php" method="POST">
                        <label>Plaque d’immatriculation :<input type="text" id="immat" name="immatriculation"></label>
                        <label>Date de 1ère immatriculation :<input type="text" id="dateImmat" name="dateImmat"></label>
                        <label>Marque :<input type="text" id="marque" name="marque"></label>
                        <label>Modèle :<input type="text" id="modele" name="modele"></label>
                        <label>Couleur :<input type="text" id="couleur" name="couleur"></label>
                        <label>Place dispo :<input type="number" id="place" name="place"></label>
                        <section class="energie">
                            <label for="energie">Energier utilisée :</label>
                            <input list="typeEnergie" id="energie" name="energie" placeholder="Choisir énergie">
                                <datalist id="typeEnergie">
                                    <option value="Essence">
                                    <option value="Diesel">
                                    <option value="Electrique">
                                </datalist>
                        </section>
                        <section class="separateurFiltres"></section>
                        <section class="pref">
                            <h2>Préférences :</h2>
                            <section class="user-pref">
                                <fieldset>
                                    <legend>Tabac</legend>
                                    <label><input type="radio" name="tabac" value="Fumeur" checked>Fumeur</label>
                                    <label><input type="radio" name="tabac" value="Non fumeur">Non fumeur</label>
                                </fieldset>
                                <fieldset>
                                    <legend>Animal</legend>
                                    <label><input type="radio" name="animal" value="Animaux acceptés" checked>Autorisé</label>
                                    <label><input type="radio" name="animal" value="Animaux refusés">Non autorisé</label>
                                </fieldset>
                                <label id="ajoutPref">Ajouter :<input type="text" name="ajoutPref"></label>
                            </section>
                            <input type="hidden" name="formType" value="ajoutVoiture"> <!-- input masqué pour dif les deux form  -->
                            <button id="btnInfo" class="button" type="submit">Enregistrer</button>
                        </section>
                    </form>
                </section>
            <?php else: ?>
                <section id="chauffeur-profil">
                    <section class="user-menu">
                        <section class="user-id">
                            <section class="user-name">
                                <img src="./assets/images/compte noir.png" alt="image compte noir">
                                <span id="first-name"><?= htmlspecialchars ($displayPseudo) ?></span>
                            </section>
                            <section class="user-info">
                                <img src="./assets/images/pile-de-pieces.png" alt="image pieces noir">
                                <span>Crédits restants : <?= htmlspecialchars ($displayCredits) ?></span>
                            </section>
                        </section>
                            <nav class="chauffeurLink">
                                <ul>
                                    <li><a href="./trajet.php">Saisir un voyage</a>
                                        <img src="./assets/images/caret-vers-le-bas.png" id="caret-right" alt="image caret vers la droite">
                                    </li>
                                    <li><a href="./mesCovoiturages.php" target="_blank">Covoiturages en cours</a>
                                        <img src="./assets/images/caret-vers-le-bas.png" id="caret-right" alt="image caret vers la droite">
                                    </li>
                                    <li><a href="#">Historique covoiturages</a>
                                        <img src="./assets/images/caret-vers-le-bas.png" id="caret-right" alt="image caret vers la droite">
                                    </li>
                                    <li><a href="./vehicule.php" target="_blank">Mes véhicules</a>
                                        <img src="./assets/images/caret-vers-le-bas.png" id="caret-right" alt="image caret vers la droite">
                                    </li>
                                </ul>
                            </nav>
                    </section>
                </section>
        <?php endif; ?>
        <!-- Section passager / chauffeur -->
        <?PHP elseif ($radio === 'lesDeux') :?>
            <?php if (!isset($voitureValide) || !$voitureValide): ?>
                <section id="chauffeur-info">
                    <h2>Informations chauffeur</h2>
                        <form action="" method="POST">
                            <label>Plaque d’immatriculation :<input type="text" id="immat" name="immatriculation"></label>
                            <label>Date de 1ère immatriculation :<input type="text" id="dateImmat" name="dateImmat"></label>
                            <label>Marque :<input type="text" id="marque" name="marque"></label>
                            <label>Modèle :<input type="text" id="modele" name="modele"></label>
                            <label>Couleur :<input type="text" id="couleur" name="couleur"></label>
                            <label>Place dispo :<input type="number" id="place" name="place"></label>
                            <section class="energie">
                                <label for="energie">Energier utilisée :</label>
                                <input list="typeEnergie" id="energie" name="energie" placeholder="Choisir énergie">
                                    <datalist id="typeEnergie">
                                        <option value="Essence">
                                        <option value="Diesel">
                                        <option value="Electrique">
                                    </datalist>
                            </section>
                            <section class="separateurFiltres"></section>
                            <section class="pref">
                                <h2>Préférences :</h2>
                                <section class="user-pref">
                                <fieldset>
                                    <legend>Tabac</legend>
                                    <label><input type="radio" name="tabac" value="Fumeur" checked>Fumeur</label>
                                    <label><input type="radio" name="tabac" value="Non fumeur">Non fumeur</label>
                                </fieldset>
                                <fieldset>
                                    <legend>Animal</legend>
                                    <label><input type="radio" name="animal" value="Animaux acceptés" checked>Autorisé</label>
                                    <label><input type="radio" name="animal" value="Animaux refusés">Non autorisé</label>
                                </fieldset>
                                    <label id="ajoutPref">Ajouter :<input type="text" name="ajoutPref"></label>
                                </section>
                                <input type="hidden" name="formType" value="ajoutVoiture"> <!-- input masqué pour dif les deux form  -->
                                <button id="btnInfo" class="button" type="submit">Enregistrer</button>
                            </section>
                        </form>
                    </section>
                <?php else: ?>
                    <section id="chauffeur-profil">
                        <section class="user-menu">
                            <section class="user-id">
                                <section class="user-name">
                                    <img src="./assets/images/compte noir.png" alt="image compte noir">
                                    <span id="first-name"><?= htmlspecialchars ($displayPseudo) ?></span>
                                </section>
                                <section class="user-info">
                                    <img src="./assets/images/pile-de-pieces.png" alt="image pieces noir">
                                    <span>Crédits restants : <?= htmlspecialchars ($displayCredits) ?></span>
                                </section>
                            </section>
                                <nav class="chauffeurLink">
                                    <ul>
                                        <li><a href="./trajet.php">Saisir un voyage</a>
                                            <img src="./assets/images/caret-vers-le-bas.png" id="caret-right" alt="image caret vers la droite">
                                        </li>
                                        <li><a href="./mesCovoiturages.php" target="_blank">Covoiturages en cours</a>
                                            <img src="./assets/images/caret-vers-le-bas.png" id="caret-right" alt="image caret vers la droite">
                                        </li>
                                        <li><a href="#">Historique covoiturages</a>
                                            <img src="./assets/images/caret-vers-le-bas.png" id="caret-right" alt="image caret vers la droite">
                                        </li>
                                        <li><a href="./vehicule.php" target="_blank">Mes véhicules</a>
                                            <img src="./assets/images/caret-vers-le-bas.png" id="caret-right" alt="image caret vers la droite">
                                        </li>
                                    </ul>
                                </nav>
                        </section>
                    </section>
            <?php endif; ?>
    <?php endif; ?>
    </main>
    <!-- Footer -->
    <?php
    require 'includes/footer.php'
    ?>
        <!-- JS  -->
    <script src="./assets/js/main.js" type="module"></script>
</body>
</html>