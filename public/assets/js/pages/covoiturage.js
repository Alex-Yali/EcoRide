 /* Acces page detail */

const btnsDetail = document.querySelectorAll(".btn-detail");

btnsDetail.forEach((btn) => {
  btn.addEventListener("click", (event) => {
    event.preventDefault();
    const idCovoit = btn.dataset.id;
    window.open(`./detail.php?id=${idCovoit}`, "_blank"); // Ouvre la page de détails dans un nouvel onglet
  });
});
