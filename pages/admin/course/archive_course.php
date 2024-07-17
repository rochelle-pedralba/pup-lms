<?php
date_default_timezone_set('Asia/Manila');

require_once '../../../php/includes/dbh_inc.php'; 
require_once '../../../php/includes/execute_query_inc.php';
require_once '../../../php/includes/error_model_inc.php';

function sanitize_input($data) {
    return htmlspecialchars(trim($data));
}

if (isset($_GET['course_ID'])) {
    $course_ID = sanitize_input($_GET['course_ID']);

    $sql = $mysqli->prepare("SELECT * FROM COURSE WHERE course_ID = ?");
    $sql->bind_param("s", $course_ID);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        $course = $result->fetch_assoc();
        $sql->close();

        $archive_sql = $mysqli->prepare("INSERT INTO course_archive (course_ID, creator_ID, cohort_ID, course_Description, college_ID, no_Of_Years, course_Name) VALUES (?, ?, ?, ?, ?, ?)");
        $archive_sql->bind_param("sssssis", $course['course_ID'], $course['creator_ID'], $course['cohort_ID'], $course['course_Description'], $course['college_ID'], $course['no_Of_Years'], $course['course_name']);

        if ($archive_sql->execute()) {
            $archive_sql->close();

            $delete_sql = $mysqli->prepare("DELETE FROM COURSE WHERE course_ID = ?");
            $delete_sql->bind_param("s", $course_ID);

            if ($delete_sql->execute()) {
                echo "<script>alert('Course has been successfully archived');</script>";
                echo "<meta http-equiv='refresh' content='0;url=update_student_course.php'>";
                exit;
            } else {
                echo "<script>alert('Error: " . $delete_sql->error . "');</script>";
            }
            $delete_sql->close();
        } else {
            echo "<script>alert('Error: " . $archive_sql->error . "');</script>";
        }
    } else {
        echo "<script>alert('Error: Course not found.');</script>";
    }
    $sql->close();
} else {
    echo "<script>alert('Error: No course ID provided.');</script>";
}
?>
