<?php
require 'back/covoiturageEnCours.php';
require 'back/infosUtilisateur.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Covoiturages en cours</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/pages/mescovoiturages.css">
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
        <h1 class="gros-titre">Covoiturages en cours :</h1>
        <section class="userCovoit">
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
            <section>
                <?php if ($mesCovoit): ?>
                    <?php foreach ($mesCovoit as $c): ?>
                        <?php
                            $heureDepart = new DateTime($c['heure_depart']);
                            $heureArrivee = new DateTime($c['heure_arrivee']);
                            $duree = $heureDepart->diff($heureArrivee);
                            $dureeCovoit = $duree->h . 'h' . str_pad($duree->i, 2, '0', STR_PAD_LEFT);

                            // Choix de l’image selon l’énergie
                            $energie = strtolower(trim($c['energie']));
                            $image = $energie === 'essence' ? './assets/images/voiture-noir.png' : './assets/images/voiture-electrique.png';
                        ?>
                        <form class="box-covoit" action="" method="POST">
                            <p id="date-covoit"><?= htmlspecialchars($c['date_formatee']) ?></p>
                            <section class="info-covoit">
                                <section class="time-covoit">
                                    <section class="start-time">
                                        <p><?= htmlspecialchars($c['lieu_depart']) ?><br><?= date('H:i', strtotime($c['heure_depart'])) ?></p>
                                    </section>
                                    <section>
                                        <p class="duree"><?= htmlspecialchars($dureeCovoit) ?></p>
                                        <section class="ligne"></section>
                                    </section>
                                    <section class="end-time">
                                        <p><?= htmlspecialchars($c['lieu_arrivee']) ?><br><?= date('H:i', strtotime($c['heure_arrivee'])) ?></p>
                                    </section>
                                    <section class="nbr-place">
                                        <p><?= htmlspecialchars($c['nb_place']) ?> places</p>
                                    </section>
                                    <section class="prix-place">
                                        <p><?= htmlspecialchars($c['prix_personne']) ?> crédits</p>
                                    </section>
                                </section>
                                <section class="perso-covoit">
                                    <section class="perso">
                                        <img class="icon-perso" src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($energie) ?>">
                                        <img class="icon-perso" src="./assets/images/homme.png" alt="conducteur">
                                        <section class="perso-avis">
                                            <p><?= htmlspecialchars(ucfirst($c['conducteur_pseudo'] ?? 'N/A')) ?><br>
                                                <?= $c['conducteur_moyenne'] !== null ? round($c['conducteur_moyenne'], 1) . ' ★' : 'Non noté' ?>
                                            </p>
                                        </section>
                                    </section>
                                    <!-- Champ caché pour identifier le covoiturage -->
                                    <input type="hidden" name="covoiturage_id" value="<?= $c['covoiturage_id'] ?>">

                                    <?php if ($idUtilisateur === $c['conducteur_id']): ?>
                                        <!-- Chauffeur : boutons selon le statut -->
                                        <?php if ($c['statut'] === 'Demarrer'): ?>
                                            <button class="button btn-arrivee" type="submit" name="action" value="terminer">Arrivée à destination</button>
                                        <?php elseif ($c['statut'] === 'Terminer'): ?>
                                            <span>Trajet terminé</span>
                                        <?php elseif ($c['statut'] === 'Annuler'): ?>
                                            <span>Trajet annulé</span>
                                        <?php else: ?>
                                            <button class="button btn-demarrer" type="submit" name="action" value="demarrer">Démarrer</button>
                                        <?php endif; ?>

                                        <!-- Bouton Annuler visible pour le chauffeur -->
                                        <button class="button" type="submit" id="btnAnnuler" name="action" value="annuler">Annuler le voyage</button>

                                    <?php else: ?>
                                        <!-- Passager : afficher seulement le statut -->
                                        <?php if ($c['statut'] === 'Demarrer'): ?>
                                            <span>Trajet en cours</span>
                                        <?php elseif ($c['statut'] === 'Terminer'): ?>
                                            <span>Trajet terminé</span>
                                        <?php elseif ($c['statut'] === 'Annuler'): ?>
                                            <span>Trajet annulé</span>
                                        <?php else: ?>
                                            <span>Trajet à venir</span>
                                        <?php endif; ?>
                                        <!--  Bouton Annuler visible pour les passagers -->
                                        <button class="button" type="submit" id="btnAnnuler" name="action" value="annuler">Annuler le voyage</button>
                                    <?php endif; ?>

                                </section>
                            </section>
                        </form>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p id="texteCovoit">Aucun covoiturage en cours.</p>
                <?php endif; ?>
            </section>
        </section>
    </main>
    <!-- Footer -->
    <?php
    require 'includes/footer.php'
    ?>
        <!-- JS  -->
    <script src="./assets/js/main.js" type="module"></script>
</body>
</html>