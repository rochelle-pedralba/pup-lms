<?php
date_default_timezone_set('Asia/Manila');

require_once '../../../php/includes/dbh_inc.php'; 
require_once '../../../php/includes/execute_query_inc.php';
require_once '../../../php/includes/error_model_inc.php';

function sanitize_input($data) {
    return htmlspecialchars(trim($data));
}

$cohort_ID = 'pupsm'; 

if ($cohort_ID) {

    $reference_check_sql = $mysqli->prepare("SELECT COUNT(*) as count FROM COURSE WHERE cohort_ID = ?");
    $reference_check_sql->bind_param("s", $cohort_ID);
    $reference_check_sql->execute();
    $reference_result = $reference_check_sql->get_result();
    $reference_count = $reference_result->fetch_assoc()['count'];
    $reference_check_sql->close();

    if ($reference_count > 0) {
        echo "<script>alert('Error: Cohort cannot be archived because it is referenced in another table.');</script>";
        echo "<meta http-equiv='refresh' content='0;url=update_student_cohort.php'>";
        exit;
    }

    $sql = $mysqli->prepare("SELECT * FROM COHORT WHERE cohort_ID = ?");
    $sql->bind_param("s", $cohort_ID);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        $cohort = $result->fetch_assoc();
        $sql->close();

        $archive_sql = $mysqli->prepare("INSERT INTO cohort_archive (cohort_ID, creator_ID, cohort_Name, cohort_Size) VALUES (?, ?, ?, ?)");
        $archive_sql->bind_param("ssss", $cohort['cohort_ID'], $cohort['creator_ID'], $cohort['cohort_Name'], $cohort['cohort_Size']);

        if ($archive_sql->execute()) {
            $archive_sql->close();

            $delete_sql = $mysqli->prepare("DELETE FROM COHORT WHERE cohort_ID = ?");
            $delete_sql->bind_param("s", $cohort_ID);

            if ($delete_sql->execute()) {
                echo "<script>alert('Cohort has been successfully archived');</script>";
                echo "<meta http-equiv='refresh' content='0;url=update_student_cohort.php'>";
                exit;
            } else {
                echo "<script>alert('Error: " . $delete_sql->error . "');</script>";
            }
            $delete_sql->close();
        } else {
            echo "<script>alert('Error: " . $archive_sql->error . "');</script>";
        }
    } else {
        echo "<script>alert('Error: Cohort not found.');</script>";
    }
    $sql->close();
} else {
    echo "<script>alert('Error: No cohort ID provided.');</script>";
}
?>
