function loadPrograms() {
  fetch('../json/course_department.json')
    .then(response => response.json())
    .then(program => {
      populatePrograms(program.student.Program);
    });
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
fetch('../json/pupcampus.json')
    .then(response => response.json())
    .then(campuses => {
        populateCampuses(campuses);
    });
}

function populateCampuses(campuses) {
var campusSelect = document.getElementById('cohort_ID');
campusSelect.innerHTML = '<option value="" selected disabled>Select Campus</option>';

campuses.forEach(function(campus) {
    var option = document.createElement('option');
    option.value = campus.cohort_ID;
    option.textContent = campus.cohort_Name;
    campusSelect.appendChild(option);
});

// Add event listener to log selected cohort_ID
campusSelect.addEventListener('change', function() {
  var selectedCohortID = this.value;
  console.log(`Selected Campus ID: ${selectedCohortID}`);
});
}

// Load programs and campuses when the page loads
document.addEventListener('DOMContentLoaded', function() {
loadPrograms();
loadCampuses();
});
