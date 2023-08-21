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
  const data = {
    password: answer,
  };

  // Create the options for the fetch request
  const options = {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
  };

  // Send the POST request and receive the response
  fetch("../classes/adminauth.php", options)
    .then(response => response.json())
    .then(result => {
      // Evaluate the received variable here

      console.log(result);
    })
    .catch(error => {
      console.error("Error:", error);
    })

}