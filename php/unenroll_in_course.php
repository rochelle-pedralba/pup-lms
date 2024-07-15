<?php 

require_once 'includes/dbh_inc.php';
require_once 'includes/execute_query_inc.php';
require_once 'includes/error_model_inc.php';



function name_searching($mysqli, $studentID) {
    $studentNames = [];
    $query = "SELECT user_ID, last_name, first_name, middle_name FROM user_information WHERE user_ID = ?";
    
    $queryResult = executeQuery($mysqli, $query, "s", [$studentID]);
    if ($queryResult['success']) {
        while ($row = $queryResult['result']->fetch_assoc()) {
            $studentNames[] = [
                'user_ID' => $row['user_ID'],
                'name' => $row['last_name'] . ", " . $row['first_name'] . " " . $row['middle_name']
            ];
        }
    } else {
        $error_message = "An error has occurred. Please try again later or contact the administrator.";
        redirectWithError($error_message);
        exit;
    }
    return $studentNames;
}

function EnrolledStudentCourse($mysqli, $params_1) {
    $students = [];
    $query = "SELECT user_ID FROM course_enrolled
            WHERE course_ID = ? AND ay = ? AND semester = ? AND user_ID = ? AND cohort_ID = ?"; 
    $queryResult = executeQuery($mysqli, $query, "sssss", $params_1);

    if (!$queryResult['success']) {
        $error_message = "An error has occurred. Please try again later or contact the administrator.";
        redirectWithError($error_message);
        exit;
    }

    while ($row = $queryResult['result']->fetch_assoc()) {
        $students[] = $row['user_ID'];
    }

    if (empty($students)) {
        return null;
    } else {
        return name_searching($mysqli, $students[0]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_ID = $_SESSION["course_ID"];
    $ay = $_SESSION["ay"];
    $semester = $_SESSION["semester"];
    $user_ID = $_POST["studentID"];
    $cohort_ID = $_SESSION["cohort_ID"];

    $params_1 = [$course_ID, $ay, $semester, $user_ID, $cohort_ID];

    $studentDetails = EnrolledStudentCourse($mysqli, $params_1);
    
    if ($studentDetails == null) {
        echo "No student found with ID " . htmlspecialchars($user_ID) . " who is enrolled in the course.";
    } else {
        echo "<div class='row-selected-student'>";
        echo "<table>";
        foreach ($studentDetails as $student) {
            echo "<tr>";
            echo "<td><button onclick = 'unenrollFunc(\"" . htmlspecialchars(addslashes($student['name']), ENT_QUOTES) . "\")' style = 'padding:0px 5px; margin: 0px 10px'>x</button></td>";
            echo "<td> " . htmlspecialchars($student['user_ID']) . " </td>";
            echo "<td>".": "." </td>";
            echo "<td> " . htmlspecialchars($student['name']) . " </td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    }
} else {
    echo "Student ID not provided.";
}
?>
