 /* Menu déroulant */
  const menuImg = document.getElementById("menu-img");
  const menuBox = document.getElementById("menu-box");
  const menu = document.getElementById("menu");

  menuImg.addEventListener("click", () => {
    menuBox.classList.toggle("hidden"); 
  });
  document.addEventListener("click", (e) => {
    if (!menu.contains(e.target)) {
      menuBox.classList.add("hidden");
    }
  })

/* Barre de recherche */ 
    const btn = document.getElementById("btn");

      if (btn) {
        btn.addEventListener("click", function(event) {
          event.preventDefault();

        const depart = document.getElementById("depart").value.trim();
        const destination = document.getElementById("destination").value.trim();
        const date = document.getElementById("date").value.trim();
        const passager = document.getElementById("passager").value.trim();

        if (depart && destination && date && passager) {
          alert("Départ : " + depart + "\nDestination : " + destination + "\nDate : " + date + "\nPassagers : " + passager);
        } else {
          alert("Veuillez remplir tous les champs !");
        }
      });
    }

/* Proposer trajet */ 

  const btn2 = document.getElementById("btn2");

    if (btn2) {
      btn2.addEventListener("click", function(event) {
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
          "Véhicule : " + cars2
        );
      } else {
        alert("Veuillez remplir tous les champs !");
      }
    });
  }

  /* Inscription utilisateur */ 

  const btn3 = document.getElementById("btn3")

    if (btn3) {
      btn3.addEventListener("click", function(event) {
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

    const btn4 = document.getElementById("btn4")

    if (btn4) {
      btn4.addEventListener("click", function(event) {
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

/* Choix onglet espace utiliateur */

document.addEventListener("DOMContentLoaded", function() {
    const sections = document.querySelectorAll(".user-profil, .user-compte, .user-pref, .user-notif");

    function showSection(sectionClass) {
        sections.forEach(sec => sec.style.display = "none");
        const target = document.querySelector("." + sectionClass);
        if (target) target.style.display = "block";
    }

    // Navigation
    document.querySelectorAll(".profil").forEach(el =>
        el.addEventListener("click", () => showSection("user-profil"))
    );
    document.querySelectorAll(".compte").forEach(el =>
        el.addEventListener("click", () => showSection("user-compte"))
    );
    document.querySelectorAll(".pref").forEach(el =>
        el.addEventListener("click", () => showSection("user-pref"))
    );
    document.querySelectorAll(".notif").forEach(el =>
        el.addEventListener("click", () => showSection("user-notif"))
    );

    // Affiche le profil par défaut
    showSection("user-profil");
});

