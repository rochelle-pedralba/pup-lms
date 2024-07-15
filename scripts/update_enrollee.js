$(document).ready(function() {

  var selectedStudents;

  try {
    selectedStudents = JSON.parse(localStorage.getItem('chosenStudents')) || [];
  } catch (e) {
    console.error("Parsing error: ", e);
    selectedStudents = [];
  }

  $(".studentCheckbox").click(function() {
    var studentID = $(this).val();
    var firstName = $(this).closest('tr').find('td:nth-child(3)').text();
    var lastName = $(this).closest('tr').find('td:nth-child(4)').text();
    var email = $(this).closest('tr').find('td:nth-child(5)').text();
    var isChecked = $(this).is(':checked');
    
    if (isChecked) {
      if (!selectedStudents.some(student => student.studentID === studentID)) {
        selectedStudents.push({studentID, firstName, lastName, email});
        }
    } else {
  
      var isConfirmed = confirm('Do you really want to remove this student?');
      
      if (isConfirmed) {
        var index = selectedStudents.findIndex(student => student.studentID === studentID);
        if (index !== -1) { selectedStudents.splice(index, 1); }
      } else { $(this).prop('checked', true); }
      }
      
      localStorage.setItem('chosenStudents', JSON.stringify(selectedStudents));
      displaySelectedStudent();

    localStorage.setItem('chosenStudents', JSON.stringify(selectedStudents));
    console.log("Selected students: " + JSON.stringify(selectedStudents));
    
    $.ajax({
      type: "POST",
      url: "",
      data: {
        selectedStudents: selectedStudents
      },
      success: function(response) {
        var html = '';                                     
        selectedStudents.forEach(function(student) {
          html += `
            <table>
            <div class="row-selected-student">
            <tr>
              <td><input type="checkbox" class="studentCheckbox1" value="${student.studentID}" checked></td>
              <td>${student.studentID}</td>
              <td>${student.firstName}</td>
              <td>${student.lastName}</td>
              <td>${student.email}</td>
            </tr>
            </div>
            </table>
            `;
        });
        $('#selected_student').html(html);
      }
    });
  });
});

// Display the students onload
$(document).ready(function() {

  displaySelectedStudent();

  $('body').on('click', '.studentCheckbox1', function() {
    var isChecked = $(this).is(':checked');
    var studentID = $(this).val();

    var selectedStudents = JSON.parse(localStorage.getItem('chosenStudents')) || [];

    if (isChecked) {
      if (!selectedStudents.some(student => student.studentID === studentID)) {
        selectedStudents.push({studentID, firstName, lastName, email});
        }
    } else {

      var isConfirmed = confirm('Do you really want to remove this student?');
      
      if (isConfirmed) {
        var index = selectedStudents.findIndex(student => student.studentID === studentID);
        if (index !== -1) { selectedStudents.splice(index, 1); }
      } else { $(this).prop('checked', true); }
      }
      
      localStorage.setItem('chosenStudents', JSON.stringify(selectedStudents));
      displaySelectedStudent();
  });
});

function displaySelectedStudent() {
  var selectedStudents = JSON.parse(localStorage.getItem('chosenStudents')) || [];
  console.log("Loaded students: " + JSON.stringify(selectedStudents));

  var html = '';
  selectedStudents.forEach(function(student) {
    html += `
      <table>
      <div class="row-selected-student">
      <tr>
        <td><input type="checkbox" class="studentCheckbox1" value="${student.studentID}" checked></td>
        <td>${student.studentID}</td>
        <td>${student.firstName}</td>
        <td>${student.lastName}</td>
        <td>${student.email}</td>
      </tr>
      </div>
      </table>
      `;
  });

  $('#selected_student').html(html);
}

// Send the searched student ID data for database query
$(document).ready(function() {
  $("#search_form").submit(function(event) {
    event.preventDefault();
    var studentID = $("#studentSearch").val();

    if (studentID) {
        $.ajax({
            url: '', // Assuming the AJAX handler is in the same file
            type: 'POST',
            data: {
              studentID: studentID,},
            success: function(response) {
              console.log("Success");
            },
            error: function(xhr, status, error) {
                alert("Error searching for student.");
            }
        });
    } else {
        alert("Please enter a student ID.");
    }
  });
});

// Enroll the selected students
$(document).ready(function() {
  
  $('#enroll_student').click(function() {

    var studentData = localStorage.getItem('chosenStudents');
    const isConfirmed = confirm("Are you sure you want to enroll the selected student?");
    
    var ay = $('#ay').val();
    var semester = $('#semester').val();
    
    if (isConfirmed) {
      if (studentData) {
        try {
          studentData = JSON.parse(studentData);

          const xhr = new XMLHttpRequest();
          xhr.open('POST', '../../../php/enroll_course.php', true);
          xhr.setRequestHeader('Content-Type', 'application/json');
          xhr.onreadystatechange = function() {
              if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                  localStorage.removeItem('chosenStudents');
                  window.location.reload();
              }
          }
          xhr.send(JSON.stringify({studentData, action: 'enroll', ay, semester}));

        } catch (e) {
          console.error("Error parsing data from local storage:", e);
          return;
        }
        } else {
            alert("No selected students.");
        }
    }
});
});

document.addEventListener('DOMContentLoaded', function() {
  const schoolYearInput = document.getElementById('ay');
  const semesterInput = document.getElementById('semester');
  const enrollButton = document.getElementById('enroll_student');

  function updateButtonState() {
      if (schoolYearInput.value && semesterInput.value) {
          enrollButton.disabled = false;
      } else {
          enrollButton.disabled = true;
      }
  }

  if (schoolYearInput && semesterInput) {
      schoolYearInput.addEventListener('input', updateButtonState);
      semesterInput.addEventListener('input', updateButtonState);
  }
});

function validateSY(input) {

  const errorDiv = document.getElementById('syError');

  if (input.value.length > 4) {
    errorDiv.textContent = 'School Year must be 4 digits.';
    input.value = '';
  } else {
    errorDiv.textContent = '';
  }

  let firstYear = parseInt(input.value.substring(0, 2), 10);
  let secondYear = parseInt(input.value.substring(2, 4), 10);

  firstYear += 2000;
  secondYear += 2000;

  if (secondYear - firstYear !== 1) {
    errorDiv.textContent = 'SY must be consecutive years. (e.g. 2324)';
    input.value = '';
  } else {
    errorDiv.textContent = '';
  }

  updateButtonState();
}

function validateSemester(input) {
  const errorDiv = document.getElementById('semesterError');
  console.log(input.value);

  if (input.value.length !== 1) {
    errorDiv.textContent = 'Semester must be 1 digit & either 1 or 2.';
    input.value = '';
  } else if (input.value !== '1' && input.value !== '2') {
    errorDiv.textContent = 'Semester must be either 1 or 2.';
    input.value = '';
  }
  else {
    errorDiv.textContent = '';
  }

  updateButtonState();
}