document.addEventListener('DOMContentLoaded', () => {
    fetch('../php/profile.php')
        .then(response => response.json())
        .then(data => {
            const country = data.country;
            const region = data.region;
            const province = data.province;
            const city = data.city;

            document.getElementById('first_Name_Name_Display').textContent = data.first_Name;
            document.getElementById('middle_Name_Name_Display').textContent = data.middle_Name;
            document.getElementById('last_Name_Name_Display').textContent = data.last_Name;
            document.getElementById('user_ID_Name_Display').textContent = data.user_ID;
            
            document.getElementById('email').value = data.email_Address;
            document.getElementById('mobile').value = data.mobile_Number;
            document.getElementById('zip_code').value = data.zip_Code;

            if (country === "Philippines") {
                preLoadUserCountry(country, region, province, city);
            } else {
                disableRequiredDropdowns();

                preLoadUserCountryNonPh(country);
                document.getElementById('_regions').value = data.region;
                document.getElementById('_provinces').value = data.province;
                document.getElementById('_cities').value = data.city;
            }
            
    })
    .catch(error => console.error('Error fetching user data:', error));
    
    loadCountries();
});

function preLoadUserCountryNonPh(country) {
    const countrySelect = document.getElementById('countries');

    for (let i = 0; i < countrySelect.options.length; i++) {
        const option = countrySelect.options[i];
        if (option.value === country) {
            option.selected = true;
            changeField(country);
            return;
        }
    }
}

function disableRequiredDropdowns() {
    var regionsSelect = document.getElementById('regions');
    var provincesSelect = document.getElementById('provinces');
    var citiesSelect = document.getElementById('cities');
    regionsSelect.removeAttribute('required');
    provincesSelect.removeAttribute('required');
    citiesSelect.removeAttribute('required');
}

function preLoadUserCountry(country, region, province, city) {
    const countrySelect = document.getElementById('countries');

    // Select the option with matching value
    for (let i = 0; i < countrySelect.options.length; i++) {
        const option = countrySelect.options[i];
        if (option.value === country) {
            option.selected = true;
            changeField(country);

            // Wait until regions are loaded before calling preloadUserRegion
            const interval = setInterval(() => {
                const regionSelect = document.getElementById('regions');
                if (regionSelect.options.length > 1) {
                    clearInterval(interval);
                    preloadUserRegion(region, province, city);
                }
            }, 100); // Check every 100ms

            return;
        }
    }
}

function preloadUserRegion(region, province, city) {
    const regionSelect = document.getElementById('regions');
    console.log(regionSelect.options.length);

    for (let i = 0; i < regionSelect.options.length; i++) {
        const option = regionSelect.options[i];
        if (option.value === region) {
            option.selected = true;

            loadProvinces(region, city);
            const interval = setInterval(() => {
                const provinceSelect = document.getElementById('provinces');
                if (provinceSelect.options.length > 1) { 
                    clearInterval(interval);
                    preloadUserProvince(region, province, city);
                }
            }, 100); 
            return;
        }
    }
}

function preloadUserProvince(region, province, city) {
    const provinceSelect = document.getElementById('provinces');
    
    for (let i = 0; i < provinceSelect.options.length; i++) {
        console.log("province:" + province);
        const option = provinceSelect.options[i];
        if (option.value === province) {
            option.selected = true;
            
            loadCities(region, province);
            const interval = setInterval(() => {
                const citySelect = document.getElementById('cities');
                
                if (citySelect.options.length > 1) { 
                    clearInterval(interval);
                    preloadUserCity(city);
                }
            }, 100); // Check every 100ms
            return;
        }
    }
}

function preloadUserCity(city) {
    const citySelect = document.getElementById('cities');

    for (let i = 0; i < citySelect.options.length; i++) {
        const option = citySelect.options[i];
        if (option.value === city) {
            option.selected = true;
            return;
        }
    }
}


function loadCountries() {
    fetch('../json/countries.json')
    .then((response) => response.json())
    .then((countries) => {
      populateCountries(countries);
    });
}

function loadProvinces(region, city) {
    console.log('loading provinces');
    fetch('../json/places.json')
    .then((response) => response.json())
    .then((places) => {
    populateProvince(places, region, city);
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
  
  function populateRegion(regions) {
    var regionSelect = document.getElementById('regions');
    regionSelect.innerHTML = '<option selected value="" disabled>Select a Region</option>';
  
    var provinceSelect = document.getElementById('provinces');
    provinceSelect.innerHTML = '<option value="" disabled>Select a Province</option>';
  
    var citySelect = document.getElementById('cities');
    citySelect.innerHTML = '<option value="" disabled>Select a City</option>';
    
    Object.keys(regions).forEach(function(region) {
    
      console.log(region);
  
      var option = document.createElement('option');
      option.value = region;
      option.text = region;
      regionSelect.appendChild(option);
    
    });
  }

function populateProvince(places, region) {
    var provinceSelect = document.getElementById('provinces');
    provinceSelect.innerHTML = '<option selected value="" disabled>Select a Province</option>';
  
    var citySelect = document.getElementById('cities');
    citySelect.innerHTML = '<option value="" disabled>Select a City</option>';
  
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
    citySelect.innerHTML = '<option selected value="" disabled>Select a City</option>';

    Object.keys(places[region]["province_list"][province]["municipality_list"]).forEach(function(city) {
        var option = document.createElement('option');
        option.value = city;
        option.textContent = city;
        citySelect.appendChild(option);
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
      // change the dropdown lists to input boxes if country != Philippines
      console.log('not philippines');
      document.getElementById('regions').style.display = "none";
      document.getElementById('provinces').style.display = "none";
      document.getElementById('cities').style.display = "none";
  
      document.getElementById('_regions').style.display = 'block';
      document.getElementById('_provinces').style.display = 'block';
      document.getElementById('_cities').style.display = 'block';
    }
}

document.getElementById("countries").addEventListener("change", () => {
    console.log('country changed');
    var country = document.getElementById('countries').value;
    changeField(country);
  }, false);

document.getElementById("regions").addEventListener("change", () => {
    console.log('region changed');
    var region = document.getElementById('regions').value;
    loadProvinces(region);
}, false);

document.getElementById("provinces").addEventListener("change", () => {
    console.log('province changed');
    var province = document.getElementById('provinces').value;
    var region = document.getElementById('regions').value;
    loadCities(region, province);
}, false);

function redirectToProfile() {
    window.location.href = "../pages/profile.php";
}
