let EventStarted = false;

function evalinput(userName = ".UserToFilter", userPass = ".PassToFilter", dispBox = ".LogToUser") {
  if (EventStarted) {
    let chckuserPass = document.querySelector(userPass);
    let chckuserName = document.querySelector(userName);
    chckuserPass.removeEventListener("input", checkPasswordValidity);
    chckuserName.removeEventListener("input", checkUsernameValidity);
    EventStarted = false;
  }

  let displayout = document.querySelector(dispBox);
  displayout.style.display ="none"
  let displayoutValidPass = null;
  function checkUsernameValidity() { 
    let Nameregex = /^(?=.+[0-9])[a-zA-Z0-9]+$/;
    if (chckuserName.value.length > 0) {
      displayout.style.display = "block"
      if (chckuserName.value.length >= 6) {
        if (Nameregex.test(chckuserName.value)) {
          displayout.style.color = "green";
          displayout.innerHTML = "Username Valid";
          displayoutValidPass =displayout.innerHTML;
        } else {
          displayout.style.color = "orange";
          displayout.innerHTML = "Username must only contain alphanumericals";
        }
      } else {
        displayout.style.color = "red";
        displayout.innerHTML = "Username must be at least 6 characters long";
      }
    } else {
      displayout.style.display = "None"
      displayout.innerHTML = "";
      displayoutValidPass =displayout.innerHTML;
    }
  }

  function checkPasswordValidity() {
    if (displayoutValidPass == "Username Valid") {
      let Passregex = /^(?=.*[a-zA-Z0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]+$/;
      if (chckuserPass.value.length > 0) {
        if (chckuserPass.value.length >= 8) {
          if (Passregex.test(chckuserPass.value)) {
            displayout.style.color = "green";
            displayout.innerHTML = "Password Valid";
          } else {
            displayout.style.color = "orange";
            displayout.innerHTML = "Password must contain alphanumericals and at least one symbol";
          }
        } else {
          displayout.style.color = "red";
          displayout.innerHTML = "Password must be at least 8 characters long";
        }
      } else {
        displayout.style.color = "green";
        displayout.innerHTML = displayoutValidPass;
      }
    }
  }

  let chckuserName = document.querySelector(userName);
  chckuserName.addEventListener("input", checkUsernameValidity);

  let chckuserPass = document.querySelector(userPass);
  chckuserPass.addEventListener("input", checkPasswordValidity);

  EventStarted = true;
}

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

