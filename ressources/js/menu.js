let openButton = document.getElementById("nav__mobile_open_menu");
let navMobileOpen = document.getElementById("nav__mobile_open");
let closeButton = document.getElementById("nav__mobile_close_menu");

openButton.addEventListener("click", function (){
    navMobileOpen.style.display = "flex";
})

closeButton.addEventListener("click", function () {
    navMobileOpen.style.display = "none";
})