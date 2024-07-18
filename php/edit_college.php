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
    $college_ID = isset($_POST['college_ID']) ? sanitize_input($_POST['college_ID']) : null;
    $college_name = isset($_POST['college_name']) ? sanitize_input($_POST['college_name']) : null;
    $college_desc = isset($_POST['college_desc']) ? sanitize_input($_POST['college_desc']) : null;

    if ($college_ID && $college_name && $college_desc) {
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
            echo "<script>alert('Error: COLLEGE ID does not exist.');</script>";
        } else {
            $sql = $mysqli->prepare("UPDATE COLLEGE SET college_Name = ?, description = ? WHERE college_ID = ?");
            $sql->bind_param("sss", $college_name, $college_desc, $college_ID);
            if ($sql->execute()) {
                echo "<script>alert('College has been successfully updated.');</script>";
                echo "<meta http-equiv='refresh' content='0;url=../pages/admin/college_overview.php'>";
                exit;
            } else {
                echo "<script>alert('Error: " . $sql->error . "');</script>";
                echo "<meta http-equiv='refresh' content='0;url=../pages/admin/college_overview.php?>";
            }
            $sql->close();
        }
    } else {
        echo "<script>alert('Error: All fields are required.');</script>";
    }
}
?>
