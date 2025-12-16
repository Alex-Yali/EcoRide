<?php 
require_once 'back/detailCovoiturage.php';
require_once 'back/fonctionCalculTrajetDetail.php';
require_once 'back/infosUtilisateur.php';
require_once 'back/csrf.php';
$csrf = generate_csrf_token();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Détails du voyage</title>

    <!-- Styles -->
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/pages/detail.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wdth,wght@0,75..100,700;1,75..100,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <?php require 'includes/header.php'; ?>

    <main>
        <h1 class="gros-titre">Détail du voyage :</h1>
        <section class="detail">

            <!-- Box informations conducteur -->
            <section class="box-detail">
                <section class="user-photo">
                    <img id="photo" src="./assets/images/homme.png" alt="photo de l'utilisateur">
                    <p>
                        <?= htmlspecialchars(ucfirst($covoitDetail['pseudo'] ?? 'N/A')) ?><br>
                        <a href="#" class="note" data-id="<?= htmlspecialchars($covoitDetail['covoiturage_id'] ?? '') ?>">
                            <?= $covoitDetail['moyenne'] ? round($covoitDetail['moyenne'], 1) . ' ★' : 'Non noté' ?>
                        </a>
                    </p>
                </section>

                <section class="separateurFiltres"></section>

                <section class="user-detail">
                    <img class="user-icon" src="<?= htmlspecialchars($image) ?>" alt="icon voiture">
                    <p class="detail-size">
                        <?= htmlspecialchars(ucfirst($covoitDetail['marqueVoiture'] ?? 'N/A')) ?> : 
                        <?= htmlspecialchars(ucfirst($covoitDetail['modele'] ?? 'N/A')) ?><br>
                        Energie : <?= htmlspecialchars(ucfirst($covoitDetail['energie'] ?? 'N/A')) ?>
                    </p>
                </section>

                <section class="separateurFiltres"></section>

                <h2>Préférences conducteur</h2>
                <?php if (!empty($preferences)): ?>
                    <?php foreach ($preferences as $index => $pref): ?>
                        <section class="user-detail">
                            <p class="detail-size"><?= htmlspecialchars(ucfirst($pref)) ?></p>
                        </section>
                        <?php if ($index < count($preferences) - 1): ?>
                            <section class="separateurFiltres"></section>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucune préférence renseignée.</p>
                <?php endif; ?>

            </section>

            <!-- Box informations covoiturage -->
            <section class="box-covoit">
                <section class="info-covoit">
                    <p id="date-covoit"><?= htmlspecialchars(ucfirst($dateDetailCovoit ?? '')) ?></p>

                    <section class="time-covoit">
                        <section class="start-time">
                            <p>
                                <?= htmlspecialchars(ucfirst($covoitDetail['lieu_depart'] ?? '')) ?><br>
                                <?= htmlspecialchars(date('H:i', strtotime($covoitDetail['heure_depart'] ?? '00:00'))) ?>
                            </p>
                        </section>

                        <section>
                            <p class="duree"><?= htmlspecialchars($dureeCovoit ?? '') ?></p>
                            <section class="ligne"></section>
                        </section>

                        <section class="end-time">
                            <p>
                                <?= htmlspecialchars(ucfirst($covoitDetail['lieu_arrivee'] ?? '')) ?><br>
                                <?= htmlspecialchars(date('H:i', strtotime($covoitDetail['heure_arrivee'] ?? '00:00'))) ?>
                            </p>
                        </section>

                        <section class="nbr-place">
                            <p><?= htmlspecialchars($covoitDetail['nb_place'] ?? 0) ?> places</p>
                        </section>
                    </section>
                </section>

                <?php
                $participeDeja = participeDeja($pdo, $idUtilisateur, $idCovoit);
                if ($participeDeja): ?>
                        <section>
                            <p id="Participe">Votre participation au covoiturage a été enregistrée !</p>
                        </section>
                <?php else: ?>
                    <section class="participe">
                        <section class="prix-place">
                            <p>1 passager</p>
                            <p><?= htmlspecialchars($covoitDetail['prix_personne'] ?? 0) ?> crédits</p>
                        </section>

                        <?php
                        $prixCovoit = $covoitDetail['prix_personne'];
                        $placeDispo = $covoitDetail['nb_place'];
                        $creditsUtilisateur = $creditsUtilisateur ?? 0;
                        if ($idUtilisateur && $creditsUtilisateur >= $prixCovoit && $placeDispo > 0): ?>
                            <button id="btnParticipe" class="button" type="button">Participer</button>
                        <?php elseif (!$idUtilisateur): ?>
                            <a href="connexion.php" class="button" id="btnConnexion">Se connecter</a>
                        <?php else: ?>
                            <button id="btnParticipeDesactive" class="button" type="button" 
                                    title="Crédits insuffisants ou aucune place disponible pour ce voyage.">Participer</button>
                        <?php endif; ?>
                    </section>

                    <form class="valid" action="" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf); ?>">
                        <p id="valid-size">
                            Souhaitez-vous utiliser <?= htmlspecialchars($covoitDetail['prix_personne'] ?? 0) ?> crédits pour réserver votre place sur ce voyage ?
                        </p>
                        <section id="notif-valid">
                            <a href="detail.php?id=<?= urlencode($covoitDetail['covoiturage_id'] ?? '') ?>">Retour</a>
                            <button id="btnValid" class="button" type="submit" name="action" value="oui">Oui</button>
                        </section>
                    </form>
                <?php endif; ?>
            </section>
        </section>
    </main>

    <!-- Footer -->
    <?php require 'includes/footer.php'; ?>

    <!-- JS -->
    <script> const roleUtilisateur = <?= json_encode($roleUtilisateur) ?>; </script>
    <script src="./assets/js/main.js" type="module"></script>
    <script src="./assets/js/pages/detail.js" type="module"></script>

</body>
</html>
