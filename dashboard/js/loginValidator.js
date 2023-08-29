/*
funtion is used to evaluate username and password
 */
function evalinput(userName, userPass, dispBox, newEvent=false) {
  if (newEvent) {
    chckuserName.removeEventListener("input");
    chckuserPass.removeEventListener("input");
  }else{
    return;
  }
  //filter user name
  let chckuserName = document.getElementById(userName);
  let chckuserPass = document.getElementById(userPass);
  let displayout = document.getElementById(dispBox);
  displayout.style.overflowWrap = true;
  chckuserName.addEventListener('input', function () {
    let Nameregex = /^(?=.+[0-9])[a-zA-Z0-9]+$/;
    if (chckuserName.value.length > 0) {
      if (chckuserName.value.length >= 6) {
        if (Nameregex.test(chckuserName.value)) {
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

  });

  //Check if password matches filters
  chckuserPass.addEventListener('input', function () {
    if (displayout.value = "Username Valid") {
      let Passregex = /^(?=.*[a-zA-Z0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]+$/;
      if (chckuserPass.value.length > 0) {
        if (chckuserPass.value.length >= 8) {
          if (Passregex.test(chckuserPass.value)) {
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
  });
}
function validateAtServer(DivForm, userName, Password, authAdmin = false) {
  let adminusername = document.getElementById(userName).value;
  let adminpassword = document.getElementById(Password).value;
  let message = {
    "AdminAuth": authAdmin,
    "AdminPass": adminpassword,
    "AdminUsername": adminusername,
    "formName": form
  };
  console.log(message);

  fetch('../classes/adminauth.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: 'data=' + encodeURIComponent(JSON.stringify(message))
  })
    .then(response => {
      if (response.ok) {
        return response.text();
      } else {
        throw new Error('XHR request failed with status: ' + response.status);
      }
    })
    
    .then(responseText => {
      if (responseText.trim() !== '') {
        console.log(responseText);
        try {
          let responseFin = JSON.parse(responseText);
          console.dir(responseFin);
          if (authAdmin == true) {
            if (responseFin.AuthAdmin == true) {
              subMit = document.getElementById(form);
              subMit.submit;
            } else {
              let attachmsg = document.getElementById(Password);
              attachmsg.insertAdjacentHTML('afterend',
                '<div>Athentication false</div>'
              );
            }
          } else {
            if (responseFin.UserAuth == true) {
              document.getElementById(form).submit;
            } else {
              let attachmsg = document.getElementById(Password);
              attachmsg.insertAdjacentHTML('afterend',
                '<div>Athentication false</div>'
              );
            }
          }
        } catch (error) {
          console.error('An error has occurred:', error);
        }
      } else {
        console.log('Empty response Received');
        console.log(response.url);
      }
    })
    .catch(error => {
      console.log(error.message);
    });
}