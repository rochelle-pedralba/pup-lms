<?php
date_default_timezone_set('Asia/Manila');

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "pup_lms";

$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

require_once 'includes/execute_query_inc.php'; // Assuming this file contains your executeQuery function

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming these are your form field names
    $user_ID = "FA0123561212"; 
    $cohort_ID = $_POST['cohort_ID'];
    $subject_ID = $_POST['subject_ID'];
    $subject_Name = $_POST['subject_name'];
    $subject_Description = $_POST['subject_description'];
    $ay = $_POST['ay'];
    $semester = $_POST['semester'];
    $course_ID = $_POST['course_ID'];
    $year = $_POST['year'];
    $section = $_POST['section'];

    // Insert query using prepared statements to prevent SQL injection
    $queryInsert = "INSERT INTO `subject`
                    (`user_ID`, `cohort_ID`, `subject_ID`, `subject_Name`, `subject_Description`, `ay`, `semester`, `course_ID`, `year`, `section`) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $insertResult = executeQuery($mysqli, $queryInsert, "ssssssssss", [$user_ID, $cohort_ID, $subject_ID, $subject_Name, $subject_Description, $ay, $semester, $course_ID, $year, $section]);

    if ($insertResult) {
        echo "Record inserted successfully.";
    } else {
        echo "Error: " . $mysqli->error;
    }
} else {
    echo "Method not allowed.";
}

// Close database connection
$mysqli->commit();
$mysqli->close();
?>
