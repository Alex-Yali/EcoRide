  /* Recuperation date de covoiturage */

document.addEventListener("DOMContentLoaded", () => {
  const result = document.getElementById("date-covoit"); // zone de texte modifié
  const date = localStorage.getItem("covoitDate"); // id ou est stocké la date

  if (date && result) {
    result.textContent = `${date}`;
  }
});

  /* Acces page detail */

  const btnDetail = document.getElementById("btnDetail")
  
    if (btnDetail) {
      btnDetail.addEventListener("click", function(event) {
        event.preventDefault(); 
        window.location.href = "./detail.php";
    });
}