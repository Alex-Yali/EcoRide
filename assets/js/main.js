/* Menu déroulant */
 
  const menuImg = document.getElementById("menu-img");
  const menuBox = document.getElementById("menu-box");
  const menu = document.getElementById("menu");

  menuImg.addEventListener("click", () => {
    menuBox.classList.toggle("hidden");

    if (menuBox.classList.contains("hidden")) {
      menuImg.src = "./assets/images/icon compte.png";   // image quand menu fermé
    } else {
      menuImg.src = "./assets/images/icon compte up.png";  // image quand menu ouvert
    }
  });

  document.addEventListener("click", (e) => {
    if (!menu.contains(e.target)) {
      menuBox.classList.add("hidden");
      menuImg.src = "./assets/images/icon compte.png"; // on remet l’image du menu si clic en dehors
    }
  });

/* Afficher date du covoiturage  

const btnNav = document.getElementById("btnNav");

function handleCovoitClick(event) {
  event.preventDefault();

  const date = document.getElementById("date")?.value.trim();

  if (date) {
    
    const dateObj = new Date(date);
    / Formater la date en français
    const options = { weekday: "long", day: "numeric", month: "long", year: "numeric" };
    const formattedDate = dateObj.toLocaleDateString("fr-FR", options);
    // Majuscule sur la première lettre
    const dateMaj = formattedDate.charAt(0).toUpperCase() + formattedDate.slice(1);

    // Sauvegarder la date dans localStorage avant la redirection
    localStorage.setItem("covoitDate", dateMaj);
  } 
}

if (btnNav) btnNav.addEventListener("click", handleCovoitClick);*/
