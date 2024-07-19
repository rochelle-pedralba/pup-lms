<?php

  require_once 'includes/dbh_inc.php';
  require_once 'includes/execute_query_inc.php';
  require_once 'includes/error_model_inc.php';
  
  $courseID = isset($_SESSION["course_ID"]) ? $_SESSION["course_ID"] : null;
  $cohortID = isset($_SESSION["cohort_ID"]) ? $_SESSION["cohort_ID"] : null;

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['studentID'])) {
    
    $studentID = $_POST['studentID'];
    $query = "SELECT ui.user_ID, ui.email_Address, ui.first_Name, ui.last_Name
          FROM user_information ui
          LEFT JOIN course_enrolled ce ON ui.user_ID = ce.user_ID AND ce.course_ID = ?
          WHERE ui.user_ID = ? AND ce.user_ID IS NULL;";

    $result = executeQuery($mysqli, $query, "ss", [$courseID, $studentID]);

    if ($result['success'] && $result['result']->num_rows > 0) {
        $student = $result['result']->fetch_assoc();

        echo "<div class='row-selected-student'>";
        echo "<table>";
        echo "<tr>";
        echo "<td><input type='checkbox' class='studentCheckbox' value=" . $student['user_ID'] . "></td>";
        echo "<td> " . $student['user_ID'] . " </td>";
        echo "<td> " . $student['first_Name'] . " </td>";
        echo "<td> " . $student['last_Name'] . " </td>";
        echo "<td> " . $student['email_Address'] . " </td>";
        echo "</tr>";
        echo "</table>";
        echo "</div>";
    } else {
        echo "Student with ID " . htmlspecialchars($studentID) . " is not existing or is already enrolled in the course.";
    }

} else {
    echo "Student ID not provided.";
}
?>