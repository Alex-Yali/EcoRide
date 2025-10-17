<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Covoiturages disponibles</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/pages/covoiturage.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wdth,wght@0,75..100,700;1,75..100,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <?php
    require 'includes/header.php'
    ?>
<main>
        <h1 class="gros-titre">Covoiturages disponibles :</h1>
    <!-- Barre de recherche -->
    <?php
    require 'includes/barreRecherche.php'
    ?>
        <!-- Box filtres -->
         <section class="covoit">
            <aside class="filtres">
                <section>
                    <h1>Durée du voyage</h1>
                        <section class="time">
                            <img src="./assets/images/sablier.png" class="icon-ecolo" alt="icon sablier">
                            <input type="number" name="maxTime" class="maxTime"  required>
                            <span>Max</span>
                        </section>
                </section>
                <section>
                    <h1>Prix</h1>
                        <section class="time">
                            <img src="./assets/images/pile-de-pieces.png" class="icon-ecolo" alt="icon pile de piece">
                            <input type="number" name="maxPrix" class="maxTime"  required>
                            <span>Max</span>
                        </section>
                </section>
                <section id="note">
                    <h1>Note</h1>
                        <form>
                        <section class="stars">
                            <input id="r5" name="rating" type="radio" value="5">
                            <label for="r5" title="5 étoiles">★</label>

                            <input id="r4" name="rating" type="radio" value="4">
                            <label for="r4" title="4 étoiles">★</label>

                            <input id="r3" name="rating" type="radio" value="3">
                            <label for="r3" title="3 étoiles">★</label>

                            <input id="r2" name="rating" type="radio" value="2">
                            <label for="r2" title="2 étoiles">★</label>

                            <input id="r1" name="rating" type="radio" value="1">
                            <label for="r1" title="1 étoile">★</label>
                        </section>
                        </form>
                </section>
                <section>
                    <h1>Voyage ecologique</h1>
                    <section class="ecolo">
                        <img src="./assets/images/voiture-electrique.png" class="icon-elec" alt="icon voiture electrique">
                            <form id="voiture-elec">
                                <fieldset>
                                    <legend>Je suis :</legend>
                                    <label><input type="radio" name="ecolo" value="oui">oui</label>
                                    <label><input type="radio" name="ecolo" value="non">non</label>
                                </fieldset>
                            </form>
                    </section>
                </section>
            </aside>
            <section class="box-covoit">
                <p id="date-covoit">Jeudi 26 juin</p>
                <section class="info-covoit">
                    <section class="time-covoit">
                        <section class="start-time">
                            <p>Paris<br>08:20</p>
                        </section>
                        <section>
                            <p class="duree">5h10</p>
                            <section class="ligne"></section>
                        </section>
                        <section class="end-time">
                            <p>Lyon<br>13:30</p>
                        </section>
                        <section class="nbr-place">
                            <p>3 places</p>
                        </section>
                        <section class="prix-place">
                            <p>5 crédits</p>
                        </section>
                    </section>
                    <section class="perso-covoit">
                        <section class="perso">
                            <img class="icon-perso" src="./assets/images/voiture-noir.png" alt="icon voiture noir">
                            <img class="icon-perso" src="./assets/images/homme.png" alt="icon homme">
                            <section class="perso-avis">
                                <p>Alex<br>★ 4,6</p>
                            </section>
                        </section>
                        <button id="btnDetail" type="submit">Détails</button>
                    </section>
                </section>
                <section class="info-covoit">
                    <section class="time-covoit">
                        <section class="start-time">
                            <p>Paris<br>12:00</p>
                        </section>
                        <section>
                            <p class="duree">5h30</p>
                            <section class="ligne"></section>
                        </section>
                        <section class="end-time">
                            <p>Lyon<br>17:30</p>
                        </section>
                        <section class="nbr-place">
                            <p>1 places</p>
                        </section>
                        <section class="prix-place">
                            <p>4 crédits</p>
                        </section>
                    </section>
                    <section class="perso-covoit">
                        <section class="perso">
                            <img class="icon-perso" src="./assets/images/voiture-electrique.png" alt="icon voiture noir">
                            <img class="icon-perso" src="./assets/images/femme.png" alt="icon homme">
                            <section class="perso-avis">
                                <p>Stef<br>★ 4,8</p>
                            </section>
                        </section>
                        <button id="btnDetail" type="submit">Détails</button>
                    </section>
                </section>
                <section class="info-covoit">
                    <section class="time-covoit">
                        <section class="start-time">
                            <p>Paris<br>15:30</p>
                        </section>
                        <section>
                            <p class="duree">5h15</p>
                            <section class="ligne"></section>
                        </section>
                        <section class="end-time">
                            <p>Lyon<br>20:45</p>
                        </section>
                        <section class="nbr-place">
                            <p>2 places</p>
                        </section>
                        <section class="prix-place">
                            <p>6 crédits</p>
                        </section>
                    </section>
                    <section class="perso-covoit">
                        <section class="perso">
                            <img class="icon-perso" src="./assets/images/voiture-noir.png" alt="icon voiture noir">
                            <img class="icon-perso" src="./assets/images/femme 2.png" alt="icon homme">
                            <section class="perso-avis">
                                <p>Marie<br>★ 4,5</p>
                            </section>
                        </section>
                        <button id="btnDetail" type="submit">Détails</button>
                    </section>
                </section>
            </section>
         </section>
</main>
    <!-- Footer -->
    <?php
    require 'includes/footer.php'
    ?>
        <!-- JS  -->
    <script src="./assets/js/main.js" type="module"></script>
    <script src="./assets/js/pages/covoiturage.js" type="module"></script>
</body>
</html>