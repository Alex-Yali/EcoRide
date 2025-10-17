<form class="nav-bar">
            <!-- Départ -->
    <section class="nav-choix">
        <img src="./assets/images/Cars 1.png" class="icon" alt="image voiture">
        <select id="depart" name="nav" required>
            <option value="" disabled selected hidden>Départ</option>
            <option value="Bordeaux">Bordeaux</option>
            <option value="Lens">Lens</option>
            <option value="Lille">Lille</option>
            <option value="Lyon">Lyon</option>
            <option value="Marseille">Marseille</option>
            <option value="Nantes">Nantes</option>
            <option value="Nice">Nice</option>
            <option value="Paris">Paris</option>    
            <option value="Toulon">Toulon</option>
            <option value="Toulouse">Toulouse</option>
        </select>
        <section class="separateur"></section>
    </section>
            <!-- Destination -->
    <section class="nav-choix">
        <img src="./assets/images/ping.png" class="icon" alt="image destination">
        <select id="destination" name="nav" required>
            <option value="" disabled selected hidden>Destination</option>
            <option value="Bordeaux">Bordeaux</option>
            <option value="Lens">Lens</option>
            <option value="Lille">Lille</option>
            <option value="Lyon">Lyon</option>
            <option value="Marseille">Marseille</option>
            <option value="Nantes">Nantes</option>
            <option value="Nice">Nice</option>
            <option value="Paris">Paris</option>    
            <option value="Toulon">Toulon</option>
            <option value="Toulouse">Toulouse</option>
        </select>
        <section class="separateur"></section>
    </section>
            <!-- Calendrier -->
    <section class="nav-choix">
        <img src="./assets/images/calendrier gris.png" class="icon" alt="image calendrier">
        <input id="date" type="date" aria-label="Date" />
    </section>
            <!-- Bouton -->
        <button id="btnNav" type="submit">Rechercher</button>
</form>