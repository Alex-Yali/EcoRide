<?php
require 'back/historiqueCovoiturage.php';
require 'back/infosUtilisateur.php';
require 'back/csrf.php'; 
$csrf = generate_csrf_token();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Historique covoiturage</title>

    <!-- Styles -->
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/pages/mescovoiturages.css">

    <!-- Google Fonts -->
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
        <h1 class="gros-titre">Historique covoiturage :</h1>
        <section class="userCovoit">
            <section class="user-id">
                <section class="user-name">
                    <img src="./assets/images/compte noir.png" alt="image compte noir">
                    <span id="first-name"><?= htmlspecialchars ($pseudoUtilisateur) ?></span>
                </section>
                <section class="user-info">
                    <img src="./assets/images/pile-de-pieces.png" alt="image pieces noir">
                    <span>Crédits restants : <?= htmlspecialchars ($creditsUtilisateur) ?></span>
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
                        <section class="box-covoit">
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

                                        <!-- Chauffeur -->
                                    <?php if ($c['statut'] === 'Terminer'): ?>
                                        <span>Trajet terminé</span>
                                    <?php elseif ($c['statut'] === 'Annuler'): ?>
                                        <span>Trajet annulé</span>
                                    <?php endif; ?>
                                    
                                </section>
                            </section>
                            <!-- Verifie si l'utilisateur connecté est le conducteur du covoiturage et si à déjà donné un avis -->
                            <?php 
                            $conducteur_id = $c['conducteur_id'];
                            $dejaAvis = avisDejaDonne ($pdo, $idUtilisateur, $c['covoiturage_id'], $conducteur_id); ?>
                            <?php if (($idUtilisateur !== $conducteur_id) && !$dejaAvis && $c['statut'] !== 'Annuler'): ?>
                            <section class="avis-covoit">
                                <form action="" class="formAvis" method="POST">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf); ?>">
                                    <!-- Étape 1 -->
                                    <section class="formAvisCovoit">
                                        <fieldset>
                                            <legend>Ce trajet s'est-il bien déroulé ?</legend>
                                            <label>
                                                <input type="radio" name="avis" value="Oui" checked> Oui
                                            </label>
                                            <label>
                                                <input type="radio" name="avis" value="Non"> Non
                                            </label>
                                        </fieldset>
                                        <button class="button" type="button" id="btnValider" name="action" value="valider">Valider</button>
                                    </section>
                                    <!-- Étape 2 -->
                                    <section class="avis" style="display: none;">
                                        <input type="hidden" name="avis" class="hidden-avis">
                                        <p>Votre avis nous intéresse</p>
                                        <section class="separateurFiltres"></section>
                                        <fieldset>
                                            <legend>Commentaire</legend>
                                            <label>
                                                <input type="text" name="commentaire" placeholder="Ajoutez un commentaire...">
                                            </label>
                                        </fieldset>
                                        <section class="separateurFiltres"></section>
                                        <section id="note">
                                            <h3>Note</h3>
                                            <section class="stars">
                                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                                    <input id="r<?= $i ?>" name="rating" type="radio" value="<?= $i ?>">
                                                    <label for="r<?= $i ?>" title="<?= $i ?> étoiles">★</label>
                                                <?php endfor; ?>
                                            </section>
                                        </section>
                                        <!-- Champ caché pour identifier le covoiturage -->
                                        <input type="hidden" name="covoiturage_id" value="<?= htmlspecialchars($c['covoiturage_id']) ?>">
                                        <button class="button" type="submit" id="btnEnvoyer" name="action" value="envoyer">Envoyer</button>
                                    </section>
                                </form>
                            </section>
                            <?php endif; ?>
                        </section>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p id="texteCovoit">Aucun historique de covoiturage.</p>
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
    <script src="./assets/js/pages/historique.js" type="module"></script>
</body>
</html>