<?php
date_default_timezone_set('Asia/Manila');

require_once 'includes/dbh_inc.php';
require_once 'includes/execute_query_inc.php';
require_once 'includes/error_model_inc.php';

// Assuming information is obtained from session
$subjectID = $_SESSION['subject_ID'] ? $_GET['subject_ID'] : null;
$semester = $_SESSION['semester'] ? $_GET['semester'] : null;
$year = $_SESSION['year'] ? $_GET['year'] : null;   
$section = $_SESSION['section'] ? $_GET['section'] : null;
$course_ID = $_SESSION['course_ID'] ? $_GET['course'] : null;
$cohort_ID = $_SESSION['cohort_ID'] ? $_GET['cohort'] : null;
$ay = $_SESSION['ay'] ? $_GET['ay'] : null;

$params_1 = [$subjectID, $semester, $year, $section, $course_ID, $cohort_ID, $ay];

function EnrolledStudent($mysqli, $params_1): ?array
{
    $query = "SELECT user_ID FROM subject_enrolled 
              WHERE subject_ID = ? AND semester = ? AND year = ? AND section = ? AND course_ID = ? AND cohort_ID = ? AND ay = ?";
    $queryResult = executeQuery($mysqli, $query, "sssssss", $params_1);
    
    if (!$queryResult['success']) {
        $error_message = "An error has occured. Please try again later or contact the administrator.";
        redirectWithError($error_message);
        exit;
    }

    $students = [];
    while ($row = $queryResult['result']->fetch_assoc()) {
        $students[] = $row['user_ID'];
    }
    if (empty($students)) {
        return null;
    } else {
        return user_id_searching($mysqli, $students);
    }
}

function user_id_searching($mysqli, $students): array
{
    $studentNames = array();
    $query = "SELECT user_ID, last_name, first_name, middle_name FROM user_information WHERE user_ID = ?";
    
    foreach ($students as $studentID) {
        $queryResult = executeQuery($mysqli, $query, "s", [$studentID]);
        
        if ($queryResult['success']) {
            while ($row = $queryResult['result']->fetch_assoc()) {
                $studentNames[] = [
                    'name' => $row['last_name'] . ", " . $row['first_name'] . " " . ($row['middle_name'] ?? ''),
                    'user_ID' => $row['user_ID']
                ];
            }
        } else {
            $error_message = "An error has occured. Please try again later or contact the administrator.";
            redirectWithError($error_message);
            exit;
        }
    }
    sort($studentNames);
    return $studentNames;
}

$students = EnrolledStudent($mysqli, $params_1); 

if ($students !== null) {
    foreach ($students as $student) {
            echo "<tr>";
            echo "<td><button onclick='unenrollFunc(\"" . htmlspecialchars(addslashes($student['name']), ENT_QUOTES) . "\", \"" . htmlspecialchars(addslashes($student['user_ID']), ENT_QUOTES) . "\")' style='padding:0px 5px; margin: 0px 10px'>x</button></td>";
            echo "<td id='showname'>" . htmlspecialchars($student['name']) . "</td>";
            echo "</tr>";
    }
} else {
    echo "No students are enrolled in this subject.";
}
session_destroy();
?>
