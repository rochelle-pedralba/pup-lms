
var footer = document.getElementById("footer");
footer.style= "margin-top: 400px";

function populate() {
  fetch('../json/dropdown.json')
  .then((response) => response.json())
  .then((json) => {
    showForm(json);
  });
}

  function showForm(json) {
      document.getElementById("registration_form").style.display = "block";
      var role = document.getElementById("roles").value;

      footer.style= "margin-top: 10px";
      document.getElementById("roles").disabled = true;

      if (role == 3) {
        var facultyForm = document.getElementById("faculty_form");
        var form = document.createElement("div");
        
        var departmentLabel = document.createElement("label");
        departmentLabel.textContent = "Department:";
        var departmentSelect = document.createElement("select");
        departmentSelect.id = "department";
        departmentSelect.required = true;

        departmentSelect.innerHTML = '<option value="" selected disabled>Select a Department</option>';
        
        json["faculty"]["Department"].forEach((department) => {
          var option = document.createElement("option");
          option.value = department;
          option.textContent = department;
          departmentSelect.appendChild(option);
        });
        
        var facultyPositionLabel = document.createElement("label");
        facultyPositionLabel.textContent = "Faculty Position:";
        var facultyPositionSelect = document.createElement("select");
        facultyPositionSelect.id = "faculty_position";
        facultyPositionSelect.required = true;

        facultyPositionSelect.innerHTML = '<option value="" selected disabled>Select a Position</option>';

        json["faculty"]["Position"].forEach((position) => {
          var option = document.createElement("option");
          option.value = position;
          option.textContent = position;
          facultyPositionSelect.appendChild(option);
        });
        
        form.appendChild(departmentLabel);
        form.appendChild(departmentSelect);
        form.appendChild(facultyPositionLabel);
        form.appendChild(facultyPositionSelect);
        
        facultyForm.appendChild(form);
      }

      if (role == 5) {
        var studentForm = document.getElementById("student_form");
        var form = document.createElement("div");
        
        var yearLevelLabel = document.createElement("label");
        yearLevelLabel.textContent = "Year Level:";
        var yearLevelSelect = document.createElement("select");
        yearLevelSelect.id = "year_level";
        yearLevelSelect.required = true;

        yearLevelSelect.innerHTML = '<option value="" selected disabled>Select a Year Level</option>';
        
        json["student"]["Year Level"].forEach((year) => {
          var option = document.createElement("option");
          option.value = year;
          option.textContent = year;
          yearLevelSelect.appendChild(option);
        });
        
        var programLabel = document.createElement("label");
        programLabel.textContent = "Program:";
        var programSelect = document.createElement("select");
        programSelect.id = "program";
        programSelect.required = true;

        programSelect.innerHTML = '<option value="" selected disabled>Select a Program</option>';

        json["student"]["Program"].forEach((program) => {
          var option = document.createElement("option");
          option.value = program;
          option.textContent = program;
          programSelect.appendChild(option);
        });
        
        var studentTypeLabel = document.createElement("label");
        studentTypeLabel.textContent = "Type of Student:";
        var studentTypeSelect = document.createElement("select");
        studentTypeSelect.id = "student_type";
        studentTypeSelect.required = true;

        studentTypeSelect.innerHTML = '<option value="" selected disabled>Select Type</option>';
        
        json["student"]["Type"].forEach((type) => {
          var option = document.createElement("option");
          option.value = type;
          option.textContent = type;
          studentTypeSelect.appendChild(option);
        });
        
        form.appendChild(yearLevelLabel);
        form.appendChild(yearLevelSelect);
        form.appendChild(programLabel);
        form.appendChild(programSelect);
        form.appendChild(studentTypeLabel);
        form.appendChild(studentTypeSelect);
        
        studentForm.appendChild(form);
      }
    }