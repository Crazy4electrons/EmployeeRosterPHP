function validateAndSubmit() {
  var inputName = document.getElementById("Lusername").value;
  var inputPass = document.getElementById("Lpassword").value;
  var inputPasslength = inputPass.length;
  var inputNamelength = inputPass.length;
  var regex = /^[a-zA-Z_]+$/;
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
  }else{
    alert("name should atleast be 6 characters long.")
  }
}

function checkwithadmin(){
  prompt
}