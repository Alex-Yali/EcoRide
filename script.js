 /* Menu déroulant */
 
  const menuImg = document.getElementById("menu-img");
  const menuBox = document.getElementById("menu-box");
  const menu = document.getElementById("menu");

  menuImg.addEventListener("click", () => {
    menuBox.classList.toggle("hidden");

    if (menuBox.classList.contains("hidden")) {
      menuImg.src = "/Projet/images/icon compte.png";   // image quand menu fermé
    } else {
      menuImg.src = "/Projet/images/icon compte up.png";  // image quand menu ouvert
    }
  });

  document.addEventListener("click", (e) => {
    if (!menu.contains(e.target)) {
      menuBox.classList.add("hidden");
      menuImg.src = "/Projet/images/icon compte.png"; // on remet l’image du menu si clic en dehors
    }
  });


/* Barre de recherche */ 
    const btnNav = document.getElementById("btnNav");

      if (btnNav) {
        btnNav.addEventListener("click", function(event) {
          event.preventDefault();

        const depart = document.getElementById("depart").value.trim();
        const destination = document.getElementById("destination").value.trim();
        const date = document.getElementById("date").value.trim();
        const passager = document.getElementById("passager").value.trim();

        if (depart && destination && date && passager) {
          alert("Départ : " + depart + "\nDestination : " + destination + "\nDate : " + date + "\nPassagers : " + passager);
          window.location.href = "/Projet/Covoiturage/covoiturage.html";
        } else {
          alert("Veuillez remplir tous les champs !");
        }
      });
    }

/* Proposer trajet */ 

  const btnTrajet = document.getElementById("btnTrajet");

    if (btnTrajet) {
      btnTrajet.addEventListener("click", function(event) {
        event.preventDefault();

      const depart2 = document.getElementById("depart2").value.trim();
      const destination2 = document.getElementById("destination2").value.trim();
      const date2 = document.getElementById("date2").value.trim();
      const place2 = document.getElementById("places2").value.trim();
      const prix2 = document.getElementById("prix2").value.trim();
      const cars2 = document.getElementById("cars2").value.trim();

      if (depart2 && destination2 && date2 && place2 && prix2 && cars2) {
        alert(
          "Départ : " + depart2 + "\n" +
          "Destination : " + destination2 + "\n" +
          "Date : " + date2 + "\n" +
          "Nombre places : " + place2 + "\n" +
          "Prix : " + prix2 + "\n" +
          "Véhicule : " + cars2 );
          window.location.href = "/Projet/Covoiturage/covoiturage.html";
      } else {
        alert("Veuillez remplir tous les champs !");
      }
    });
  }

  /* Inscription utilisateur */ 

  const btnInscri = document.getElementById("btnInscri")

    if (btnInscri) {
      btnInscri.addEventListener("click", function(event) {
        event.preventDefault();
      
      const pseudo = document.getElementById("pseudo").value.trim();
      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value.trim();

            if (pseudo && email && password) {
        alert("Bienvenue ! Vous disposez dès maintenant de 20 crédits, valables sur tous les covoiturages disponibles ou pour créer votre propre trajet en tant que conducteur.");
        window.location.href = "/Projet/Espace utilisateur/espace.html";

      } else {
        alert("Veuillez remplir tous les champs !");
      }
    });
}
  /* Connection utilisateur */

    const btnConect = document.getElementById("btnConect")

    if (btnConect) {
      btnConect.addEventListener("click", function(event) {
        event.preventDefault();
      
      const email2 = document.getElementById("email2").value.trim();
      const password2 = document.getElementById("password2").value.trim();

            if (email2 && password2) {
        window.location.href = "/Projet/Espace utilisateur/espace.html";

      } else {
        alert("Veuillez remplir tous les champs !");
      }
    });
}

/* Choix utiliateur */

  document.addEventListener('DOMContentLoaded', () => {
    const radios = document.querySelectorAll('input[name="user-role"]');
    const chauffeurSection = document.getElementById('chauffeur-info');

    chauffeurSection.style.display = 'none';

    radios.forEach(radio => {
      radio.addEventListener('change', () => {
        if (radio.value === 'chauffeur') {
          chauffeurSection.style.display = 'flex';
        } else {
          chauffeurSection.style.display = 'none';
        }
      });
    });
  });


  /* Informations chauffeur */

  const btnInfo = document.getElementById("btnInfo")

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
          "Animal : " + animal );

      } else {
        alert("Veuillez remplir tous les champs !");
      }
    });
}
