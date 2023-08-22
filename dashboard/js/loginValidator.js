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
  let message = { AdminpPassword: adminpassword, AdminUsername: adminusername };
  console.log(message);
  let xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      let response = xhr.responseText;
      if (response.trim() !== '') {
        let jsonData = JSON.parse(response);
        console.log(jsonData);
        console.log(response);
      } else { console.log('Empty response'); }
    } else { console.log('XHR request failed with status:', xhr.status); }
  }
  xhr.open('POST', '../classes/adminauth.php', true); xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhr.send('message=' + encodeURIComponent(message));
}

// Create a data object to send as the payload
//   const message = {
//     adminDo: 'auth',
//     password: answer,
//     username: 'EDS',
//   };

//   // Create the options for the fetch request
//   const request = {
//     method: "POST",
//     headers: {
//       "Content-Type": "application/json",
//     },
//     body: JSON.stringify(message),
//   };

//   // Send the POST request and receive the response
//   request.json().then((data) => {
//     console.log(data);
//   });
// }
//   fetch("../classes/adminauth.php", options)
//     .then(response => response.json)
//     .then(result => {
//      console.log(result);
//     })
//     .catch(error => {
//       console.error("Error:", error);
//     })

// }
// const obj = { hello: "world" };

// const request = new Request("/myEndpoint", {
//   method: "POST",
//   body: JSON.stringify(obj),
// });

// request.json().then((data) => {
//   // do something with the data sent in the request
// });