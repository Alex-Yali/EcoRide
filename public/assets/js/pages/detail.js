
/* Affichage section validation covoiturage */

  const participe = document.querySelector(".participe");
  const valid = document.querySelector(".valid");
  const btnParticipe = document.getElementById("btnParticipe");

  if (btnParticipe) {
    btnParticipe.addEventListener("click",() =>{
      if(roleUtilisateur != "utilisateur" && roleUtilisateur != "admin" && roleUtilisateur != "employe") {
        window.location.href = "/connexion/"
      } else {
          participe.style.display = "none";  
          valid.style.display = "block";
      }
    }
  )
}

/* Acces page avis conducteur */

const btnAvis = document.querySelectorAll(".moyenne");
btnAvis.forEach((avis) => {
  avis.addEventListener("click", (event) => {
    event.preventDefault();
    const idAvis = avis.dataset.id;
    window.location.href = `/covoiturage/avis?id=${idAvis}`;
  });
});