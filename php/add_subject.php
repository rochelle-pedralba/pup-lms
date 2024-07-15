<?php
date_default_timezone_set('Asia/Manila');

require_once 'includes/dbh_inc.php';
require_once 'includes/execute_query_inc.php';
require_once 'includes/error_model_inc.php';


$user_ID = $_SESSION['user_ID']; // Example session variable


$cohort_ID = $_POST['cohort_ID'];
$subject_ID = $_POST['subject_ID'];
$subject_Name = $_POST['subject_name'];
$subject_Description = $_POST['subject_description']; 
$ay = $_POST['ay'];
$semester = $_POST['semester'];
$course_ID = $_POST['course_ID'];
$year = $_POST['year'];
$section = $_POST['section'];

$queryUser = "SELECT creator_ID FROM user_information WHERE user_ID = ?";
$paramsUser = [$user_ID];
$resultUser = executeQuery($mysqli, $queryUser, 'i', $paramsUser);

if ($resultUser['success']) {
    $creator_ID = $resultUser['result']->fetch_assoc()['creator_ID'];

    // Insert query for subject table
    $queryInsert = "INSERT INTO `subject` 
                    (`creator_ID`, `cohort_ID`, `subject_ID`, `subject_Name`, `subject_Description`, `ay`, `semester`, `course_ID`, `year`, `section`) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $paramsInsert = [
        $creator_ID, $cohort_ID, $subject_ID, $subject_Name, $subject_Description, $ay, $semester, $course_ID, $year, $section
    ];

    // Execute insert query
    $resultInsert = executeQuery($mysqli, $queryInsert, 'iiisssssss', $paramsInsert);

    if ($resultInsert['success']) {
        echo "Subject added successfully!";
    } else {
        echo "Error inserting subject: " . $resultInsert['error'];
    }
} else {
    echo "Error fetching creator_ID: " . $resultUser['error'];
}

// Close database connection
$mysqli->close();
?>
