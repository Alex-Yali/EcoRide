 /* ============================= Dynamiser Ajout Trajet ============================= */

const inputDepart = document.getElementById("depart2");
const inputDestination = document.getElementById("destination2")
const inputPlaceTrajet = document.getElementById("places2");
const inputPrix = document.getElementById("prix2");
const btnValidTrajet = document.getElementById("btnTrajet");

inputDepart.addEventListener("keyup", validateTrajet); 
inputDestination.addEventListener("keyup", validateTrajet); 
inputPlaceTrajet.addEventListener("input", validateTrajet); 
inputPrix.addEventListener("input", validateTrajet); 

function validateDepart(input){
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

function validateDestination(input){
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

function validatePlaceTrajet(input){
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

function validatePrix(input){
    const error = input.parentElement.querySelector(".error");
    const value = parseInt(input.value, 10);
    
    if(input.value.trim() === ""){
        input.classList.remove("is-valid","is-invalid");
        if(error) error.style.display = "none";
        return false;
    }

    if(isNaN(value) || value < 1 || value > 20){
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

function validateTrajet(){
    const departOk = validateDepart(inputDepart);
    const destinationOk = validateDestination(inputDestination);
    const placeTrajetOk = validatePlaceTrajet(inputPlaceTrajet);
    const prixOk = validatePrix(inputPrix);

    if(departOk && destinationOk && placeTrajetOk && prixOk){
        btnValidTrajet.disabled = false;
    }
    else{
        btnValidTrajet.disabled = true;
    }
}
 
 /* ============================= Modal Voiture ============================= */

const ajoutModal = document.getElementById('ajoutVoiture');
const openAjoutBtn = document.getElementById('openAjoutModal');
const closeButtons = document.querySelectorAll('.close');

// Ouvrir modal ajout
openAjoutBtn.addEventListener('click', () => {
    ajoutModal.classList.add('active');
});

// Fermer
closeButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        const modalId = btn.getAttribute('data-modal');
        document.getElementById(modalId).classList.remove('active');
    });
});

// Fermer en cliquant dehors
window.addEventListener('click', (e) => {
    if (e.target === ajoutModal) {
        ajoutModal.classList.remove('active');
    }
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

inputImmat.addEventListener("keyup", validateForm); 
inputDateImmat.addEventListener("keyup", validateForm); 
inputMarque.addEventListener("keyup", validateForm); 
inputModele.addEventListener("keyup", validateForm); 
inputCouleur.addEventListener("keyup", validateForm); 
inputPlace.addEventListener("input", validateForm); 
inputEnergie.addEventListener("input", validateForm); 

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
function validateForm(){
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

 