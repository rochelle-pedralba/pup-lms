<?php 

  $_SESSION["course_ID"] = "BSCS";
  $_SESSION["cohort_ID"] = "PUPMN";

?>

<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="../../../styles/update_enrollee.css">
  </head>

  <body>
    <div>
      <div>
        <h4>Course Creator Name</h4>
      </div>
      <div>
        <h1>Course ID: Course Name</h1>
        <h3>No. of Years</h3>
      </div>
    </div>

    <div id="enroll_students">
      <div class="first-row">
        <!-- Display all BSCS students -->
        <div id="search_student">
        <form method="POST">
          <input type="text" id="studentSearch" name="studentID" placeholder="Enter Student ID">
          <button type="submit" id="searchBtn">Search</button>
        </form>
        <div id="searchedStudentInfo"></div>

          <div class="display-student">
            <div id="student_list">
              <?php
                require_once '../../../php/student_list.php'; 
              ?>
            </div>
          </div>
        </div>

        <div class="selected-student-container">
          <div class="title">
            <h3>Selected Student</h3>
            <input type="text" id="ay" name="sy" placeholder="Enter School Year" pattern="\d{4}" title="Enter a valid School Year" onblur="validateSY(this);">
            <div id="syError" class="error"></div>

            <input type="text" id="semester" name="semester" placeholder="Enter Semester" pattern="\d{1}" title="Enter the semester" onblur="validateSemester(this);">
            <div id="semesterError" class="error"></div>
            <input id="enroll_student" type="submit" value="Enroll Student" disabled/>
          </div>
          <div id="selected_student"></div>
        </div>
    </div>

    <div id="unenroll_students">
    </div>
  </body>
</html>