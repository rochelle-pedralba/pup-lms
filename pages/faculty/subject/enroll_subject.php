<?php 
  session_start();

  $_SESSION["course_ID"] = "BSCS";
  $_SESSION["subject_ID"] = "COMP10173";
  $_SESSION["cohort_ID"] = "PUPSJ";
  $_SESSION["ay"] = "2324";
  $_SESSION["semester"] = "2";
  $_SESSION["year"] = "3";
  $_SESSION["section"] = "5";
?>

<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="../../../styles/update_class.css">
    <script src="../../../scripts/unenroll.js"></script>
  </head>

  <body>
    <div>
      <div>
        <h4>Professor Name</h4>
      </div>
      <div>
        <h3 class="year-section-container">Year and Section</h3>
        <h1>Subject ID: Subject Name</h1>
        <h3>Semester  A.Y.</h3>
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
                require_once '../../../php/enrolled_student_list.php'; 
              ?>
            </div>
          </div>
        </div>

        <div class="selected-student-container">
          <div class="title">
            <h3>Selected Student</h3>
            <button id="enroll_student">Enroll</button>
          </div>
          <div id="selected_student"></div>
        </div>
      </div>

    <div id="unenroll_students">
          <div class="unenroll-student-container">
            <h3>Student Enrolled</h3>
            <table id="enrolled_student">
                <?php require_once '../../../php/unenroll.php'; ?>
            </table>
          </div> 
        </div>
    </div>
  </body>
</html>