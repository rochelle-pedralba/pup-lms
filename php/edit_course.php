<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Manila');

require_once 'includes/dbh_inc.php';
require_once 'includes/execute_query_inc.php';
require_once 'includes/error_model_inc.php';

function sanitize_input($data) {
    return htmlspecialchars(trim($data));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_ID = isset($_POST['course_ID']) ? sanitize_input($_POST['course_ID']) : null;
    $creator_ID = isset($_POST['creator_ID']) ? sanitize_input($_POST['creator_ID']) : null;
    $cohort_ID = isset($_POST['cohort_ID']) ? sanitize_input($_POST['cohort_ID']) : null;
    $course_desc = isset($_POST['course_desc']) ? sanitize_input($_POST['course_desc']) : null;
    $college_ID = isset($_POST['college_ID']) ? sanitize_input($_POST['college_ID']) : null;
    $no_of_years = isset($_POST['no_of_years']) ? sanitize_input($_POST['no_of_years']) : null;
    $course_name = isset($_POST['course_name']) ? sanitize_input($_POST['course_name']) : null;

    if ($course_ID && $creator_ID  && $cohort_ID && $course_desc && $college_ID && $no_of_years && $course_name) {
        function record_exists($mysqli, $table, $column, $value) {
            $sql = $mysqli->prepare("SELECT COUNT(*) FROM $table WHERE $column = ?");
            $sql->bind_param("s", $value);
            $sql->execute();
            $sql->bind_result($count);
            $sql->fetch();
            $sql->close();
            return $count > 0;
        }

        if (!record_exists($mysqli, 'COLLEGE', 'college_ID', $college_ID)) {
            echo "<script>alert('Error: COLLEGE does not exist.');</script>";
        } elseif (!record_exists($mysqli, 'COHORT', 'cohort_ID', $cohort_ID)) {
            echo "<script>alert('Error: COHORT does not exist.');</script>";
        } elseif (!record_exists($mysqli, 'COURSE', 'creator_ID', $creator_ID)) {
            echo "<script>alert('Error: CREATOR ID does not exist.');</script>";
        } else {
            $sql = $mysqli->prepare("UPDATE COURSE SET creator_ID = ?,  cohort_ID = ?, course_Description = ?, college_ID = ?, no_Of_Years = ?, course_Name = ? WHERE course_ID = ?");
            $sql->bind_param("ssssiss", $creator_ID, $cohort_ID, $course_desc, $college_ID, $no_of_years, $course_name, $course_ID);
            if ($sql->execute()) {
                echo "<script>alert('Course has been successfully updated.');</script>";
                echo "<meta http-equiv='refresh' content='0;url=../pages/admin/update_student_course.php?>";
                exit;
            } else {
                echo "<script>alert('Error: " . $sql->error . "');</script>";
            }
            $sql->close();
        }
    } else {
        echo "<script>alert('Error: All fields are required.');</script>";
    }
}
?>
