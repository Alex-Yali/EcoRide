
/* Affichage section validation covoiturage */

  const participe = document.querySelector(".participe");
  const valid = document.querySelector(".valid");
  const btnParticipe = document.getElementById("btnParticipe");

  if (btnParticipe) {
    btnParticipe.addEventListener("click",() =>{
      if(roleUtilisateur != "utilisateur" && roleUtilisateur != "admin" && roleUtilisateur != "employe") {
        window.location.href = "./connexion.php"
      } else {
          participe.style.display = "none";  // Cache section 1
          valid.style.display = "block"; // Affiche section 2
      }
    }
  )
}

/* Acces page avis conducteur */

const btnAvis = document.querySelectorAll(".note");
btnAvis.forEach((avis) => {
  avis.addEventListener("click", (event) => {
    event.preventDefault();
    const idAvis = avis.dataset.id;
    window.open(`./avis.php?id=${idAvis}`, "_blank"); // Ouvre la page de détails dans un nouvel onglet
  });
});