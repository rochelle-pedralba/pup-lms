let sec_count = 0;

document.getElementById('add_section').addEventListener('click', function() {

sec_count++;
const sectionContainer = document.createElement('div');
sectionContainer.className = 'section-container';
sectionContainer.id = `section_container_${sec_count}`;
        
    sectionContainer.innerHTML = `
        <label for="year_level_${sec_count}"> Year Level:</label>
        <input type="number" min="1" max="5" id="year_level_${sec_count}" name="year_level_${sec_count}" placeholder="3" required>

        <label for="section_${sec_count}"> Section:</label>
        <input type="text" id="section_${sec_count}" name="section_${sec_count}" placeholder="5" required>

        <label for="faculty_${sec_count}"> Faculty:</label>
        <input type="text" id="faculty_${sec_count}" name="faculty_${sec_count}" placeholder="Juan Dela Cruz" required>

        <button type="button" class="remove_section" onclick="removeSection(${sec_count})">Remove</button>`;

    document.getElementById('sections-container').appendChild(sectionContainer);
});

function removeSection(sectionId) {
    const sectionContainer = document.getElementById(`section_container_${sectionId}`);
    sectionContainer.remove();
}

document.getElementById('subject_form').addEventListener('submit', function(event) {
    const sections = [];
    const errorMessage = document.getElementById('error-message');

    for (let i = 1; i <= sec_count; i++) {
        const yearLevel = document.getElementById(`year_level_${i}`);
        const section = document.getElementById(`section_${i}`);

        if (yearLevel && section) {
            const sectionValue = section.value.trim();
            const yearLevelValue = yearLevel.value.trim();
            const combination = `${yearLevelValue}-${sectionValue}`;

            if (sections.includes(combination)) {
                alert('Warning: Duplicate section and year level combination');
                return;
            }
            sections.push(combination);
        }
    }
});

function loadDepartment() {
    fetch('../json/course_department.json')
      .then((response) => response.json())
      .then((department) => {
        populateDepartments(department.faculty.Department);
      });
}
  
function loadProgram() {
    fetch('../json/course_department.json')
      .then((response) => response.json())
      .then((program) => {
        populatePrograms(program.student.Program);
      });
}
  
function populateDepartments(departments) {
    var departmentSelect = document.getElementById('department');
    departmentSelect.innerHTML = '<option value="" selected disabled>Select a Department</option>';
  
    departments.forEach(function(department) {
      var option = document.createElement('option');
      option.value = department;
      option.textContent = department;
      departmentSelect.appendChild(option);
    });
}
  
function populatePrograms(programs) {
    var programSelect = document.getElementById('program');
    programSelect.innerHTML = '<option value="" selected disabled>Select a Program</option>';
  
    programs.forEach(function(program) {
      var option = document.createElement('option');
      option.value = program;
      option.textContent = program;
      programSelect.appendChild(option);
    });
}
  
loadDepartment();
loadProgram();
  