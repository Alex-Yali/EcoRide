  /* Inscription utilisateur */ 

  const btnInscri = document.getElementById("btnInscri")

    if (btnInscri) {
      btnInscri.addEventListener("click", function(event) {
        event.preventDefault();
      
      const pseudo = document.getElementById("pseudo").value.trim();
      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value.trim();

            if (pseudo && email && password) {
        localStorage.setItem("pseudoUser", pseudo);
        alert("Bienvenue ! Vous disposez dès maintenant de 20 crédits, valables sur tous les covoiturages disponibles ou pour créer votre propre trajet en tant que conducteur.");
        window.location.href = "./espace.php";

      } else {
        alert("Veuillez remplir tous les champs !");
      }
    });
}
