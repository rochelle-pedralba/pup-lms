<?php
require_once 'includes/dbh_inc.php';
require_once 'includes/execute_query_inc.php';
require_once 'includes/config_session_inc.php';
require_once 'includes/error_model_inc.php';

function getCurrentRole($mysqli, $user_ID)
{
    $query = "SELECT user_Role FROM USER_ROLE WHERE user_ID = ?";
    $queryResult = executeQuery($mysqli, $query, "s", [$user_ID]);
    return $queryResult['result'] ? $queryResult['result']->fetch_assoc() : null;
}

function updateUserRole($mysqli, $user_ID, $new_Role, $current_Role)
{
    $query = "UPDATE USER_ROLE 
              SET user_Role = ?, previous_Role = ?, date_Change = ? 
              WHERE user_ID = ?";
    $date_Change = date("Y-m-d");
    $success = executeQuery($mysqli, $query, "ssss", [$new_Role, $current_Role, $date_Change, $user_ID]);
    return $success;
}

if ($_SESSION['user_Role'] !== '1') {
    $error_message = "Permission denied. Only a manager can modify user role.";
    redirectWithError($error_message);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['user_ID_hidden']) && isset($_POST['new_Role'])) {
    $user_ID = $_POST["user_ID_hidden"];
    $new_Role = $_POST["new_Role"];

    $current_Role_Data = getCurrentRole($mysqli, $user_ID);
    if (!$current_Role_Data) {
        $error_message = "User not found.";
        redirectWithError($error_message);
        exit;
    }

    $current_Role = $current_Role_Data['user_Role'];

    $success = updateUserRole($mysqli, $user_ID, $new_Role, $current_Role);

    $mysqli->close();
    if ($success) {
        echo "<script>alert('Role updated successfully');
            window.location.href = '../pages/edit_role.php';
        </script>";
        exit;
    } else {
        $error_message = "Failed to update user role. Please try again later or contact the administrator.";
        redirectWithError($error_message);
        exit;
    }

} else {
    $error_message = "Invalid submission method. Form submission method not allowed or missing parameters.";
    redirectWithError($error_message);
    exit;
}
