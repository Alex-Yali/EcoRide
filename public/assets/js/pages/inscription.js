 /*Implémenter le JS de ma page*/
const inputPseudo = document.getElementById("pseudo");
const inputMail = document.getElementById("email");
const inputPassword = document.getElementById("password");
const inputPasswordConfirm = document.getElementById("password_confirm");
const btnValidation = document.getElementById("btnInscri");

inputPseudo.addEventListener("keyup", validateForm); 
inputMail.addEventListener("keyup", validateForm);
inputPassword.addEventListener("keyup", validateForm);
inputPasswordConfirm.addEventListener("keyup", validateForm);

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
        btnValidation.disabled = false;
    }
    else{
        btnValidation.disabled = true;
    }
}

 /* Affichage mot de passe en clair */
const togglePassword = document.getElementById("togglePassword");
const togglePasswordConfirm = document.getElementById("togglePasswordConfirm");

togglePassword.addEventListener('click', function () {
    // On bascule le type entre 'password' et 'text'
    const type = inputPassword.getAttribute('type') === 'password' ? 'text' : 'password';
    inputPassword.setAttribute('type', type);
    
    // Changer l'image
        const img = this.querySelector('img');
        img.src = type === 'password' ? '/assets/images/oeil-ouvert.png' : '/assets/images/oeil-ferme.png';
});

togglePasswordConfirm.addEventListener('click', function () {
    // On bascule le type entre 'password' et 'text'
    const type = inputPasswordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
    inputPasswordConfirm.setAttribute('type', type);
    
    // Changer l'image
        const img = this.querySelector('img');
        img.src = type === 'password' ? '/assets/images/oeil-ouvert.png' : '/assets/images/oeil-ferme.png';
});

 /* Barre check mot de passe */
const strengthBar = document.getElementById('strength-bar');
const strengthText = document.getElementById('strength-text');

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
