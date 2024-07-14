<!--<?php
session_start();

require_once '../../php/includes/dbh_inc.php';
require_once '../../php/includes/execute_query_inc.php';
require_once '../../php/includes/error_model_inc.php';

$course = "BSCS";  //from session variable
$ay = "2324";    //from session variable
$semester = "2";    //from session variable

$_SESSION['course'] = $course;
$_SESSION['ay'] = $ay;
$_SESSION['semester'] = $semester;

?>-->

<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="../../styles/update_class.css">
  <script src="../../scripts/unenroll_course.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
  <div>
    <div>
      <h4>Admin Name</h4>
    </div>
    <div>
      <h1><?php echo $course?></h1>
      <h3><?php echo $ay.': Semester '.$semester ?></h3>
    </div>
  </div>

  <div id="enroll_students">
    <div class="first-column">
      <div>
        <input type="search">
        <select>
          <option value="1">Student ID</option>
          <option value="2">Email Address</option>
          <option value="3">Last Name</option>
        </select>
      </div>

      <table id="student_list">
      </table>
    </div>

    <div class="second-column">
      <table id="selected_students">
        <h2>Selected Students</h2>
      </table>
    </div>
  </div>

    <div id="unenroll_students">
        <div class="first-column">
        <h2>Student Enrolled</h2>
        <div id = search_student>
            <form method="POST">
            <input type="text" id="studentSearch" name="studentID" placeholder="Enter Student ID">
            <button type="submit" id="searchBtn">Search</button>
            </form>
        </div>
        <div id="searchedStudentInfo">
          <div class="display-student">
            <div id="student_list">
              <?php
                
                require_once '../../php/unenroll_in_course.php'; 
              ?>
            </div>
          </div>
        </div>
    </div>
</body>
</html>
</html>