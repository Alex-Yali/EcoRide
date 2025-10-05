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


/* Barre de recherche onglet accueil, rechercher et covoiturage */ 

const btnCovoit = document.getElementById("btnCovoit");
const btnNav = document.getElementById("btnNav");

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
    const dateMaj = formattedDate.charAt(0).toUpperCase() + formattedDate.slice(1);

    // Sauvegarder la date dans localStorage avant la redirection
    localStorage.setItem("covoitDate", dateMaj);

    alert(
          "Départ : " + depart + "\n" +
          "Destination : " + destination + "\n" +
          "Date : " + date + "\n" +
          "Passager: " + passager );

    // Redirection vers covoiturage.html
    window.location.href = "/Projet/Covoiturage/covoiturage.html";

  } else {
    alert("⚠️ Veuillez remplir tous les champs !");
  }
}

if (btnCovoit) btnCovoit.addEventListener("click", handleCovoitClick);
if (btnNav) btnNav.addEventListener("click", handleCovoitClick);
