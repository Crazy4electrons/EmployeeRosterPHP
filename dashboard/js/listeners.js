/*
funtion is used to evaluate username and password
*/
let EventStarted = false;
function evalinput(userName="usertoFilter", userPass="PasstoFilter", dispBox="logToUser") {
    let displayoutA = document.getElementsByClassName(dispBox);
    // displayout.style.overflowWrap = "true";

    if (EventStarted) {
        chckuserName.removeEventListener("input",userPassChck());
        EventStarted = false;
    }
    let chckuserName = document.getElementsByClassName(userName);
    let chckuserPass = document.getElementsByClassName(userPass);
    chckuserName.addEventListener('input', userPassChck(chckuserName, chckuserPass,displayoutA));
    EventStarted = true;
    //filter function user name and password
    function userPassChck(userName, userPass,displayout) {
        let Nameregex = /^(?=.+[0-9])[a-zA-Z0-9]+$/;
        if (userName.length > 0) {
            if (userName.length >= 6) {
                if (Nameregex.test(userName)) {
                    displayout.style.color = "green";
                    displayout.innerHTML = "Username Valid";
                } else {
                    displayout.style.color = "Orange";
                    displayout.innerHTML = "Username must only contain alphanumericals";
                }
            } else {
                displayout.style.color = "Red";
                displayout.innerHTML = "Username must be atleast 6 characters long";
            }
        } else {
            displayout.innerHTML = "";
        }


        //Check if password matches filters
        if (displayout.value = "Username Valid") {
            let Passregex = /^(?=.*[a-zA-Z0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]+$/;
            if (userPass.length > 0) {
                if (userPass.length >= 8) {
                    if (Passregex.test(chckuserPass)) {
                        displayout.style.color = "Green";
                        displayout.innerHTML = "Password Valid";
                    } else {
                        displayout.style.color = "Orange";
                        displayout.innerHTML = "Password must contain alphanumericals and atleast one symbol";
                    }
                } else {
                    displayout.style.color = "Red";
                    displayout.innerHTML = "Password must be atleast 8 characters long";
                }
            } else {
                displayout.innerHTML = "";
            }
        }

    }

}
let popup = document.getElementsByClassName("popup");
let openBtn = document.getElementsByClassName("openbtn");
let closeBtn = document.getElementsByClassName("closebtn");
openBtn.addEventListener('click', function () {
    popup.classList.remove('nodisplay');
    popup.classList.add('visible');
});
closeBtn.addEventListener('click', function () {
    popup.classList.remove('visible');
    popup.classList.add('nodisplay');
})  