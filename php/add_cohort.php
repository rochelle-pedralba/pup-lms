<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Manila');

require_once 'includes/dbh_inc.php'; // Ensure this file correctly defines the $mysqli variable
require_once 'includes/execute_query_inc.php';
require_once 'includes/error_model_inc.php';

function sanitize_input($data) {
    return htmlspecialchars(trim($data));
}

$cohort_ID = isset($_POST['cohort_ID']) ? sanitize_input($_POST['cohort_ID']) : null;
$creator_ID = isset($_POST['creator_ID']) ? sanitize_input($_POST['creator_ID']) : null;
$cohort_name = isset($_POST['cohort_name']) ? sanitize_input($_POST['cohort_name']) : null;
$cohort_size = isset($_POST['cohort_size']) ? sanitize_input($_POST['cohort_size']) : null;

function record_exists($mysqli, $table, $column, $value) {
    $sql = $mysqli->prepare("SELECT COUNT(*) FROM $table WHERE $column = ?");
    $sql->bind_param("s", $value);
    $sql->execute();
    $sql->bind_result($count);
    $sql->fetch();
    return $count > 0;
}

function add_cohort($mysqli, $cohort_ID, $creator_ID, $cohort_name, $cohort_size) {
    if (!record_exists($mysqli, 'COHORT', 'creator_ID', $creator_ID)) {
        return "Error: CREATOR ID does not exist.";
    }

    if (record_exists($mysqli, 'COHORT', 'cohort_ID', $cohort_ID)) {
        return "Error: COHORT already exists.";
    }

    $sql = $mysqli->prepare("INSERT INTO COHORT (cohort_ID, creator_ID, cohort_Name, cohort_Size) VALUES (?, ?, ?, ?)");
    $sql->bind_param("ssss", $cohort_ID, $creator_ID, $cohort_name, $cohort_size);
    
    if ($sql->execute()) {
        return true;
    } else {
        return $sql->error;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($cohort_ID && $creator_ID && $cohort_name && $cohort_size) {
        if (isset($mysqli) && $mysqli) {
            $result = add_cohort($mysqli, $cohort_ID, $creator_ID, $cohort_name, $cohort_size);
            
            if ($result === true) {
                echo "<script>alert('Cohort has been successfully created');</script>";
                echo "<meta http-equiv='refresh' content='0;url=../pages/admin/cohort/add_cohort.html'>";
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