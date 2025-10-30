 /* Acces page detail */

  const btnDetail = document.getElementById("btnDetail")
  
    if (btnDetail) {
      btnDetail.addEventListener("click", function(event) {
        event.preventDefault(); 
        window.location.href = "./detail.php";
    });
}