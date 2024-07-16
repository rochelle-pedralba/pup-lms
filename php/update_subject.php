<?php
require_once 'includes/dbh_inc.php'; // for db connection
require_once 'includes/execute_query_inc.php';
require_once 'includes/error_model_inc.php';

// Retrieve form data
$subject_ID = $_POST['subject_id'] ?? '';
$subjectName = $_POST['subject_name'] ?? '';
$semester = $_POST['semester'] ?? '';
$academicYear = $_POST['academic_year'] ?? '';
$year = $_POST['year'] ?? '';
$section = $_POST['section'] ?? '';
$subjectDescription = $_POST['subject_description'] ?? '';

// Update query
$updateQuery = "UPDATE subject SET subject_Name = ?, semester = ?, ay = ?, year = ?, section = ?, subject_Description = ? WHERE subject_ID = ?";
$updateResult = executeQuery($mysqli, $updateQuery, "sssssss", [$subjectName, $semester, $academicYear, $year, $section, $subjectDescription, $subject_ID]);

if (!$updateResult['success']) {
    $error_message = "Failed to update subject. Please try again later.";
    redirectWithError($error_message);
    exit;
}

// Redirect back to view_subject.php with updated data
header("Location: ../pages/faculty/subject/view_subject.php");
exit;
?>