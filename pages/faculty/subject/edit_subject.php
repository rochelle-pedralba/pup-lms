<?php
require_once '../../../php/includes/dbh_inc.php'; // for db connection
require_once '../../../php/includes/execute_query_inc.php';
require_once '../../../php/includes/error_model_inc.php';

// Assuming subject_id is passed via query string
$subject_ID = $_GET['subject_id'] ?? '';

// Retrieve current subject details
$querySubject = "SELECT subject_Name, subject_ID, semester, ay, year, section, subject_Description FROM subject WHERE subject_ID = ?";
$queryResult = executeQuery($mysqli, $querySubject, "s", [$subject_ID]);

if (!$queryResult['success']) {
    $error_message = "An error has occurred. Please try again later or contact the administrator.";
    redirectWithError($error_message);
    exit;
}

$row = $queryResult['result']->fetch_assoc();

// Check if subject exists
if (!$row) {
    $error_message = "Subject not found.";
    redirectWithError($error_message);
    exit;
}

$subjectName = $row['subject_Name'];
$semester = $row['semester'];
$academicYear = $row['ay'];
$subjectDescription = $row['subject_Description'];
$year = $row['year'];
$section = $row['section'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Subject</title>
    <style>
        /* Your CSS styles */
    </style>
</head>

<body>
    <h1>Edit Subject: <?php echo htmlspecialchars($subject_ID); ?></h1>

    <form action="../../../php/update_subject.php" method="POST">
        <input type="hidden" name="subject_id" value="<?php echo htmlspecialchars($subject_ID); ?>">
        
        <label for="subject_name">Subject Name:</label><br>
        <input type="text" id="subject_name" name="subject_name" value="<?php echo htmlspecialchars($subjectName); ?>"><br><br>
        
        <label for="semester">Semester:</label><br>
        <input type="text" id="semester" name="semester" value="<?php echo htmlspecialchars($semester); ?>"><br><br>
        
        <label for="academic_year">Academic Year:</label><br>
        <input type="text" id="academic_year" name="academic_year" value="<?php echo htmlspecialchars($academicYear); ?>"><br><br>
        
        <label for="year">Year:</label><br>
        <input type="text" id="year" name="year" value="<?php echo htmlspecialchars($year); ?>"><br><br>
        
        <label for="section">Section:</label><br>
        <input type="text" id="section" name="section" value="<?php echo htmlspecialchars($section); ?>"><br><br>
        
        <label for="subject_description">Subject Description:</label><br>
        <textarea id="subject_description" name="subject_description" rows="4" cols="50"><?php echo htmlspecialchars($subjectDescription); ?></textarea><br><br>
        
        <input type="submit" value="Submit">
    </form>

    <form method="post" action="../../../php/archive_subject.php">
            <input type="hidden" name="subject_id" value="<?php echo htmlspecialchars($subjectID); ?>">
            <button type="submit">Archive</button>
        </form>

</body>

</html>