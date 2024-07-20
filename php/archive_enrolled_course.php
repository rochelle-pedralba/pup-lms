<?php
date_default_timezone_set('Asia/Manila');

require_once 'includes/dbh_inc.php';
require_once 'includes/execute_query_inc.php';
require_once 'includes/error_model_inc.php';

function getInformation($mysqli, $user_ID){
    $query = "SELECT user_ID, course_ID, ay, semester, cohort_ID FROM course_enrolled WHERE user_ID = ?";
    $queryResult = executeQuery($mysqli, $query, "s", [$user_ID]);
    $row = $queryResult['result']->fetch_assoc();
    return $row;
}

if (isset($_POST['studentID'])) {
    $studentID = $_POST['studentID'];

    // Assuming executeQuery() sets 'success' correctly
    $deleteQuery = "DELETE FROM course_enrolled WHERE user_ID = ?";
    $deleteResult = executeQuery($mysqli, $deleteQuery, "s", [$studentID]);

    // Padagdagan ng archive na query nalang dito

    if ($deleteResult['success']) {
        echo "Student successfully removed."; // This will be the responseText in the AJAX call
    } else {
        echo "An error has occurred. Please try again later or contact the administrator.";
    }
} else {
    redirectWithError("Student ID not provided.");
}
?>
