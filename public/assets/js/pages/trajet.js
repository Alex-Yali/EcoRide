 /* Gestion Modal */

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
