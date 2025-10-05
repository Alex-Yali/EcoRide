document.addEventListener("DOMContentLoaded", () => {
  const result = document.getElementById("date-covoit"); // zone de texte modifié
  const date = localStorage.getItem("covoitDate"); // id ou est stocké la date

  if (date && result) {
    result.textContent = `${date}`;
  }
});