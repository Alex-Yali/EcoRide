const formAvis = document.querySelector(".formAvisCovoit");
const avis = document.querySelector(".avis");
const btnValider = document.getElementById("btnValider");
const form = document.querySelector(".formAvis");

if (btnValider) {
  btnValider.addEventListener("click", () => {
    formAvis.style.display = "none";  // cache étape 1
    avis.style.display = "flex";     // affiche étape 2
  });
}

if (form) {
  form.addEventListener("submit", (e) => {
    e.preventDefault(); // empêche le rechargement

    // Masquer les sections
    formAvis.style.display = "none";
    avis.style.display = "none";
    form.style.display = "none";

    // Message de confirmation
    alert("Merci pour votre avis !");

    // Optionnel : envoyer les données via fetch si tu veux les stocker sans recharger
    // fetch(form.action || '', { method: 'POST', body: new FormData(form) });
  });
}