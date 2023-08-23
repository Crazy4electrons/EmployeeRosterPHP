function validateAndSubmit() {
  let inputName = document.getElementById("Lusername").value;
  let inputPass = document.getElementById("Lpassword").value;
  let inputPasslength = inputPass.length;
  let inputNamelength = inputPass.length;
  let regex = /^[a-zA-Z_]+$/;
  if (inputNamelength >= 6) {
    if (regex.test(inputName)) {
      if (inputPasslength >= 8) {
        document.getElementById("Lform").submit();
      } else {
        alert("password should be atleast 8 long.");
      }
    } else {
      alert("Invalid username and password combination!!");
    }
  } else {
    alert("name should atleast be 6 characters long.");
  }
}
//popup for admin auth
function checkwithadmin() {
  let popup = document.getElementById("adminpopup");
  popup.style.display = "flex";
}
//reponse meassage
function displayMessage(message) {
  var chat = document.getElementById('chat');
  chat.innerHTML += '<p><strong>User:</strong> ' + message + '</p>';
}
//
function displayResponse(response) {
  var chat = document.getElementById('chat');
  chat.innerHTML += '<p><strong>Bot:</strong> ' + response + '</p>';
}

function sendMessage() {
  let adminusername = document.getElementById('adminusername').value;
  let adminpassword = document.getElementById('adminpassword').value;
  let message = { "AdminpPassword": adminpassword, "AdminUsername": adminusername };
  console.log(message);

  fetch('../classes/adminauth.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: 'message=' + encodeURIComponent(JSON.stringify(message))
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
    } else {
      console.log('Empty response');
      console.log(response.url);
    }
  })
  .catch(error => {
    console.log(error.message);
  });
}