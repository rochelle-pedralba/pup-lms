<?php

require_once 'includes/dbh_inc.php';
require_once 'includes/execute_query_inc.php';
require_once 'includes/error_model_inc.php';

session_start();

$courseID = isset($_SESSION["course_ID"]) ? $_SESSION["course_ID"] : null;
$subjectID = isset($_SESSION["subject_ID"]) ? $_SESSION["subject_ID"] : null;
$cohortID = isset($_SESSION["cohort_ID"]) ? $_SESSION["cohort_ID"] : null;
$ay = isset($_SESSION["ay"]) ? $_SESSION["ay"] : null;
$semester = isset($_SESSION["semester"]) ? $_SESSION["semester"] : null;
$year = isset($_SESSION["year"]) ? $_SESSION["year"] : null;
$section = isset($_SESSION["section"]) ? $_SESSION["section"] : null;

if (!$courseID || !$subjectID || !$cohortID || !$ay || !$semester || !$year || !$section) {
  // Redirect to a previous step or show an error message
  die('Error: Required session variables are not set.');
}

var_dump($_SESSION);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['studentID'])) {
  $data = json_decode(file_get_contents('php://input'), true);

  // Assuming the action is to enroll students
  if (isset($data['action']) && $data['action'] === 'enroll') {
      $studentData = $data['studentData'];

      $responses = []; // Initialize an array to store responses for each student

      foreach ($studentData as $student) {
          // Check if 'studentID' key exists
          if (!isset($student['studentID'])) {
              $responses[] = ['studentID' => $student['studentID'], 'status' => 'error', 'message' => 'Missing studentID'];
              continue; // Skip this iteration if studentID is missing
          }

          $studentID = $student['studentID'];

          // Prepare the SQL INSERT statement
          $insertQuery = "INSERT INTO subject_enrolled (course_ID, subject_ID, cohort_ID, ay, semester, year, section, user_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

          // Execute the query
          if ($stmt = $mysqli->prepare($insertQuery)) {
              // Bind parameters and execute statement...
          }
      }
  }
}