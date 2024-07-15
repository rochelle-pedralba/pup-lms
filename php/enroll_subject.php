<?php

require_once 'includes/dbh_inc.php';
require_once 'includes/execute_query_inc.php';
require_once 'includes/error_model_inc.php';

session_start();
$_SESSION["course_ID"] = "BSCS";
$_SESSION["subject_ID"] = "COMP10173";
$_SESSION["cohort_ID"] = "PUPSJ";
$_SESSION["ay"] = "2324";
$_SESSION["semester"] = "2";
$_SESSION["year"] = "3";
$_SESSION["section"] = "5";

$courseID = $_SESSION["course_ID"];
$subjectID = $_SESSION["subject_ID"];
$cohortID = $_SESSION["cohort_ID"];
$ay = $_SESSION["ay"];
$semester = $_SESSION["semester"];
$year = $_SESSION["year"];
$section = $_SESSION["section"];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['course_ID'])) {
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