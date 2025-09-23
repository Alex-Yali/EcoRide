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