<?php 

require_once 'includes/dbh_inc.php';
require_once 'includes/execute_query_inc.php';
require_once 'includes/error_model_inc.php';

session_start();

$courseID = $_SESSION["course_ID"];
$cohortID = $_SESSION["cohort_ID"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $data = json_decode(file_get_contents('php://input'), true);
  echo '<script>console.log(' . json_encode($data) . ')</script>';


  if(isset($data['ay']) && isset($data['semester']) && isset($data['studentData'])){
    
    $ay = $data['ay'];
    $semester = $data['semester'];
    $studentData = $data['studentData'];

    echo $ay, $semester, $studentData;

    $responses = []; // Initialize an array to store responses for each student

    foreach ($studentData as $student) {

        // Check if 'studentID' key exists in the $student array
        if (!isset($student['studentID'])) {
          $responses[] = ['studentID' => $student['studentID'], 'status' => 'error', 'message' => 'Missing studentID'];
          continue; // Skip this iteration if studentID is missing
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

  } else {
    echo "Missing required data.";
  }
} else {
  echo "Student ID not provided.";
}