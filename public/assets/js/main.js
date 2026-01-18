/* Menu déroulant */
 
  const menuImg = document.getElementById("menu-img");
  const menuBox = document.getElementById("menu-box");
  const menu = document.getElementById("menu");

  menuImg.addEventListener("click", () => {
    menuBox.classList.toggle("hidden");

    if (menuBox.classList.contains("hidden")) {
      menuImg.src = "./assets/images/icon compte.png";   // image quand menu fermé
    } else {
      menuImg.src = "./assets/images/icon compte up.png";  // image quand menu ouvert
    }
  });

  document.addEventListener("click", (e) => {
    if (!menu.contains(e.target)) {
      menuBox.classList.add("hidden");
      menuImg.src = "./assets/images/icon compte.png"; // on remet l’image du menu si clic en dehors
    }
  });

