const btnInscri = document.getElementById ('btnInscri')

if (btnInscri) {
    const userData = document.getElementById('user-data');
    const pseudo = userData.dataset.pseudo;
    btnInscri.addEventListener ('click', function(event) {

    alert("Bienvenue ${pseudo} ! Vous disposez dès maintenant de 20 crédits.");

    });
}