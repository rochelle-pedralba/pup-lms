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
    $cohort_ID = isset($_POST['cohort_ID']) ? sanitize_input($_POST['cohort_ID']) : null;
    $creator_ID = isset($_POST['creator_ID']) ? sanitize_input($_POST['creator_ID']) : null;
    $cohort_name = isset($_POST['cohort_name']) ? sanitize_input($_POST['cohort_name']) : null;
    $cohort_size = isset($_POST['cohort_size']) ? sanitize_input($_POST['cohort_size']) : null;

    if ($cohort_ID && $creator_ID && $cohort_name && $cohort_size) {
        function record_exists($mysqli, $table, $column, $value) {
            $sql = $mysqli->prepare("SELECT COUNT(*) FROM $table WHERE $column = ?");
            $sql->bind_param("s", $value);
            $sql->execute();
            $sql->bind_result($count);
            $sql->fetch();
            $sql->close();
            return $count > 0;
        }

        if (!record_exists($mysqli, 'COHORT', 'creator_ID', $creator_ID)) {
            echo "<script>alert('Error: CREATOR ID does not exist.');</script>";
        } else {
            $sql = $mysqli->prepare("UPDATE COHORT SET creator_ID = ?, cohort_Name = ?, cohort_Size = ? WHERE cohort_ID = ?");
            $sql->bind_param("ssss", $creator_ID, $cohort_name, $cohort_size, $cohort_ID);
            if ($sql->execute()) {
                echo "<script>alert('Cohort has been successfully updated.');</script>";
                echo "<meta http-equiv='refresh' content='0;url=../pages/admin/update_cohort.php?>";
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
