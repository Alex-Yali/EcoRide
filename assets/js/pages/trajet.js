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
      } else {
        alert("Veuillez remplir tous les champs !");
      }
    });
  }