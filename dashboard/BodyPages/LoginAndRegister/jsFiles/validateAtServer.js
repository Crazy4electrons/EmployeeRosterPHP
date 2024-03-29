function validateAtServer(userName, Password) {
  let adminusername = document.getElementById(userName).value;
  let adminpassword = document.getElementById(Password).value;
  
  let message = {
    "AdminPassword": adminpassword,
    "AdminUsername": adminusername,
  };
  console.log(message);

  fetch('dashboard/classes/adminauth.php', {
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
          if (responseFin.UserAuth == "true") {

            let chkadmin = document.querySelector(".chckadmin");
            if (chkadmin.parentElement.tagName == "FORM") {
              let parentchkadmin = chkadmin.parentElement;
              parentchkadmin.submit();
              console.log("true on submit");
            }else{
              chkadmin.innerHTML = "false on submit: User doesn't exist";
            }
          } else {
            let chkadmin = document.querySelector(".chckadmin")
            chkadmin.innerHTML = "false";
          };
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