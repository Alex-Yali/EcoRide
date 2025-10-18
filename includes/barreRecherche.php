<form class="nav-bar">
            <!-- Départ -->
    <section class="nav-choix">
        <img src="./assets/images/Cars 1.png" class="icon" alt="image voiture">
        <input type="text" name="depart" id="depart" placeholder="Ville départ" required>
        <section class="separateur"></section>
    </section>
            <!-- Destination -->
    <section class="nav-choix">
        <img src="./assets/images/ping.png" class="icon" alt="image destination">
        <input type="text" name="destination" id="destination" placeholder="Ville d'arrivée" required>
        <section class="separateur"></section>
    </section>
            <!-- Calendrier -->
    <section class="nav-choix">
        <img src="./assets/images/calendrier gris.png" class="icon" alt="image calendrier">
        <input id="date" type="date" data-placeholder="jj/mm/aaaa">
    </section>
            <!-- Bouton -->
        <button id="btnNav" type="submit">Rechercher</button>
</form>