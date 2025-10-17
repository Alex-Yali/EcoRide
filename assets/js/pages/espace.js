 /* --- Switch entre les radios --- */
document.addEventListener('DOMContentLoaded', () => {
  const radios = document.querySelectorAll('input[name="user-role"]');
  const chauffeurInfo = document.getElementById('chauffeur-info');
  const passagerSection = document.getElementById('user-profil');
  const chauffeurProfil = document.getElementById('user-chauffeur');

  // On garde une variable d'état : a-t-il validé ?
  let chauffeurValide = false;

  // Par défaut
  chauffeurInfo.style.display = 'none';
  chauffeurProfil.style.display = 'none';
  passagerSection.style.display = 'flex';

  radios.forEach(radio => {
    radio.addEventListener('change', () => {
      if (radio.value === 'chauffeur') {
        passagerSection.style.display = 'none';

        if (chauffeurValide) {
          chauffeurInfo.style.display = 'none';
          chauffeurProfil.style.display = 'flex';
        } else {
          chauffeurInfo.style.display = 'flex';
          chauffeurProfil.style.display = 'none';
        }

      } else {
        passagerSection.style.display = 'flex';
        chauffeurInfo.style.display = 'none';
        chauffeurProfil.style.display = 'none';
      }
    });
  });

  /* --- Informations chauffeur --- */
  const btnInfo = document.getElementById("btnInfo");

  if (btnInfo) {
    btnInfo.addEventListener("click", function(event) {
      event.preventDefault();

      const immat = document.getElementById("immat").value.trim();
      const immatDate = document.getElementById("dateImmat").value.trim();
      const modele = document.getElementById("modele").value.trim();
      const couleur = document.getElementById("couleur").value.trim();
      const marque = document.getElementById("marque").value.trim();
      const place = document.getElementById("place").value.trim();
      const tabac = document.querySelector('input[name="tabac"]:checked')?.value;
      const animal = document.querySelector('input[name="animal"]:checked')?.value;

      if (immat && immatDate && modele && couleur && marque && place && tabac && animal) {
        alert(
          "Plaque immatriculation : " + immat + "\n" +
          "Date 1ère immatriculation : " + immatDate + "\n" +
          "Modèle : " + modele + "\n" +
          "Couleur : " + couleur + "\n" +
          "Marque : " + marque + "\n" +
          "Place dispo : " + place + "\n" +
          "Tabac : " + tabac + "\n" +
          "Animal : " + animal
        );

        chauffeurValide = true;
        chauffeurInfo.style.display = "none";
        chauffeurProfil.style.display = "flex";

      } else {
        alert("Veuillez remplir tous les champs !");
      }
    });
  }
});

  /* Nom chauffeur */

  const pseudo = localStorage.getItem("pseudoUser");

  if (pseudo) {
    const pseudoCapitalized = pseudo.charAt(0).toUpperCase() + pseudo.slice(1);
    document.getElementById("first-name").textContent = pseudoCapitalized;
  }
