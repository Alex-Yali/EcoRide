 /* Acces page detail */

const btnsDetail = document.querySelectorAll(".btn-detail");

btnsDetail.forEach((btn) => {
  btn.addEventListener("click", (event) => {
    event.preventDefault();
    const idCovoit = btn.dataset.id;
    window.open(`/covoiturage/detail?id=${idCovoit}`, '_blank');
  });
});
