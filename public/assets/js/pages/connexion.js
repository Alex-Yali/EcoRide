/* Affichage mot de passe en clair */
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