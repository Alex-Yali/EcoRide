<?php require 'back/mesVoitures.php';
 require 'back/ajoutVoitureTrajet.php';
 require 'back/ajoutTrajet.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Proposer un covoiturage</title>

    <!-- Styles -->
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/pages/trajet.css">

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
        <h1 class="gros-titre">Saisir un voyage :</h1>

        <!-- Messages d'erreur ajout covoit -->
        <?php if (!empty($messageTrajet)): ?>
            <p style="color: <?= ($trajetValide ?? false) ? 'green' : 'red' ?>; text-align:center; margin:0;">
                <?= htmlspecialchars($messageTrajet) ?>
            </p>
        <?php endif; ?>

        <section class="ajoutTrajet">

        <form id="trajet" method="POST">

            <section class="ajouter-trajet">

                <!-- Départ -->
                <section class="nav-cat">
                    <img src="./assets/images/Cars 1.png" class="icon" alt="">
                    <input id="depart2" name="depart" type="text" placeholder="Départ" required>
                </section>

                <!-- Date & heure départ -->
                <section class="nav-cat">
                    <img src="./assets/images/calendrier gris.png" class="icon" alt="">
                    <input type="date" name="dateDepart" required>
                    <input type="time" name="heureDepart" required>
                </section>

                <section class="separateurFiltres"></section>

                <!-- Destination -->
                <section class="nav-cat">
                    <img src="./assets/images/ping.png" class="icon" alt="">
                    <input id="destination2" name="destination" type="text" placeholder="Destination" required>
                </section>

                <!-- Date & heure arrivée -->
                <section class="nav-cat">
                    <img src="./assets/images/calendrier gris.png" class="icon" alt="">
                    <input type="date" name="dateArrivee" required>
                    <input type="time" name="heureArrivee" required>
                </section>

                <section class="separateurFiltres"></section>

                <!-- Places -->
                <section class="nav-cat">
                    <img src="./assets/images/compte gris.png" class="icon" alt="">
                    <input type="number" name="places" id="places2" placeholder="Nombre de places" min="1" required>
                </section>

                <!-- Prix -->
                <section class="nav-cat">
                    <img src="./assets/images/prix gris.png" class="icon" alt="">
                    <input type="number" name="prix" id="prix2" placeholder="Prix (€)" min="1" required>
                    <span class="info-icon" title="2 crédits seront déduits de votre solde">ℹ️</span>
                </section>

                <!-- Véhicule -->
                <section class="nav-cat">
                    <img src="./assets/images/ajouter voiture gris.png" class="icon" alt="">

                    <select id="cars2" name="voiture" required>
                        <option value="" disabled selected hidden>Véhicules</option>

                        <?php if (!empty($voitures)) : ?>
                            <?php foreach ($voitures as $voiture): ?>
                                <option value="<?= htmlspecialchars($voiture['voiture_id']) ?>">
                                    <?= htmlspecialchars(ucfirst($voiture['libelle'] ?? 'N/A')) ?>
                                    <?= htmlspecialchars(ucfirst($voiture['modele'] ?? '')) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>

                    <a title="Ajouter un véhicule" href="#modal">
                        <img src="./assets/images/icon plus.png" id="icon-plus" alt="Ajouter">
                    </a>
                </section>

                <!-- Bouton -->
                <input type="hidden" name="formType" value="ajoutTrajet">
                <button id="btnTrajet" class="button" type="submit">Publier</button>

            </section>
        </form>

            <!-- Image -->
            <img src="./assets/images/voiture 1.jpg" id="voiture" alt="Illustration voiture">
        </section>

            <!-- MODAL AJOUT VOITURE -->
            <section id="modal" class="modal">
                <section class="voiture">
                    <a href="#" class="close">x</a>
                    <h2>Ajouter un véhicule</h2>

                <!-- Messages d'erreur ajout voiture -->
                <?php if (!empty($messageVoiture)): ?>
                    <p style="color: <?= ($voitureValide ?? false) ? 'green' : 'red' ?>; text-align:center; margin:0;">
                        <?= htmlspecialchars($messageVoiture) ?>
                    </p>
                <?php endif; ?>


                    <form method="POST" class="modal-content">

                        <label>Plaque d’immatriculation :
                            <input type="text" name="immatriculation" required>
                        </label>

                        <label>Date de 1ère immatriculation :
                            <input type="text" name="dateImmat" required>
                        </label>

                        <label>Marque :
                            <input type="text" name="marque" required>
                        </label>

                        <label>Modèle :
                            <input type="text" name="modele" required>
                        </label>

                        <label>Couleur :
                            <input type="text" name="couleur" required>
                        </label>

                        <label>Places dispo :
                            <input type="number" name="place" min="1" required>
                        </label>

                        <section class="voitureEnergie">
                            <label for="energie">Énergie utilisée :</label>
                            <input list="typeEnergie" id="energie" name="energie" required>
                            <datalist id="typeEnergie">
                                <option value="Essence">
                                <option value="Diesel">
                                <option value="Électrique">
                            </datalist>
                        </section>

                        <input type="hidden" name="formType" value="ajoutVoiture">

                        <button class="button" id="btnInfo" type="submit">Enregistrer</button>

                    </form>
                </section>
            </section>

    </main>

    <!-- Footer -->
    <?php require 'includes/footer.php'; ?>

    <!-- Scripts -->
    <script src="./assets/js/main.js" type="module"></script>
    <script src="./assets/js/pages/trajet.js" type="module"></script>
</body>
</html>
