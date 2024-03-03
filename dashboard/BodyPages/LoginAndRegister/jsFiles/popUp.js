let popup = document.querySelector(".popUp");
let openBtn = document.querySelector(".openBtn");
let closeBtn = document.querySelector(".closeBtn");

openBtn.addEventListener("click", function () {
  popup.classList.remove("nodisplay");
  popup.classList.add("visible");
});

closeBtn.addEventListener("click", function () {
  popup.classList.remove("visible");
  popup.classList.add("nodisplay");
});