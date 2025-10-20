/* Recuperation date de covoiturage */

document.addEventListener("DOMContentLoaded", () => {
  const result = document.getElementById("date-covoit"); // zone de texte modifié
  const date = localStorage.getItem("covoitDate"); // id ou est stocké la date

  if (date && result) {
    result.textContent = `${date}`;
  }
});

/* Affichage notiv validation covoiturage */

  const participe = document.querySelector(".participe");
  const valid = document.querySelector(".valid");
  const btnReserve = document.getElementById("btnReserve");

  if (btnReserve) {
    btnReserve.addEventListener("click",() =>{
      if(!isConect) {
        window.location.href = "./connexion.php"
      } else {
          participe.style.display = "none";  // Cache section 1
          valid.style.display = "block"; // Affiche section 2
      }
    }
  )
}