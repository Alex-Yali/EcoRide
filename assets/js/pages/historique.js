const avisCovoit = document.querySelectorAll(".avis-covoit");

// On récupère tous les formulaires
document.querySelectorAll(".formAvis").forEach(form => {
  const formAvisCovoit = form.querySelector(".formAvisCovoit");
  const avisSection = form.querySelector(".avis");
  const btnValider = form.querySelector("#btnValider");
  const hiddenAvis = form.querySelector(".hidden-avis");

  // Étape 1 
  if (btnValider) {
    btnValider.addEventListener("click", () => {
      const checkedRadio = form.querySelector('input[name="avis"]:checked');

      if (!checkedRadio) {
        alert("Veuillez sélectionner un avis avant de continuer.");
        return;
      }

      if (hiddenAvis) hiddenAvis.value = checkedRadio.value;

      formAvisCovoit.style.display = "none";
      avisSection.style.display = "flex";
    });
  }

  // Étape 2
  form.addEventListener("submit", (e) => {

    alert("Merci pour votre avis !");

  });
});
