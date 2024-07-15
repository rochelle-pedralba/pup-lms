<?php
date_default_timezone_set('Asia/Manila');

require_once '../../../php/includes/dbh_inc.php'; 
require_once '../../../php/includes/execute_query_inc.php';
require_once '../../../php/includes/error_model_inc.php';

function sanitize_input($data) {
    return htmlspecialchars(trim($data));
}

if (isset($_GET['college_ID'])) {
    $college_ID = sanitize_input($_GET['college_ID']);

    $reference_check_sql = $mysqli->prepare("SELECT COUNT(*) as count FROM COURSE WHERE college_ID = ?");
    $reference_check_sql->bind_param("s", $college_ID);
    $reference_check_sql->execute();
    $reference_result = $reference_check_sql->get_result();
    $reference_count = $reference_result->fetch_assoc()['count'];
    $reference_check_sql->close();

    if ($reference_count > 0) {
        echo "<script>alert('Error: College cannot be archived because it is referenced in another table.');</script>";
        echo "<meta http-equiv='refresh' content='0;url=update_student_college.php'>";
        exit;
    }

    $sql = $mysqli->prepare("SELECT * FROM COLLEGE WHERE college_ID = ?");
    $sql->bind_param("s", $college_ID);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        $cohort = $result->fetch_assoc();
        $sql->close();

        $archive_sql = $mysqli->prepare("INSERT INTO college_archive (college_ID, college_Name, description) VALUES (?, ?, ?)");
        $archive_sql->bind_param("sss", $cohort['college_ID'], $cohort['college_Name'], $cohort['description']);

        if ($archive_sql->execute()) {
            $archive_sql->close();

            $delete_sql = $mysqli->prepare("DELETE FROM COLLEGE WHERE college_ID = ?");
            $delete_sql->bind_param("s", $college_ID);

            if ($delete_sql->execute()) {
                echo "<script>alert('College has been successfully archived');</script>";
                echo "<meta http-equiv='refresh' content='0;url=update_student_college.php'>";
                exit;
            } else {
                echo "<script>alert('Error: " . $delete_sql->error . "');</script>";
            }
            $delete_sql->close();
        } else {
            echo "<script>alert('Error: " . $archive_sql->error . "');</script>";
        }
    } else {
        echo "<script>alert('Error: College not found.');</script>";
    }
    $sql->close();
} else {
    echo "<script>alert('Error: No college ID provided.');</script>";
}
?>
