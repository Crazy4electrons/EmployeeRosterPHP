evalinput('Lusername', 'Lpassword','notice');
let popup = document.getElementById("adminpopup");
let openBtn = document.getElementById("openbtn");
let closeBtn = document.getElementById("closebtn");
openBtn.addEventListener('click', function () {
    popup.classList.remove('nodisplay');
    popup.classList.add('visible');
});
closeBtn.addEventListener('click', function () {
    popup.classList.remove('visible');
    popup.classList.add('nodisplay');
})  