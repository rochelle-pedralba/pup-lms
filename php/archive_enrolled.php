<?php
date_default_timezone_set('Asia/Manila');

require_once 'includes/dbh_inc.php';
require_once 'includes/execute_query_inc.php';
require_once 'includes/error_model_inc.php';

function getInfo($mysqli, $user_ID){
    $query = "SELECT user_ID, subject_ID, semester, year, section, course_ID, cohort_ID, ay FROM subject_enrolled WHERE user_ID = ?";
    $queryResult = executeQuery($mysqli, $query, "s", [$user_ID]);
    $row = $queryResult['result']->fetch_assoc();
    return $row;
}

if (isset($_GET['studentName'])) {
    $studentName = $_GET['studentName'];

    $query = "SELECT user_ID FROM user_information WHERE CONCAT(last_name, ', ', first_name, ' ', middle_name) = ?";
    $params = [$studentName];

    $queryResult = executeQuery($mysqli, $query, "s", $params);

    if ($queryResult['success']) {
        $row = $queryResult['result']->fetch_assoc();
        $userID = $row['user_ID'];

        $info = getInfo($mysqli, $userID);

        $archiveQuery = "INSERT INTO subject_enrolled_archive (user_ID, subject_ID, semester, year, section, course_ID, cohort_ID, ay) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $archiveParams = [$info['user_ID'], $info['subject_ID'], $info['semester'], $info['year'], $info['section'], $info['course_ID'], $info['cohort_ID'], $info['ay']];
        $archiveResult = executeQuery($mysqli, $archiveQuery, "ssssssss", $archiveParams);

        $deleteQuery = "DELETE FROM subject_enrolled WHERE user_ID = ?";
        $deleteResult = executeQuery($mysqli, $deleteQuery, "s", [$userID]);


        if ($deleteResult['success'] && $archiveResult['success']) {
            header('Location: ../pages/faculty/subject/enroll_subject.php');
        } else {
            $error_message = "An error has occured. Please try again later or contact the administrator.";
            redirectWithError($error_message);
            exit;
        }
    } else {
        $error_message = "An error has occured. Please try again later or contact the administrator.";
        redirectWithError($error_message);
        exit;
    }
} else {
    $error_message = "An error has occured. Please try again later or contact the administrator.";
    redirectWithError($error_message);
    exit;
}
?>
