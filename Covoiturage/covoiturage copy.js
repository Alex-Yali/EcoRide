  /* Affichage date covoit selectionnée 
const btnCovoit = document.getElementById("btnCovoit");

if (btnCovoit) {
  btnCovoit.addEventListener("click", function(event) {
    event.preventDefault();

    const depart = document.getElementById("depart").value.trim();
    const destination = document.getElementById("destination").value.trim();
    const date = document.getElementById("date").value.trim();
    const passager = document.getElementById("passager").value.trim();
    const result = document.getElementById("date-covoit");

    if (depart && destination && date && passager) {
      // Transformer la date
      const dateObj = new Date(date);
      const options = { weekday: "long", day: "numeric", month: "long", year: "numeric" };
      const formattedDate = dateObj.toLocaleDateString("fr-FR", options);
      const capitalized = formattedDate.charAt(0).toUpperCase() + formattedDate.slice(1);

      // Afficher les infos dans l'alerte
      alert(
        "🚗 Détails du covoiturage :" +
        "\nDépart : " + depart +
        "\nDestination : " + destination +
        "\nDate : " + date +
        "\nPassagers : " + passager
      );

      // Modifier le paragraphe avec la date choisie
      result.textContent = ` ${capitalized}`;

    } else {
      alert("⚠️ Veuillez remplir tous les champs !");
    }
  });
}*/

// Récupérer les boutons
const btnCovoit = document.getElementById("btnCovoit");
const btnNav = document.getElementById("btnNav");
const result = document.getElementById("date-covoit"); // Le paragraphe pour afficher la date

// Fonction commune pour traiter le clic
function handleCovoitClick(event) {
  event.preventDefault();

  const depart = document.getElementById("depart")?.value.trim();
  const destination = document.getElementById("destination")?.value.trim();
  const date = document.getElementById("date")?.value.trim();
  const passager = document.getElementById("passager")?.value.trim();

  if (depart && destination && date && passager) {
    // Transformer la date
    const dateObj = new Date(date);
    const options = { weekday: "long", day: "numeric", month: "long", year: "numeric" };
    const formattedDate = dateObj.toLocaleDateString("fr-FR", options);
    const capitalized = formattedDate.charAt(0).toUpperCase() + formattedDate.slice(1);

    // Afficher alerte
    alert(
      "🚗 Détails du covoiturage :" +
      "\nDépart : " + depart +
      "\nDestination : " + destination +
      "\nDate : " + capitalized +
      "\nPassagers : " + passager
    );

    // Modifier le paragraphe avec la date
    if (result) {
      window.location.href = "/Projet/Covoiturage/covoiturage.html";
      result.textContent = `${capitalized}`;
    }

  } else {
    alert("⚠️ Veuillez remplir tous les champs !");
  }
}

// Ajouter le listener si les boutons existent
if (btnCovoit) btnCovoit.addEventListener("click", handleCovoitClick);
if (btnNav) btnNav.addEventListener("click", handleCovoitClick);
