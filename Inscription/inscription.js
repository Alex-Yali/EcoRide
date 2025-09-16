/* Menu déroulant */

const icon = document.querySelector(".menu-img");
const menu = document.querySelector(".menu-box");

    icon.addEventListener("click", () => {
      menu.classList.toggle("active");
    });