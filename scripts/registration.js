
var footer = document.getElementById("footer");
footer.style= "margin-top: 400px";

function showForm() {
  var selectedRole = document.getElementById('roles').value;
  document.getElementById('roleInput').value = selectedRole;
  
  document.getElementById("registration_form").style.display = "block";
  footer.style= "margin-top: 10px";
}

function checkPassword() {
  if (document.getElementById('password').value != '') {
    if (document.getElementById('password').value ==
    document.getElementById('confirm_password').value) {
      document.getElementById('submit').disabled = false;
  } else {
    document.getElementById('submit').disabled = true;
    alert("Password doesn't match")
    }
  } else {
    document.getElementById('submit').disabled = true;
  }
}

function validatePassword(password)
{
    var re = /^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z]).{8,}$/;
    if(!re.test(password)) {
      alert('Password must contain at least 8 characters\nOne symbol\nAn uppercase letter\nA lowercase letter\nAnd a number')
    }
}