<?php
// Récupération des filtres optionnels pour conserver les valeurs dans le formulaire
$filters = $filters ?? [
    'maxPrix' => '',
    'maxTime' => '',
    'rating'  => '',
    'ecolo'   => ''
];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Covoiturages disponibles</title>

    <!-- Styles -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/pages/covoiturage.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wdth,wght@0,75..100,700;1,75..100,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Header -->
    <?php require APP_ROOT . "/templates/pages/includes/header.php"; ?>

    <main>
        <h1 class="gros-titre">Covoiturages disponibles :</h1>

        <!-- Barre de recherche -->
        <?php require APP_ROOT . "/templates/pages/includes/barreRecherche.php" ?>

        <!-- Messages d'erreur covoiturage -->
        <?php require APP_ROOT . "/src/Service/MessagesErreur.php" ?>

        <section class="covoit">
            <!-- Filtres latéraux -->
            <form class="filtres" action="" method="POST">
                <!-- Inputs cachés pour conserver la recherche -->
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf ?? ''); ?>">
                <input type="hidden" name="depart" value="<?= htmlspecialchars($_POST['depart'] ?? '') ?>">
                <input type="hidden" name="arrivee" value="<?= htmlspecialchars($_POST['arrivee'] ?? '') ?>">
                <input type="hidden" name="date" value="<?= htmlspecialchars($_POST['date'] ?? '') ?>">

                <section>
                    <h1>Durée du voyage</h1>
                    <section class="time">
                        <img src="/assets/images/sablier.png" class="icon-ecolo" alt="icon sablier">
                        <input type="number" name="maxTime" class="maxTime" min="1" value="<?= htmlspecialchars($filters['maxTime']) ?>">
                        <span>heures Max</span>
                    </section>
                </section>

                <section class="separateurFiltres"></section>

                <section>
                    <h1>Prix</h1>
                    <section class="time">
                        <img src="/assets/images/pile-de-pieces.png" class="icon-ecolo" alt="icon pile de piece">
                        <input type="number" name="maxPrix" class="maxTime" min="1" value="<?= htmlspecialchars($filters['maxPrix']) ?>">
                        <span>crédits Max</span>
                    </section>
                </section>

                <section class="separateurFiltres"></section>

                <section id="note">
                    <h1>Note</h1>
                    <section class="stars">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input id="r<?= $i ?>" name="rating" type="radio" value="<?= $i ?>" <?= ($filters['rating'] == $i) ? 'checked' : '' ?>>
                            <label for="r<?= $i ?>" title="<?= $i ?> étoiles">★</label>
                        <?php endfor; ?>
                    </section>
                </section>
                <section class="separateurFiltres"></section>

                <section>
                    <h1>Voyage écologique</h1>
                    <section class="ecolo">
                        <img src="/assets/images/voiture-electrique.png" class="icon-elec" alt="icon voiture electrique">
                        <section id="voiture-elec">
                            <label><input type="radio" name="ecolo" value="oui" <?= ($filters['ecolo'] === 'oui') ? 'checked' : '' ?>>oui</label>
                            <label><input type="radio" name="ecolo" value="non" <?= ($filters['ecolo'] === 'non') ? 'checked' : '' ?>>non</label>
                        </section>
                    </section>
                </section>

                <section class="separateurFiltres"></section>

                <button name="btnReset" id="btnReset" value="1" type="submit">Tout effacer</button>
                <button id="btnFiltres" class="button" type="submit">Appliquer</button>
            </form>

            <!-- Résultats -->
            <section class="box-covoit">
                <p id="date-covoit"><?= htmlspecialchars(ucfirst($covoits[0]->getDateFormatted()) ?? '') ?></p>
                <?php if ($covoits): ?>
                    <?php foreach ($covoits as $c): ?>
                        <section class="info-covoit">
                            <section class="time-covoit">
                                <section class="start-time">
                                    <p><?= htmlspecialchars($c->getLieuDepart()) ?><br><?= htmlspecialchars($c->getHeureDepartFormat()) ?></p>
                                </section>
                                <section>
                                    <p class="duree"><?= htmlspecialchars($c->getDureeFormatted()) ?></p>
                                    <section class="ligne"></section>
                                </section>
                                <section class="end-time">
                                    <p><?= htmlspecialchars($c->getLieuArrivee()) ?><br><?= htmlspecialchars($c->getHeureArriveeFormat()) ?></p>
                                </section>
                                <section class="nbr-place">
                                    <p><?= htmlspecialchars($c->getNbPlace()) ?> places</p>
                                </section>
                                <section class="prix-place">
                                    <p><?= htmlspecialchars($c->getPrixPersonne()) ?> crédits</p>
                                </section>
                            </section>
                            <section class="perso-covoit">
                                <section class="perso">
                                    <img class="icon-perso" src="<?= htmlspecialchars($c->getImageVoiture()) ?>" alt="voiture">
                                    <img class="icon-perso" src="/assets/images/homme.png" alt="conducteur">
                                    <section class="perso-avis">
                                        <p><?= htmlspecialchars($c->getPseudo()) ?><br><?= htmlspecialchars($c->getMoyenne() !== null ? round($c->getMoyenne(), 1) . ' ★' : 'Non noté') ?></p>
                                    </section>
                                </section>
                                <button class="button btn-detail" type="button" data-id="<?= htmlspecialchars($c->getCovoiturageId()) ?>">Détails</button>
                            </section>
                        </section>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun covoiturage ne correspond aux critères.</p>
                <?php endif; ?>
            </section>
        </section>
    </main>
    <!-- Footer -->
    <?php require APP_ROOT . "/templates/pages/includes/footer.php" ?>

    <!-- JS -->
    <script src="/assets/js/main.js" type="module"></script>
    <script src="/assets/js/pages/covoiturage.js" type="module"></script>
</body>

</html>