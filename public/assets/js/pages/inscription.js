 /* Affichage mot de passe en clair */
const passwordInput = document.querySelector('#password');
const togglePassword = document.querySelector('#togglePassword');
const passwordInputConfirm = document.querySelector('#password_confirm');
const togglePasswordConfirm = document.querySelector('#togglePasswordConfirm');

togglePassword.addEventListener('click', function () {
    // On bascule le type entre 'password' et 'text'
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    
    // Changer l'image
        const img = this.querySelector('img');
        img.src = type === 'password' ? '/assets/images/oeil-ouvert.png' : '/assets/images/oeil-ferme.png';
});

togglePasswordConfirm.addEventListener('click', function () {
    // On bascule le type entre 'password' et 'text'
    const type = passwordInputConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInputConfirm.setAttribute('type', type);
    
    // Changer l'image
        const img = this.querySelector('img');
        img.src = type === 'password' ? '/assets/images/oeil-ouvert.png' : '/assets/images/oeil-ferme.png';
});

 /* Barre check mot de passe */
const password = document.getElementById('password');
const strengthBar = document.getElementById('strength-bar');
const strengthText = document.getElementById('strength-text');

password.addEventListener('input', () => {
    const val = password.value;
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