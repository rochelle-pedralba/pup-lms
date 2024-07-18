document.addEventListener('DOMContentLoaded', function() {
  loadPrograms();
  loadCampuses();
});

function loadPrograms() {
  fetch('/pup-lms/json/course_department.json')
      .then(response => response.json())
      .then(program => {
          console.log('Programs:', program);
          populatePrograms(program.student.Program);
      })
      .catch(error => console.error('Error loading programs:', error));
}

function populatePrograms(programs) {
  var programSelect = document.getElementById('course_ID');
  programSelect.innerHTML = '<option value="" selected disabled>Select Course</option>';
  programs.forEach(function(program) {
      var option = document.createElement('option');
      option.value = program;
      option.textContent = program;
      programSelect.appendChild(option);
  });
}

function loadCampuses() {
  fetch('/pup-lms/json/pupcampus.json')
      .then((response) => response.json())
      .then((campuses) => {
          populateCampuses(campuses);
      })
      .catch(error => console.error('Error loading campuses:', error));
}

function populateCampuses(campuses) {
  var campusSelect = document.getElementById('cohort_name');
  campusSelect.innerHTML = '<option value="" selected disabled>Select a Campus</option>';
  campuses.forEach((campus) => {
      var option = document.createElement('option');
      option.value = campus.cohort_ID; // Set value to cohort_ID
      option.textContent = campus.cohort_Name;
      campusSelect.appendChild(option);
  });

  campusSelect.addEventListener('change', function() {
      console.log(`Selected Campus Name: ${campusSelect.value}`);
      const selectedCampus = campuses.find(campus => campus.cohort_ID === campusSelect.value);
      if (selectedCampus) {
          document.getElementById('cohort_ID').value = selectedCampus.cohort_ID; // Set hidden input value
          var cohort_ID = document.getElementById('cohort_ID').value;
          console.log(`Selected Campus ID: ${cohort_ID}`);
      }
  });
}