document.addEventListener('DOMContentLoaded', function() {
  loadPrograms();
  loadCampuses();
});

function loadPrograms() {
  fetch('/pup-lms/json/course_department.json')
      .then(response => response.json())
      .then(program => {
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
  fetch('/pup-lms/php/add_subject.php')
      .then((response) => response.json())
      .then((campuses) => {
          console.log(campuses); // Log the fetched data to the console
          populateCampuses(campuses);
      })
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
}