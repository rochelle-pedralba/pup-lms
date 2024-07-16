<?php
require_once 'includes/dbh_inc.php'; 
require_once 'includes/execute_query_inc.php';
require_once 'includes/error_model_inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subjectID = $_POST['subject_id'];

    // Start a transaction
    $mysqli->begin_transaction();

    try {
        // Copy the entry from the subject table to the subject_archived table
        $queryArchive = "INSERT INTO subject_archived (subject_ID, user_ID, subject_Name, subject_Description, cohort_ID, course_ID, ay, semester, year, section)
                         SELECT subject_ID, user_ID, subject_Name, subject_Description, cohort_ID, course_ID, ay, semester, year, section
                         FROM subject
                         WHERE subject_ID = ?";
        $archiveResult = executeQuery($mysqli, $queryArchive, "s", [$subjectID]);

        if (!$archiveResult['success']) {
            throw new Exception("Failed to archive the subject.");
        }

        // Commit the transaction
        $mysqli->commit();
        echo "Subject successfully archived.";
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $mysqli->rollback();
        echo "An error occurred: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
?>