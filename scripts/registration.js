
var footer = document.getElementById("footer");
footer.style= "margin-top: 400px";

function showForm() {
  var selectedRole = document.getElementById('roles').value;
  document.getElementById('roleInput').value = selectedRole;
  
  document.getElementById("registration_form").style.display = "block";
  footer.style= "margin-top: 10px";
}

var pwInput = document.getElementById("password");
var lower = document.getElementById("lower");
var upper = document.getElementById("upper");
var number = document.getElementById("number");
var symbol = document.getElementById("symbol");
var minlength = document.getElementById("minlength");

// When the user clicks on the password field, show the message box
pwInput.cha = function() {
  document.getElementById("passwordMessage").style.display = "block";
}

pwInput.addEventListener("keyup", () => {});

// When the user clicks outside of the password field, hide the message box
pwInput.onblur = function() {
  document.getElementById("passwordMessage").style.display = "none";
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
    var noUpper = /^(?=.*[A-Z])/;
    var noLower = /^(?=.*[a-z])/;
    var noNum = /^(?=.*\d)/;
    var noSymbol = /^(?=.*[!@#$%^&*])/;
    var minLength = /^.{8,}$/;

    if(noUpper.test(password)) {
      upper.classList.remove("invalid");
      upper.classList.add("valid");
    }

    if(noLower.test(password)) {
      lower.classList.remove("invalid");
      lower.classList.add("valid");
    }

    if(noNum.test(password)) {
      number.classList.remove("invalid");
      number.classList.add("valid");
    }

    if(noSymbol.test(password)) {
      symbol.classList.remove("invalid");
      symbol.classList.add("valid");
    }

    if(minLength.test(password)) {
      minlength.classList.remove("invalid");
      minlength.classList.add("valid");
    }
}