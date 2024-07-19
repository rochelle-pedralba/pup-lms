<?php 
  $cohort = isset($_GET['cohort']) ? $_GET['cohort'] : null;
  $course = isset($_GET['course']) ? $_GET['course'] : null;
  $subjectID = isset($_GET['subject_ID']) ? $_GET['subject_ID'] : null;
  $subjectName = isset($_GET['subject_Name']) ? $_GET['subject_Name'] : null;
  $semester = isset($_GET['semester']) ? $_GET['semester'] : null;
  $ay = isset($_GET['ay']) ? $_GET['ay'] : null;
  $section = isset($_GET['section']) ? $_GET['section'] : null;
  $year = isset($_GET['year']) ? $_GET['year'] : null;
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
        <h3 class="year-section-container"><?php echo $year." - ".$section ?></h3>
        <h1><?php echo $subjectID." : ".$subjectName ?></h1>
        <h3>Semester: <?php echo $semester." A.Y. ".$ay?></h3>
      </div>
    </div>

      <div class="row">
        
      <div class="column" style="background-color:#aaa;">
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
        </div>
        
        <div class="column" style="background-color:#bbb;">
          <div class="selected-student-container">
            <div class="title">
              <h3>Selected Student</h3>
              <button id="enroll_student">Enroll</button>
            </div>
            <div id="selected_student"></div>
          </div>
        </div>

        <div id="unenroll_students">
          <div class="column column-three" style="background-color:#ccc;">
            <div id="unenroll_students">
              <div class="unenroll-student-container">
                <h3>Student Enrolled</h3>
                <table id="enrolled_student">
                    <?php require_once '../../../php/unenroll.php'; ?>
                </table>
              </div> 
            </div>

          <div id="searchedStudentInfo">
            <div class="display-student">
              <div id="student_list_">
                <?php
                  require_once '../../../php/unenroll.php'; 
                ?>
              </div>
            </div>
          </div>
        </div>
        <button onclick="window.location.href='../../admin/overview.html'">BACK</button>
    </div>
    
  </body>
</html>