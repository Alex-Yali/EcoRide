 /* ============================= Dynamiser Connexion ============================= */

const inputMail = document.getElementById("email");
const inputPassword = document.getElementById("password");
const btnValidation = document.getElementById("btnConect");
 
inputMail.addEventListener("keyup", validateForm);
inputPassword.addEventListener("keyup", validateForm);

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

function validateForm(){
    const mailOk = validateMail(inputMail);
    const passwordOk = validatePassword(inputPassword);

    if(mailOk && passwordOk){
        btnValidation.disabled = false;
    }
    else{
        btnValidation.disabled = true;
    }
}

 /* ============================= Affichage mot de passe en clair ============================= */

const passwordInput = document.querySelector('#password');
const togglePassword = document.querySelector('#togglePassword');

togglePassword.addEventListener('click', function () {
    // On bascule le type entre 'password' et 'text'
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    
    // Changer l'image
        const img = this.querySelector('img');
        img.src = type === 'password' ? '/assets/images/oeil-ouvert.png' : '/assets/images/oeil-ferme.png';
});

 /* ============================= Fonction Async ============================= */

btnValidation.addEventListener("click", ConnecterUtlisateur);

async function ConnecterUtlisateur() {
    const email = inputMail.value;
    const password = inputPassword.value;
    const csrf = document.querySelector('input[name="csrf_token"]').value;

    try {
        const response = await fetch("/connexion/", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest"
            },
            body: JSON.stringify({ email, password, csrf_token: csrf })
        });

        const result = await response.json();

        if (result.success) {
            window.location.href = "/espace/";
        } else {
            let erreur = document.querySelector(".errorMessage");
            if (!erreur) {
                erreur = document.createElement("p");
                erreur.className = "errorMessage";
                document.getElementById("formulaire").appendChild(erreur);
            }
            erreur.textContent = result.message;
        }
    } catch (error) {
        console.error("Erreur réseau :", error);
    }
}