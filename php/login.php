<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Manila');

require_once 'includes/dbh_inc.php';
require_once 'includes/execute_query_inc.php';
require_once 'includes/config_session_inc.php';
require_once 'includes/error_model_inc.php';

function getUser(object $mysqli, string $user_ID): ?array
{
    $query = "SELECT ua.*, ui.account_Status, ur.user_Role, ua.first_Access, pm.login_Attempt
              FROM USER_ACCESS ua
              JOIN USER_INFORMATION ui ON ua.user_ID = ui.user_ID
              LEFT JOIN USER_ROLE ur ON ua.user_ID = ur.user_ID
              LEFT JOIN PASSWORD_MAINTENANCE pm ON ua.user_ID = pm.user_ID
              WHERE ua.user_ID = ? 
              AND (ui.account_Status = ? OR ui.account_Status = ?)";

    $queryResult = executeQuery($mysqli, $query, "sss", [$user_ID, '1', '0']);

    if (!$queryResult['success']) {
        $error_message = "An error has occured. Please try again later or contact the administrator.";
        redirectWithError($error_message);
        exit;
    }

    return $queryResult['result'] ? $queryResult['result']->fetch_assoc() : null;
}

function verifyPassword(string $password, ?array $user): bool
{
    if ($user && password_verify($password, $user["user_Password"])) {
        return true;
    }
    return false;
}

function updateLastAccess(object $mysqli, string $user_ID)
{
    $query = "UPDATE user_access SET last_Access = ?, time_Access = ? WHERE user_ID = ?";
    $current_date = date('Y-m-d');
    $current_time = date('H:i:s');
    $params_update_access = [$current_date, $current_time, $user_ID];
    $update_result = executeQuery($mysqli, $query, "sss", $params_update_access);

    if (!$update_result['success']) {
        $error_message = "An internal error has occured. Please try again later or contact the administrator.";
        redirectWithError($error_message);
        exit;
    }
}

function setFirstAccess(object $mysqli, string $user_ID)
{
    $query = "UPDATE user_access SET first_Access = ? WHERE user_ID = ?";
    $current_date = date('Y-m-d');
    $params_update_access = [$current_date, $user_ID];
    $update_result = executeQuery($mysqli, $query, "ss", $params_update_access);

    if (!$update_result['success']) {
        $error_message = "An internal error has occured. Please try again later or contact the administrator.";
        redirectWithError($error_message);
        exit;
    }
}

function updateLoginAttempt(object $mysqli, string $user_ID, int $attempts)
{
    $query = "UPDATE PASSWORD_MAINTENANCE SET login_Attempt = ? WHERE user_ID = ?";
    $params_update_attempt = [$attempts, $user_ID];
    $update_result = executeQuery($mysqli, $query, "is", $params_update_attempt);

    if (!$update_result['success']) {
        $error_message = "An internal error has occured. Please try again later or contact the administrator.";
        redirectWithError($error_message);
        exit;
    }
}

function isAccountLocked(object $mysqli, string $user_ID): bool
{
    $query = "SELECT login_Attempt, last_Access, time_Access FROM USER_ACCESS WHERE user_ID = ?";
    $queryResult = executeQuery($mysqli, $query, "s", [$user_ID]);

    if (!$queryResult['success']) {
        $error_message = "An internal error has occured. Please try again later or contact the administrator.";
        redirectWithError($error_message);
        exit;
    }

    $result = $queryResult['result']->fetch_assoc();

    if ($result['login_Attempt'] >= 3) {
        $lastAccessTime = strtotime($result['last_Access'] . ' ' . $result['time_Access']);
        if ((time() - $lastAccessTime) < 3600) {
            return true;
        } else {
            updateLoginAttempt($mysqli, $user_ID, 0);
        }
    }
    return false;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_ID = $_POST['user_ID'];
    $password = $_POST["password"];

    if (isAccountLocked($mysqli, $user_ID)) {
        echo "<script>alert('Account locked due to too many failed login attempts. Please try again after 1 hour.');
              window.location.href = '../pages/login.html'; 
              </script>";
        exit;
    }

    $user = getUser($mysqli, $user_ID);

    if ($user === null || !verifyPassword($password, $user)) {
        $loginAttempts = $user['login_Attempt'] ?? 0;
        updateLoginAttempt($mysqli, $user_ID, $loginAttempts + 1);
        echo "<script>alert('Password incorrect. Please try again.');
              window.location.href = '../pages/login.html'; 
              </script>";
        exit;
    }

    if ($user['account_Status'] != '1') {
        $error_message = "Your account must be activated. Please contact the administrator.";
        redirectWithError($error_message);
        exit;
    }

    session_start();
    $_SESSION['user_ID'] = $user["user_ID"];
    $_SESSION["user_Role"] = $user["user_Role"];

    updateLastAccess($mysqli, $user_ID);

    if ($user['first_Access'] === null) {
        setFirstAccess($mysqli, $user_ID);
        header("Location: ../pages/registration.html");
        $mysqli->close();
        exit;
    }

    if ($_SESSION["user_Role"] === '1') {
        header("Location: ../pages/index_manager.php");
        $mysqli->close();
        exit;
    }

    header("Location: ../pages/index.php");
    $mysqli->close();
    exit;
}

$error_message = "Invalid submission method. Form submission method not allowed";
redirectWithError($error_message);
exit;