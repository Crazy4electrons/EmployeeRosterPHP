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
        alert("password should be atleast 8 long.")
      }
    } else {
      alert("Invalid username and password combination!!");
    }
  } else {
    alert("name should atleast be 6 characters long.")
  }
}

function checkwithadmin() {
  let answer = prompt("Please ask admin to enter password");
  // Create a data object to send as the payload
  const message = {
    adminDo: 'auth',
    password: answer,
    username: 'EDS',
  };

  // Create the options for the fetch request
  const request = {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(message),
  };

  // Send the POST request and receive the response
  request.json().then((data) => {
    console.log(data);
  });
}
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