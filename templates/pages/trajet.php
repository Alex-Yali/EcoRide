<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Proposer un covoiturage</title>

    <!-- Styles -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/pages/trajet.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wdth,wght@0,75..100,700;1,75..100,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
</head>

<body>

    <!-- Header -->
    <?php require APP_ROOT . "/templates/pages/includes/header.php"; ?>

    <main>
        <h1 class="gros-titre">Saisir un voyage :</h1>
        <!-- Message de succès / erreur -->
        <?php require APP_ROOT . "/src/Service/MessagesErreur.php" ?>

        <section class="ajoutTrajet">

            <form id="trajet" method="POST">

                <section class="ajouter-trajet">

                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf); ?>">

                    <!-- Départ -->
                    <section class="trajet-group">
                        <img src="/assets/images/Cars 1.png" class="icon" alt="">
                        <input id="depart2" name="depart" type="text" placeholder="Départ" required>
                        <span class="iconTrajet"></span>
                        <span class="error">La ville de départ doit être inférieur à 20 caractères</span>
                    </section>

                    <!-- Date & heure départ -->
                    <section class="trajet-group">
                        <img src="/assets/images/calendrier gris.png" class="icon" alt="">
                        <input id="dateDepart" type="date" name="dateDepart" min="<?= date('Y-m-d') ?>" required>
                        <input id="heureDepart" type="time" name="heureDepart" required>
                        <span class="error">Une date et heure sont requises</span>
                    </section>

                    <section class="separateurFiltres"></section>

                    <!-- Destination -->
                    <section class="trajet-group">
                        <img src="/assets/images/ping.png" class="icon" alt="">
                        <input id="destination2" name="destination" type="text" placeholder="Destination" required>
                        <span class="iconTrajet"></span>
                        <span class="error">La ville d'arrivée doit être inférieur à 20 caractères</span>
                    </section>

                    <!-- Date & heure arrivée -->
                    <section class="trajet-group">
                        <img src="/assets/images/calendrier gris.png" class="icon" alt="">
                        <input id="dateArrivee" type="date" name="dateArrivee" min="<?= date('Y-m-d') ?>" required>
                        <input id="heureArrivee" type="time" name="heureArrivee" required>
                        <span class="error">Une date et heure sont requises</span>
                    </section>

                    <section class="separateurFiltres"></section>

                    <!-- Places -->
                    <section class="trajet-group">
                        <img src="/assets/images/compte gris.png" class="icon" alt="">
                        <input type="number" name="places" id="places2" placeholder="Nombre de places" min="1" max="4" required>
                        <span class="iconTrajet"></span>
                        <span class="error">Veuillez sélectionner min 1 et max 4 places</span>
                    </section>

                    <!-- Prix -->
                    <section class="trajet-group">
                        <img src="/assets/images/prix gris.png" class="icon" alt="">
                        <input type="number" name="prix" id="prix2" placeholder="Prix (€)" min="1" max="20" required>
                        <span class="iconTrajet"></span>
                        <span class="error">Veuillez sélectionner max 20 crédits</span>
                    </section>

                    <!-- Véhicule -->
                    <section class="trajet-group">
                        <img src="/assets/images/ajouter voiture gris.png" class="icon" alt="">
                        <select id="cars2" name="voiture" required>
                            <option value="" disabled selected hidden>Véhicules</option>

                            <?php if (empty($voituresUtilisateur)) : ?>
                                <option value="" disabled>Vous n'avez aucun véhicule associé à votre compte.</option>
                            <?php else: ?>
                                <?php foreach ($voituresUtilisateur as $voiture): ?>
                                    <option value="<?= htmlspecialchars($voiture->getVoitureId() ?? 'N/A') ?>">
                                        <?= htmlspecialchars(ucfirst($voiture->getLibelle() ?? 'N/A')) ?>
                                        <?= htmlspecialchars(ucfirst($voiture->getModele() ?? 'N/A')) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <button class="modal-open-btn" id="openAjoutModal" title="Ajouter un véhicule">
                            <img src="/assets/images/icon plus.png" id="icon-plus" alt="Ajouter">
                        </button>
                        <span class="error">Veuillez sélectionner un véhicule</span>
                    </section>
                    <p class="info-icon">ℹ️: 2 crédits seront déduits de votre solde </p>

                    <!-- Bouton -->
                    <input type="hidden" name="formType" value="ajoutTrajet">
                    <button id="btnTrajet" class="button" type="submit">Publier</button>

                </section>
            </form>

            <!-- Image -->
            <img src="/assets/images/voiture 1.jpg" id="voiture" alt="Illustration voiture">
        </section>

        <!-- Modal ajout voiture -->
        <section id="ajoutVoiture" class="modal">
            <section class="voiture">
                <button class="close" data-modal="ajoutVoiture">x</button>
                <h2>Ajouter un véhicule</h2>

                <form method="POST" class="modal-content">

                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf); ?>">


                    <label>Plaque d’immatriculation : </label>
                    <section class="modal-group">
                        <input type="text" name="immatriculation" id="immatriculation" required>
                        <span class="iconModal"></span>
                        <span class="error">Format attendu : AA-123-AA</span>
                    </section>


                    <label>Date de 1ère immatriculation : </label>
                    <section class="modal-group">
                        <input type="text" name="dateImmat" id="dateImmat" required>
                        <span class="iconModal"></span>
                        <span class="error">Format attendu : 12 mars 2026</span>
                    </section>

                    <label>Marque : </label>
                    <section class="modal-group">
                        <input type="text" name="marque" id="marque" required>
                        <span class="iconModal"></span>
                        <span class="error">La marque doit être inférieur à 15 caractères</span>
                    </section>

                    <label>Modèle : </label>
                    <section class="modal-group">
                        <input type="text" name="modele" id="modele" required>
                        <span class="iconModal"></span>
                        <span class="error">Le modele doit être inférieur à 20 caractères</span>
                    </section>


                    <label>Couleur : </label>
                    <section class="modal-group">
                        <input type="text" name="couleur" id="couleur" required>
                        <span class="iconModal"></span>
                        <span class="error">La couleur doit être inférieur à 15 caractères</span>
                    </section>

                    <label>Places dispo : </label>
                    <section class="modal-group">
                        <input type="number" name="place" id="place" min="1" max="4" required>
                        <span class="iconModal"></span>
                        <span class="error">Veuillez sélectionner min 1 et max 4 places</span>
                    </section>

                    <section class="modal-group">
                        <label for="energie">Énergie utilisée :</label>
                        <input list="typeEnergie" id="energie" name="energie" required>
                        <datalist id="typeEnergie">
                            <option value="Essence">
                            <option value="Diesel">
                            <option value="Électrique">
                        </datalist>
                        <span class="iconModal"></span>
                        <span class="error">Veuillez sélectionner un type d'énergie</span>
                    </section>

                    <input type="hidden" name="formType" value="ajoutVoiture">

                    <button class="button" id="btnInfo" type="submit">Enregistrer</button>

                </form>
            </section>
        </section>

    </main>

    <!-- Footer -->
    <?php require APP_ROOT . "/templates/pages/includes/footer.php" ?>

    <!-- Scripts -->
    <script src="/assets/js/main.js" type="module"></script>
    <script src="/assets/js/pages/trajet.js" type="module"></script>
</body>

</html>