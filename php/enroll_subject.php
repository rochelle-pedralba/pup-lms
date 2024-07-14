<?php

require_once 'includes/dbh_inc.php';
require_once 'includes/execute_query_inc.php';
require_once 'includes/error_model_inc.php';

$course_ID = $_SESSION["course_ID"];
$subjectID = $_SESSION["subject_ID"];
$cohortID = $_SESSION["cohort_ID"];
$ay = $_SESSION["ay"];
$semester = $_SESSION["semester"];
$year = $_SESSION["year"];
$section = $_SESSION["section"];

echo $_SERVER['REQUEST_METHOD'];

// Make sure the incoming request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $jsonContent = file_get_contents('php://input');
  $data = json_decode($jsonContent, true);
  
  $response = ['status' => 'success', 'message' => 'Data received successfully.'];
  echo json_encode($response);



} else {
  http_response_code(405);
  echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}