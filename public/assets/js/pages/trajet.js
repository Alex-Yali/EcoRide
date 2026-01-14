/* Fermeture après ajout voiture */

document.addEventListener("DOMContentLoaded", () => {
    const close = document.querySelector(".close");

    if (close) {
        close.addEventListener("click", (e) => {
            e.preventDefault();

            // 1) Fermer le modal 
            window.location.hash = "";

            // 2) Recharger la page
            setTimeout(() => {
                window.location.reload();
            }, 10);
        });
    }
});
