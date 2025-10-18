<style>
   /* Structure générale */
.nav-bar {
  display: flex;
  align-items: center;
  gap: 10px;
}

.nav-choix {
  position: relative;
  display: flex;
  align-items: center;
}

.icon {
  width: 20px;
  height: 20px;
  margin-right: 8px;
}

.separateur {
  width: 1px;
  height: 30px;
  background-color: #ccc;
  margin-left: 8px;
}

/* Inputs généraux */
input[type="text"],
input[type="date"] {
  border: none;
  outline: none;
  font-size: 16px;
  padding: 8px;
  color: #000;
}

/* Placeholder classique pour les champs texte */
input::placeholder {
  color: #999;
}

/* Simulation de placeholder pour le champ date */
.date-container::before {
  content: attr(data-placeholder);
  position: absolute;
  left: 40px; /* ajuste selon la largeur de ton icône */
  color: #999;
  pointer-events: none;
  transition: 0.2s;
}

/* Masquer le placeholder simulé quand une date est saisie */
input[type="date"]:focus,
input[type="date"]:valid {
  color: #000;
}

input[type="date"]:focus ~ .date-container::before,
input[type="date"]:valid ~ .date-container::before {
  opacity: 0;
}
 
</style>

<form class="nav-bar">
  <!-- Départ -->
  <section class="nav-choix">
    <img src="./assets/images/Cars 1.png" class="icon" alt="image voiture">
    <input type="text" name="depart" id="depart" placeholder="Ville de départ" required>
    <section class="separateur"></section>
  </section>

  <!-- Destination -->
  <section class="nav-choix">
    <img src="./assets/images/ping.png" class="icon" alt="image destination">
    <input type="text" name="destination" id="destination" placeholder="Ville d'arrivée" required>
    <section class="separateur"></section>
  </section>

  <!-- Calendrier -->
  <section class="nav-choix date-container" data-placeholder="jj/mm/aaaa">
    <img src="./assets/images/calendrier gris.png" class="icon" alt="image calendrier">
    <input id="date" type="date" name="date" required>
  </section>

  <!-- Bouton -->
  <button id="btnNav" type="submit">Rechercher</button>
</form>
