/* Barre de recherche */
document.getElementById("searchForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const depart = document.getElementById("depart").value;
    const destination = document.getElementById("destination").value;
    const date = document.getElementById("date").value;
    const passagers = document.getElementById("passagers").value;

    if (depart && destination && date && passagers) {
        alert(`Recherche : ${depart} ➝ ${destination} le ${date} pour ${passagers} passager(s)`);
    } 
    else {
        alert("Merci de remplir toutes les zones");
      }
});

/* Menu déroulant */

const icon = document.querySelector(".menu-img");
const menu = document.querySelector(".menu-box");

    icon.addEventListener("click", () => {
      menu.classList.toggle("active");
    });