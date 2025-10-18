<?php
session_start();

if (empty($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$pseudo = $_SESSION['user_pseudo'] ?? 'Utilisateur';
$pseudo = ucfirst($pseudo);
$display_pseudo = htmlspecialchars($pseudo, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
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
        <section class="user-box">
            <form id="user-type">
                <fieldset>
                    <legend>Je suis :</legend>
                    <label><input type="radio" name="user-role" value="passager" checked>Passager</label>
                    <label><input type="radio" name="user-role" value="chauffeur">Chauffeur</label>
                    <label><input type="radio" name="user-role" value="chauffeur">Les deux</label>
                </fieldset>
            </form>
        </section>
        <!-- Section qui sera affichée uniquement si "Chauffeur" est choisi -->
        <section id="chauffeur-info">
        <h2>Informations chauffeur</h2>
            <label>Plaque d’immatriculation :<input type="text" id="immat" name="immatriculation"></label>
            <label>Date de 1ère immatriculation :<input type="text" id="dateImmat" name="dateImat"></label>
            <label>Modèle :<input type="text" id="modele" name="modele"></label>
            <label>Couleur :<input type="text" id="couleur" name="couleur"></label>
            <label>Marque :<input type="text" id="marque" name="marque"></label>
            <label>Place dispo :<input type="number" id="place" name="place"></label>
            <section class="separator"></section>
            <section class="pref">
                <h2>Préférences :</h2>
                <form id="user-pref">
                    <fieldset>
                        <legend>Tabac</legend>
                        <label><input type="radio" name="tabac" value="Autorisé" checked>Autorisé</label>
                        <label><input type="radio" name="tabac" value="Non autorisé">Non autorisé</label>
                    </fieldset>
                    <fieldset>
                        <legend>Animal</legend>
                        <label><input type="radio" name="animal" value="Autorisé" checked>Autorisé</label>
                        <label><input type="radio" name="animal" value="Non autorisé">Non autorisé</label>
                    </fieldset>
                </form>
                <button id="btnInfo" type="submit">Enregistrer</button>
            </section>
        </section>
        <!-- Section infos profil -->
        <section id="user-profil">
            <section class="user-menu">
                <section class="user-id">
                    <section class="user-name">
                        <img src="./assets/images/compte noir.png" alt="image compte noir">
                        <span id="first-name"><?= $display_pseudo ?></span>
                    </section>
                    <section class="user-info">
                        <img src="./assets/images/pile-de-pieces.png" alt="image pieces noir">
                        <span>Crédits restants : 20</span>
                    </section>
                </section>
                    <nav class="user-link">
                        <ul>
                            <li><a href="./mesCovoiturages.php">Covoiturages en cours</a>
                                <img src="./assets/images/caret-vers-le-bas.png" id="caret-right" alt="image caret vers la droite">
                            </li>
                            <li><a href="#">Historique covoiturages</a>
                                <img src="./assets/images/caret-vers-le-bas.png" id="caret-right" alt="image caret vers la droite">
                            </li>
                            <li><a href="#">Mes véhicules</a>
                                <img src="./assets/images/caret-vers-le-bas.png" id="caret-right" alt="image caret vers la droite">
                            </li>
                        </ul>
                    </nav>
            </section>
        </section>
        <section id="user-chauffeur">
            <section class="user-menu">
                <section class="user-id">
                    <section class="user-name">
                        <img src="./assets/images/compte noir.png" alt="image compte noir">
                        <span id="first-name"><?= $display_pseudo ?></span>
                    </section>
                    <section class="user-info">
                        <img src="./assets/images/pile-de-pieces.png" alt="image pieces noir">
                        <span>Crédits restants : 20</span>
                    </section>
                </section>
                    <nav class="user-link">
                        <ul>
                            <li><a href="./trajet.php">Saisir un voyage</a>
                                <img src="./assets/images/caret-vers-le-bas.png" id="caret-right" alt="image caret vers la droite">
                            </li>
                            <li><a href="./mesCovoiturages.php">Covoiturages en cours</a>
                                <img src="./assets/images/caret-vers-le-bas.png" id="caret-right" alt="image caret vers la droite">
                            </li>
                            <li><a href="#">Historique covoiturages</a>
                                <img src="./assets/images/caret-vers-le-bas.png" id="caret-right" alt="image caret vers la droite">
                            </li>
                            <li><a href="#">Mes véhicules</a>
                                <img src="./assets/images/caret-vers-le-bas.png" id="caret-right" alt="image caret vers la droite">
                            </li>
                        </ul>
                    </nav>
            </section>
        </section>
    </main>
    <!-- Footer -->
    <?php
    require 'includes/footer.php'
    ?>
        <!-- JS  -->
    <script src="./assets/js/main.js" type="module"></script>
    <script src="./assets/js/pages/espace.js" type="module"></script>
</body>
</html>