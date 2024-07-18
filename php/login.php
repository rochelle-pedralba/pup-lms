<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Manila');

require_once 'includes/dbh_inc.php';
require_once 'includes/execute_query_inc.php';
require_once 'includes/config_session_inc.php';
require_once 'includes/error_model_inc.php';

function getUser(object $mysqli, string $user_ID): ?array
{
    $query = "SELECT ua.*, ui.account_Status, ur.user_Role, ua.first_Access, pm.login_Attempt, pm.lockout_Time
              FROM USER_ACCESS ua
              JOIN USER_INFORMATION ui ON ua.user_ID = ui.user_ID
              LEFT JOIN USER_ROLE ur ON ua.user_ID = ur.user_ID
              LEFT JOIN PASSWORD_MAINTENANCE pm ON ua.user_ID = pm.user_ID
              WHERE ua.user_ID = ? 
              AND (ui.account_Status = ? OR ui.account_Status = ?)";

    $queryResult = executeQuery($mysqli, $query, "sss", [$user_ID, '1', '0']);

    if (!$queryResult['success']) {
        $error_message = "An error has occurred. Please try again later or contact the administrator.";
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
        $error_message = "An internal error has occurred. Please try again later or contact the administrator.";
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
        $error_message = "An internal error has occurred. Please try again later or contact the administrator.";
        redirectWithError($error_message);
        exit;
    }
}

function incrementLoginAttempt(object $mysqli, string $user_ID)
{
    $query = "UPDATE PASSWORD_MAINTENANCE 
              SET login_Attempt = login_Attempt + 1, 
                  lockout_Time = CASE WHEN login_Attempt >= 2 THEN NOW() ELSE lockout_Time END 
              WHERE user_ID = ?";
    $params = [$user_ID];
    $result = executeQuery($mysqli, $query, "s", $params);

    if (!$result['success']) {
        $error_message = "An internal error has occurred. Please try again later or contact the administrator.";
        redirectWithError($error_message);
        exit;
    }
}

function resetLoginAttempts(object $mysqli, string $user_ID)
{
    $query = "UPDATE PASSWORD_MAINTENANCE 
              SET login_Attempt = 0, 
                  lockout_Time = NULL 
              WHERE user_ID = ?";
    $params = [$user_ID];
    $result = executeQuery($mysqli, $query, "s", $params);

    if (!$result['success']) {
        $error_message = "An internal error has occurred. Please try again later or contact the administrator.";
        redirectWithError($error_message);
        exit;
    }
}

function isAccountLocked(?array $user): bool
{
    if ($user && isset($user['lockout_Time'])) {
        $lockout_Time = strtotime($user['lockout_Time']);
        $current_time = time();
        $lock_duration = 24 * 60 * 60; // 24 hours

        if (($current_time - $lockout_Time) < $lock_duration) {
            return true;
        }
    }
    return false;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_ID = $_POST['user_ID'];
    $password = $_POST["password"];

    $user = getUser($mysqli, $user_ID);

    if ($user === null) {
        echo "<script>alert('Invalid user ID or password.');
              window.location.href = '../pages/login.html'; 
              </script>";
        exit;
    }

    if (isAccountLocked($user)) {
        echo "<script>alert('Account is locked due to too many failed login attempts. Please try again after 24 hours.');
              window.location.href = '../pages/login.html'; 
              </script>";
        exit;
    }

    $attempts_left = 3 - $user['login_Attempt'];

    if (!verifyPassword($password, $user)) {
        incrementLoginAttempt($mysqli, $user_ID);

        if ($attempts_left - 1 == 1) {
            echo "<script>alert('Password incorrect. You have 1 attempt left. Consider resetting your password.');
                  window.location.href = '../pages/login.html'; 
                  </script>";
        } else {
            echo "<script>alert('Password incorrect. You have $attempts_left attempts left.');
                  window.location.href = '../pages/login.html'; 
                  </script>";
        }
        exit;
    }

    if ($user['account_Status'] != '1') {
        $error_message = "Your account must be activated. Please contact the administrator.";
        redirectWithError($error_message);
        exit;
    }

    resetLoginAttempts($mysqli, $user_ID);

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
