<?php 

  $_SESSION["course_ID"] = "BSCS";
  $_SESSION["cohort_ID"] = "PUPMN";
  $_SESSION["ay"] = "2324";
  $_SESSION["semester"] = "1";
?>

<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="../../../styles/update_enrollee1.css">
  </head>

  <body>
    
    <div class="year-section-container">
      <div>
        <h4>Course Creator Name</h4>
      </div>
      <div>
        <h1>Course ID: Course Name</h1>
        <h3>No. of Years</h3>
      </div>
    </div>

    <div class="row-container">
      <div class="row">
        
      <div class="column column-one" style="background-color:#aaa;">
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
        </div>
        
        <div class="column column-two" style="background-color:#bbb;">
          <div class="selected-student-container">
            <div class="title">
              <h3>Selected Student</h3>
              <div>
                <input type="text" id="ay" name="sy" placeholder="Enter School Year" pattern="\d{4}" title="Enter a valid School Year" onblur="validateSY(this);">
                <div id="syError" class="error"></div>

                <input type="text" id="semester" name="semester" placeholder="Enter Semester" pattern="\d{1}" title="Enter the semester" onblur="validateSemester(this);">
                <div id="semesterError" class="error"></div>
                <input id="enroll_student" type="submit" value="Enroll Student" disabled/>
              </div>
            </div>
            <div id="selected_student"></div>
          </div>
        </div>
        
        <div class="column column-three" style="background-color:#ccc;">
          <div id="unenroll_students">
            <div class="first-column">
              <h3>Student Enrolled</h3>
              <div id = "search_student_">
                <form method="POST">
                  <input type="text" name="studentID" placeholder="Enter Student ID">
                  <button type="submit">Search</button>
                </form>
              </div>
            </div>
          </div>

          <div id="searchedStudentInfo">
            <div class="display-student">
              <div id="student_list_">
                <?php
                  require_once '../../../php/unenroll_in_course.php'; 
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../../../scripts/update_enrollee.js"></script>
    <script src="../../../scripts/unenroll_course.js"></script>

  </body>
</html>