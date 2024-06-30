var footer = document.getElementById("footer");
footer.style= "margin-top: 400px";

function showPersonalDetails() {  
  document.getElementById("personal_information").style.display = "block";
  footer.style= "margin-top: 150px";
}

function showContactDetails() {
  document.getElementById("contact_information").style.display = "block";
  footer.style= "margin-top: 100px";
}

function showLoginInformation() {
  document.getElementById("login_information").style.display = "block";
  footer.style= "margin-top: 40px";

  document.getElementById("submit").style.display = "block";
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

function loadCountries() {
  fetch('../json/countries.json')
  .then((response) => response.json())
  .then((countries) => {
    populateCountries(countries);
  });
}

function loadProvince(region) {

  console.log('loading provinces');
    fetch('../json/places.json')
    .then((response) => response.json())
    .then((places) => {
      populateProvince(places, region);
    });
}

function loadCities(region, province) {

  console.log('loading cities');

  fetch('../json/places.json')
  .then((response) => response.json())
  .then((places) => {
    populateCities(places, region, province);
  });
}

function changeField(country){
  if (country == 'Philippines') {

    document.getElementById('_regions').style.display = 'none';
    document.getElementById('_provinces').style.display = 'none';
    document.getElementById('_cities').style.display = 'none';

    document.getElementById('regions').style.display = "block";
    document.getElementById('provinces').style.display = "block";
    document.getElementById('cities').style.display = "block";

    places();

    function places() {
      fetch('../json/places.json')
      .then((response) => response.json())
      .then((places) => {
        populateRegion(places);
      });
    }
  } else {
    // change the select to input if not philippines
    console.log('not philippines');
    document.getElementById('regions').style.display = "none";
    document.getElementById('provinces').style.display = "none";
    document.getElementById('cities').style.display = "none";

    document.getElementById('_regions').style.display = 'block';
    document.getElementById('_provinces').style.display = 'block';
    document.getElementById('_cities').style.display = 'block';
  }
}

// populate the options in country dropdown
function populateCountries(countries) {
  var countrySelect = document.getElementById('countries');
  countrySelect.innerHTML = '<option value="" selected disabled>Select a Country</option>';

  countries.forEach((country) => {
    console.log(country['name']);

    var option = document.createElement('option');
    option.value = country['name'];
    option.textContent = country['name'];
    countrySelect.appendChild(option);
  });
}

// populate the options in region dropdown
function populateRegion(regions) {
  var regionSelect = document.getElementById('regions');
  regionSelect.innerHTML = '<option value="" selected disabled>Select a Region</option>';
  
  Object.keys(regions).forEach(function(region) {
  
    console.log(region);

    var option = document.createElement('option');
    option.value = region;
    option.text = region;
    regionSelect.appendChild(option);
  
  });
}

// populate the options in province dropdown
function populateProvince(places, region) {
  var provinceSelect = document.getElementById('provinces');
  provinceSelect.innerHTML = '<option value="" selected disabled>Select a Province</option>';

  var citySelect = document.getElementById('cities');
  citySelect.innerHTML = '<option value="" selected disabled>Select a City</option>';

  Object.keys(places[region]["province_list"]).forEach(function(province) {
    var option = document.createElement('option');
    option.value = province;
    option.text = province;
    provinceSelect.appendChild(option);
  });
}

function populateCities(places, region, province) {

  console.log(places[region]["province_list"][province]["municipality_list"]);

  var citySelect = document.getElementById('cities');
  citySelect.innerHTML = '<option value="" selected disabled>Select a City</option>';

  Object.keys(places[region]["province_list"][province]["municipality_list"]).forEach(function(city) {
    var option = document.createElement('option');
    option.value = city;
    option.textContent = city;
    citySelect.appendChild(option);
  });
}

loadCountries();

document.getElementById("next1").addEventListener("click", () => showPersonalDetails());

document.getElementById("next2").addEventListener("click", () => showContactDetails());

document.getElementById("next3").addEventListener("click", () => showLoginInformation());

document.getElementById("countries").addEventListener("change", () => {
  console.log('country changed');
  var country = document.getElementById('countries').value;
  changeField(country);
}, false);

document.getElementById("regions").addEventListener("change", () => {
  console.log('region changed');
  var region = document.getElementById('regions').value;
  loadProvince(region);
}, false);

document.getElementById("provinces").addEventListener("change", () => {
  console.log('province changed');
  var province = document.getElementById('provinces').value;
  var region = document.getElementById('regions').value;
  loadCities(region, province);
}, false);



