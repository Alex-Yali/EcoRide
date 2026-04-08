 /* Acces page detail */

const btnsDetail = document.querySelectorAll(".btn-detail");

btnsDetail.forEach((btn) => {
  btn.addEventListener("click", (event) => {
    event.preventDefault();
    const idCovoit = btn.dataset.id;
    window.location.href = `/covoiturage/detail?id=${idCovoit}`;
  });
});
