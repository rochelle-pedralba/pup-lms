<?php 

session_start();

  require_once 'includes/dbh_inc.php';
  require_once 'includes/execute_query_inc.php';
  require_once 'includes/error_model_inc.php';
  
  $course_ID = $_SESSION["course_ID"];
  $cohort_ID = $_SESSION["cohort_ID"];

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $studentID = $_POST['studentID'];
    $query = "SELECT ui.user_ID, ui.email_Address, ui.first_Name, ui.last_Name
          FROM user_information ui
          LEFT JOIN course_enrolled ce ON ui.user_ID = ce.user_ID AND ce.course_ID = ?
          WHERE ui.user_ID = ? AND ce.user_ID IS NULL;";

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
        echo "Student with ID " . htmlspecialchars($studentID) . " is not existing or is already enrolled in the course.";
    }

    if (isset($data['action']) && $data['action'] === 'enroll') {

        $ay = $_POST['ay'] ?? '';
        $semester = $_POST['semester'] ?? '';
        $studentData = $_POST['studentData'] ?? '';

        $responses = []; // Initialize an array to store responses for each student
    
        foreach ($studentData as $student) {
            // Check if 'studentID' key exists in the $student array
            if (!isset($student['studentID'])) {
                $responses[] = ['studentID' => '', 'status' => 'error', 'message' => 'Missing studentID'];
                continue; // Skip this iteration if 'studentID' is missing
            }
        
            $studentID = $student['studentID'];
            // Assuming $courseID, $ay, $semester, and $cohortID are defined elsewhere in your script
            $insertQuery = "INSERT INTO course_enrolled (user_ID, course_ID, ay, semester, cohort_ID) VALUES (?, ?, ?, ?, ?)";
        
            if ($stmt = $mysqli->prepare($insertQuery)) {
                // Bind the correct variables. Ensure these are defined and hold the correct values.
                $stmt->bind_param("sssss", $studentID, $courseID, $ay, $semester, $cohortID);
        
                if ($stmt->execute()) {
                    $responses[] = ['studentID' => $studentID, 'status' => 'success', 'message' => 'Data inserted successfully.'];
                } else {
                    $responses[] = ['studentID' => $studentID, 'status' => 'error', 'message' => 'Failed to insert data.'];
                }
                $stmt->close();
            } else {
                $responses[] = ['studentID' => $studentID, 'status' => 'error', 'message' => 'Failed to prepare the database statement.'];
            }
        }
        echo json_encode($responses);
      }

} else {
    echo "Student ID not provided.";
}
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="../../../scripts/update_enrollee.js">
</script>