/* ============================= Dynamiser les formulaires ============================= */
const inputPseudo = document.getElementById("pseudo");
const inputMail = document.getElementById("email");
const inputPassword = document.getElementById("password");
const inputPasswordConfirm = document.getElementById("password_confirm");
const btnValidInscri = document.getElementById("btnCompte");

if (inputPseudo) {
    inputPseudo.addEventListener("keyup", validateForm);
}

if (inputMail) {
    inputMail.addEventListener("keyup", validateForm);
}

if (inputPassword) {
    inputPassword.addEventListener("keyup", validateForm);
}

if (inputPasswordConfirm) {
    inputPasswordConfirm.addEventListener("keyup", validateForm);
}

function validatePseudo(input){
    const error = input.parentElement.querySelector(".error");

    if(input.value.trim() === ''){
        input.classList.remove("is-valid","is-invalid");
        if(error) error.style.display = "none";
        return false;
    }
    if(input.value.length > 10){
        input.classList.remove("is-valid");
        input.classList.add("is-invalid");
        if(error) error.style.display = "block";
        return false;
    }
    else {
        input.classList.add("is-valid");
        input.classList.remove("is-invalid");
        if(error) error.style.display = "none";
        return true;
    }
}

function validateMail(input){
    const error = input.parentElement.querySelector(".error");

    if(input.value.trim() === ''){
        input.classList.remove("is-valid","is-invalid");
        if(error) error.style.display = "none";
        return false;
    }
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(emailRegex.test(input.value)){
        input.classList.add("is-valid");
        input.classList.remove("is-invalid");
        if(error) error.style.display = "none";
        return true;
    }
    else {
        input.classList.remove("is-valid");
        input.classList.add("is-invalid");
        if(error) error.style.display = "block";
        return false;
    }
}

function validatePassword(input){
    const error = input.parentElement.querySelector(".error");

    if(input.value.trim() === ''){
        input.classList.remove("is-valid","is-invalid");
        if(error) error.style.display = "none";
        return false;
    }
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{9,}$/;
    const passwordUser = input.value;

    if(passwordUser.match(passwordRegex)){
        input.classList.add("is-valid");
        input.classList.remove("is-invalid");
        if(error) error.style.display = "none";
        return true;
    }
    else {
        input.classList.remove("is-valid");
        input.classList.add("is-invalid");
        if(error) error.style.display = "block";
        return false;
    }
}

function validateConfirmationPassword(inputPassword, inputPasswordConfirm){
    const error = inputPasswordConfirm.parentElement.querySelector(".error");

    if(inputPasswordConfirm.value.trim() === ''){
        inputPasswordConfirm.classList.remove("is-valid","is-invalid");
        if(error) error.style.display = "none";
        return false;
    }
    if(inputPassword.value === inputPasswordConfirm.value){
        inputPasswordConfirm.classList.add("is-valid");
        inputPasswordConfirm.classList.remove("is-invalid");
        if(error) error.style.display = "none";
        return true;
    }
    else {
        inputPasswordConfirm.classList.remove("is-valid");
        inputPasswordConfirm.classList.add("is-invalid");
        if(error) error.style.display = "block";
        return false;
    }
}

//Function permettant de valider tout le formulaire
function validateForm(){
    const pseudoOk = validatePseudo(inputPseudo);
    const mailOk = validateMail(inputMail);
    const passwordOk = validatePassword(inputPassword);
    const passwordConfirmOk = validateConfirmationPassword (inputPassword, inputPasswordConfirm);

    if(pseudoOk && mailOk && passwordOk && passwordConfirmOk){
        btnValidInscri.disabled = false;
    }
    else{
        btnValidInscri.disabled = true;
    }
}

/* ============================= Affichage mot de passe en clair ============================= */
const togglePassword = document.getElementById("togglePassword");
const togglePasswordConfirm = document.getElementById("togglePasswordConfirm");

if (togglePassword) {
    togglePassword.addEventListener('click', function () {
        // On bascule le type entre 'password' et 'text'
        const type = inputPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        inputPassword.setAttribute('type', type);
        
        // Changer l'image
            const img = this.querySelector('img');
            img.src = type === 'password' ? '/assets/images/oeil-ouvert.png' : '/assets/images/oeil-ferme.png';
    });
}

if (togglePasswordConfirm) {
    togglePasswordConfirm.addEventListener('click', function () {
        // On bascule le type entre 'password' et 'text'
        const type = inputPasswordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
        inputPasswordConfirm.setAttribute('type', type);
        
        // Changer l'image
            const img = this.querySelector('img');
            img.src = type === 'password' ? '/assets/images/oeil-ouvert.png' : '/assets/images/oeil-ferme.png';
    });
}

/* ============================= Barre check mot de passe ============================= */
const strengthBar = document.getElementById('strength-bar');
const strengthText = document.getElementById('strength-text');

if (inputPassword) {
    inputPassword.addEventListener('input', () => {
        const val =  inputPassword.value;
        let score = 0;

        if (val.length >= 9) score++; 
        if (/[a-z]/.test(val)) score++;
        if (/[A-Z]/.test(val)) score++; 
        if (/[0-9]/.test(val)) score++; 
        if (/[^A-Za-z0-9]/.test(val)) score++; 

        strengthBar.className = "strength-bar"; 
        
    if (val.length === 0) {
            strengthText.textContent = "";
            strengthBar.style.width = "0%";
        } else if (score <= 2) {
            strengthBar.classList.add('weak');
            strengthText.textContent = "Faible 🔴";
            strengthBar.style.width = "33%";
        } else if (score <= 4) {
            strengthBar.classList.add('medium');
            strengthText.textContent = "Moyen 🟠";
            strengthBar.style.width = "66%";
        } else {
            strengthBar.classList.add('strong');
            strengthText.textContent = "Fort 🟢";
            strengthBar.style.width = "100%";
        }
    })
}

/* ============================= Gestion Modal ============================= */
const modal = document.getElementById('modal');

if (modal) {

    const btnClose = modal.querySelector('.close');

    // Ouvrir modal
    if (window.location.hash === '#modal') {
        modal.classList.add('active');
    }

    // Fermer bouton
    if (btnClose) {
        btnClose.addEventListener('click', function(e) {
            e.preventDefault();
            closeModal();
        });
    }

    // Fermer en dehors
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Retirer #modal de l'URL après fermeture
    function closeModal() {
        modal.classList.remove('active');
        history.replaceState(null, null, window.location.pathname);
    }
}

/* ============================= Gestion clic ============================= */
document.addEventListener('DOMContentLoaded', () => {

    const menus = document.querySelectorAll('.user-menu');

    menus.forEach(menu => {
        const menuBtns = menu.querySelectorAll('.menu-btn');
        const tabContents = menu.querySelectorAll('.content-tab');

        if (!menuBtns.length || !tabContents.length) return;

        menuBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const target = btn.dataset.tab;

                menuBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                tabContents.forEach(content => {
                    content.style.display =
                        content.dataset.tabContent === target ? 'block' : 'none';
                });
            });
        });

        const firstBtn = menu.querySelector('.menu-btn');
        if (firstBtn) firstBtn.click();
    });

});

/* ============================= Dynamiser Modal Voiture ============================= */

const inputImmat = document.getElementById("immatriculation");
const inputDateImmat = document.getElementById("dateImmat");
const inputMarque = document.getElementById("marque");
const inputModele = document.getElementById("modele");
const inputCouleur = document.getElementById("couleur");
const inputPlace = document.getElementById("place");
const inputEnergie = document.getElementById("energie");
const btnValidVoiture = document.getElementById("btnInfo");

inputImmat.addEventListener("keyup", validateVoiture); 
inputDateImmat.addEventListener("keyup", validateVoiture); 
inputMarque.addEventListener("keyup", validateVoiture); 
inputModele.addEventListener("keyup", validateVoiture); 
inputCouleur.addEventListener("keyup", validateVoiture); 
inputPlace.addEventListener("input", validateVoiture); 
inputEnergie.addEventListener("input", validateVoiture); 

function validateImmat(input){
    const error = input.parentElement.querySelector(".error");

    if(input.value.trim() === ''){
        input.classList.remove("is-valid","is-invalid");
        if(error) error.style.display = "none";
        return false;
    }
    const immatRegex = /^[A-Z]{2}-[0-9]{3}-[A-Z]{2}$/;
    if(immatRegex.test(input.value)){
        input.classList.add("is-valid");
        input.classList.remove("is-invalid");
        if(error) error.style.display = "none";
        return true;
    }
    else {
        input.classList.remove("is-valid");
        input.classList.add("is-invalid");
        if(error) error.style.display = "block";
        return false;
    }
}

function validateDateImmat(input){
    const error = input.parentElement.querySelector(".error");

    if(input.value.trim() === ''){
        input.classList.remove("is-valid","is-invalid");
        if(error) error.style.display = "none";
        return false;
    }
    const dateImmatRegex = /^(0?[1-9]|[12][0-9]|3[01]) (janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre) [0-9]{4}$/;
    if(dateImmatRegex.test(input.value)){
        input.classList.add("is-valid");
        input.classList.remove("is-invalid");
        if(error) error.style.display = "none";
        return true;
    }
    else {
        input.classList.remove("is-valid");
        input.classList.add("is-invalid");
        if(error) error.style.display = "block";
        return false;
    }
}

function validateMarque(input){
    const error = input.parentElement.querySelector(".error");

    if(input.value.trim() === ''){
        input.classList.remove("is-valid","is-invalid");
        if(error) error.style.display = "none";
        return false;
    }
    if(input.value.length > 15){
        input.classList.remove("is-valid");
        input.classList.add("is-invalid");
        if(error) error.style.display = "block";
        return false;
    }
    else {
        input.classList.add("is-valid");
        input.classList.remove("is-invalid");
        if(error) error.style.display = "none";
        return true;
    }
}

function validateModele(input){
    const error = input.parentElement.querySelector(".error");

    if(input.value.trim() === ''){
        input.classList.remove("is-valid","is-invalid");
        if(error) error.style.display = "none";
        return false;
    }
    if(input.value.length > 20){
        input.classList.remove("is-valid");
        input.classList.add("is-invalid");
        if(error) error.style.display = "block";
        return false;
    }
    else {
        input.classList.add("is-valid");
        input.classList.remove("is-invalid");
        if(error) error.style.display = "none";
        return true;
    }
}

function validateCouleur(input){
    const error = input.parentElement.querySelector(".error");

    if(input.value.trim() === ''){
        input.classList.remove("is-valid","is-invalid");
        if(error) error.style.display = "none";
        return false;
    }
    if(input.value.length > 15){
        input.classList.remove("is-valid");
        input.classList.add("is-invalid");
        if(error) error.style.display = "block";
        return false;
    }
    else {
        input.classList.add("is-valid");
        input.classList.remove("is-invalid");
        if(error) error.style.display = "none";
        return true;
    }
}

function validatePlace(input){
    const error = input.parentElement.querySelector(".error");
    const value = parseInt(input.value, 10);

    if(input.value.trim() === ""){
        input.classList.remove("is-valid","is-invalid");
        if(error) error.style.display = "none";
        return false;
    }

    if(isNaN(value) || value < 1 || value > 4){
        input.classList.remove("is-valid","is-invalid");
        input.classList.add("is-invalid");
        if(error) error.style.display = "block";
        return false;
    }
    else {
        input.classList.add("is-valid");
        input.classList.remove("is-invalid");
        if(error) error.style.display = "none";
        return true;
    }
}

function validateEnergie(input){
    const error = input.parentElement.querySelector(".error");
    const validOptions = ["Essence","Diesel","Électrique"];

    if(input.value.trim() === ""){
        input.classList.remove("is-valid","is-invalid");
        if(error) error.style.display = "none";
        return false;
    }

    if(!validOptions.includes(input.value)){
        input.classList.remove("is-valid","is-invalid");
        input.classList.add("is-invalid");
        if(error) error.style.display = "block";
        return false;
    }
    else {
        input.classList.add("is-valid");
        input.classList.remove("is-invalid");
        if(error) error.style.display = "none";
        return true;
    }
}

//Function permettant de valider tout le formulaire
function validateVoiture(){
    const immatOk = validateImmat(inputImmat);
    const dateImmatOk = validateDateImmat(inputDateImmat);
    const marquedOk = validateMarque(inputMarque);
    const modeleOk = validateModele (inputModele);
    const couleurOk = validateCouleur (inputCouleur);
    const placeOk = validatePlace (inputPlace);
    const energieOk = validateEnergie (inputEnergie);

    if(immatOk && dateImmatOk && marquedOk && modeleOk && couleurOk && placeOk && energieOk){
        btnValidVoiture.disabled = false;
    }
    else{
        btnValidVoiture.disabled = true;
    }
}
