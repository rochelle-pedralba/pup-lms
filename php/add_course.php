<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Manila');

require_once 'includes/dbh_inc.php'; // Ensure this file correctly defines the $mysqli variable
require_once 'includes/execute_query_inc.php';
require_once 'includes/error_model_inc.php';

function sanitize_input($data) {
    return htmlspecialchars(trim($data));
}

$course_ID = isset($_POST['course_ID']) ? sanitize_input($_POST['course_ID']) : null;
$creator_ID = isset($_POST['creator_ID']) ? sanitize_input($_POST['creator_ID']) : null;
$cohort_ID = isset($_POST['cohort_ID']) ? sanitize_input($_POST['cohort_ID']) : null;
$course_desc = isset($_POST['course_desc']) ? sanitize_input($_POST['course_desc']) : null;
$college_ID = isset($_POST['college_ID']) ? sanitize_input($_POST['college_ID']) : null;
$no_of_years = isset($_POST['no_of_years']) ? sanitize_input($_POST['no_of_years']) : null;
$course_name = isset($_POST['course_name']) ? sanitize_input($_POST['course_name']) : null;

function record_exists($mysqli, $table, $column1, $value1, $column2 = null, $value2 = null) {
    if ($column2 && $value2) {
        $sql = $mysqli->prepare("SELECT COUNT(*) FROM $table WHERE $column1 = ? AND $column2 = ?");
        $sql->bind_param("ss", $value1, $value2);
    } else {
        $sql = $mysqli->prepare("SELECT COUNT(*) FROM $table WHERE $column1 = ?");
        $sql->bind_param("s", $value1);
    }
    $sql->execute();
    $sql->bind_result($count);
    $sql->fetch();
    return $count > 0;
}

function add_course($mysqli, $course_ID, $creator_ID, $cohort_ID, $course_desc, $college_ID, $no_of_years, $course_name) {
    if (!record_exists($mysqli, 'COLLEGE', 'college_ID', $college_ID)) {
        return "Error: COLLEGE does not exist.";
    }
    if (!record_exists($mysqli, 'COHORT', 'cohort_ID', $cohort_ID)) {
        return "Error: COHORT does not exist.";
    }
    if (!record_exists($mysqli, 'COURSE', 'creator_ID', $creator_ID)) {
        return "Error: CREATOR ID does not exist.";
    }
    if (record_exists($mysqli, 'COURSE', 'course_ID', $course_ID, 'cohort_ID', $cohort_ID)) {
        return "Error: COURSE with this cohort already exists.";
    }

    $sql = $mysqli->prepare("INSERT INTO COURSE (course_ID, creator_ID, cohort_ID, course_Description, college_ID, no_Of_Years, course_Name) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $sql->bind_param("sssssis", $course_ID, $creator_ID, $cohort_ID, $course_desc, $college_ID, $no_of_years, $course_name);
    
    if ($sql->execute()) {
        return true;
    } else {
        return $sql->error;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($course_ID && $creator_ID && $cohort_ID && $course_desc && $college_ID && $no_of_years && $course_name) {
        if (isset($mysqli) && $mysqli) {
            $result = add_course($mysqli, $course_ID, $creator_ID, $cohort_ID, $course_desc, $college_ID, $no_of_years, $course_name);
            
            if ($result === true) {
                echo "<script>alert('Course has been successfully created');</script>";
                echo "<meta http-equiv='refresh' content='0;url=../pages/admin/course/add_course.html'>";
                exit;
            } else {
                echo "<script>alert('$result');</script>";
            }
        } else {
            echo "<script>alert('Database connection error.');</script>";
        }
    } else {
        echo "<script>alert('Error: All fields are required.');</script>";
    }
} else {
    $error_message = "Form submission method not allowed";
    echo "<script>alert('$error_message');</script>";
    exit;
}
?>
