<?php 
  require_once 'includes/dbh_inc.php';
  require_once 'includes/execute_query_inc.php';
  require_once 'includes/error_model_inc.php';

    session_start();

  $course_ID = $_SESSION["course_ID"];
  $subjectID = $_SESSION["subject_ID"];
  $cohortID = $_SESSION["cohort_ID"];
  $ay = $_SESSION["ay"];
  $semester = $_SESSION["semester"];
  $year = $_SESSION["year"];
  $section = $_SESSION["section"];

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['studentID'])) {
    
    $studentID = $_POST['studentID'];
    $query = "SELECT ce.user_ID, ui.first_Name, last_Name, ui.email_Address 
              FROM course_enrolled ce
              JOIN user_information ui ON ce.user_ID = ui.user_ID
              LEFT JOIN subject_enrolled se ON ce.user_ID = se.user_ID
              WHERE ce.course_ID = ? AND ce.user_ID = ? AND se.user_ID IS NULL";
    $result = executeQuery($mysqli, $query, "ss", [$course_ID, $studentID]);

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
        echo "Student with ID " . htmlspecialchars($studentID) . " is not existing or is already enrolled in the subject.";
    }

} else {
    echo "Student ID not provided.";
}
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="../../../scripts/update_class.js">
</script>