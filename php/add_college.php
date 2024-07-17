<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Manila');

require_once 'includes/dbh_inc.php'; 
require_once 'includes/execute_query_inc.php';
require_once 'includes/error_model_inc.php';

function sanitize_input($data) {
    return htmlspecialchars(trim($data));
}

$college_ID = isset($_POST['college_ID']) ? sanitize_input($_POST['college_ID']) : null;
$college_name = isset($_POST['college_name']) ? sanitize_input($_POST['college_name']) : null;
$college_desc = isset($_POST['college_desc']) ? sanitize_input($_POST['college_desc']) : null;

function record_exists($mysqli, $table, $column, $value) {
    $sql = $mysqli->prepare("SELECT COUNT(*) FROM $table WHERE $column = ?");
    $sql->bind_param("s", $value);
    $sql->execute();
    $sql->bind_result($count);
    $sql->fetch();
    return $count > 0;
}

function add_cohort($mysqli, $college_ID, $college_name, $college_desc) {
    if (record_exists($mysqli, 'COLLEGE', 'college_ID', $college_ID)) {
        return "Error: COLLEGE already exists.";
    }

    $sql = $mysqli->prepare("INSERT INTO COLLEGE (college_ID, college_Name, description) VALUES (?, ?, ?)");
    $sql->bind_param("sss", $college_ID, $college_name, $college_desc);
    
    if ($sql->execute()) {
        return true;
    } else {
        return $sql->error;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($college_ID && $college_name && $college_desc) {
        if (isset($mysqli) && $mysqli) {
            $result = add_cohort($mysqli, $college_ID, $college_name, $college_desc);
            
            if ($result === true) {
                echo "<script>alert('College has been successfully created');</script>";
                echo "<meta http-equiv='refresh' content='0;url=../pages/admin/college_overview.php'>";
                exit;
            } else {
                echo "<script>alert('$result');</script>";
                echo "<meta http-equiv='refresh' content='0;url=../pages/admin/college_overview.php'>";
                exit;
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