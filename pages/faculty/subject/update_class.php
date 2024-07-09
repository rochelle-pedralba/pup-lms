<!--<?php

  require_once '../../../php/includes/dbh_inc.php';
  require_once '../../../php/includes/execute_query_inc.php';
  require_once '../../../php/includes/error_model_inc.php';

  $creator_ID = "202110273MN0";

  $querySubject = "SELECT subject_Name, subject_ID FROM subject WHERE creator_ID = ?";
  $queryResult = executeQuery($mysqli, $querySubject, "s", [$creator_ID]);

  if (!$queryResult['success']) {
    $error_message = "An error has occured. Please try again later or contact the administrator.";
    redirectWithError($error_message);
    exit;
  }

  $row = $queryResult['result']->fetch_assoc();

  $subjectName = $row['subject_Name'];
  $subjectID = $row['subject_ID'];

?>-->

<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="../../../styles/update_class.css">
  </head>

  <body>
    <div>
      <div>
        <h4>Professor Name</h4>
      </div>
      <div>
        <h3 class="year-section-container">Year and Section</h3>
        <h1><?php echo $subjectID.': '.$subjectName; ?></h1>
        <h3>Semester  A.Y.</h3>
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
        <table id="enrolled_student">
        </table>
      </div>
    </div>
  </body>
</html>
</html>