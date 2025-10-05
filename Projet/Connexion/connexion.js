  /* Connection utilisateur */

    const btnConect = document.getElementById("btnConect")

    if (btnConect) {
      btnConect.addEventListener("click", function(event) {
        event.preventDefault();
      
      const email2 = document.getElementById("email2").value.trim();
      const password2 = document.getElementById("password2").value.trim();

            if (email2 && password2) {
        window.location.href = "/Projet/Espace utilisateur/espace.html";

      } else {
        alert("Veuillez remplir tous les champs !");
      }
    });
}