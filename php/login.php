<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Manila');

require_once 'includes/dbh_inc.php';
require_once 'includes/execute_query_inc.php';
require_once 'includes/config_session_inc.php';
require_once 'includes/error_model_inc.php';

session_start();

function getUser(object $mysqli, string $user_ID): ?array
{
    $query = "SELECT ua.*, ui.account_Status, ur.user_Role, ua.first_Access
              FROM USER_ACCESS ua
              JOIN USER_INFORMATION ui ON ua.user_ID = ui.user_ID
              LEFT JOIN USER_ROLE ur ON ua.user_ID = ur.user_ID
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

function incrementLoginAttempt()
{
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 1;
    } else {
        $_SESSION['login_attempts']++;
    }
}

function getLoginAttempts()
{
    return $_SESSION['login_attempts'] ?? 0;
}

function resetLoginAttempts()
{
    unset($_SESSION['login_attempts']);
}

function lockAccount()
{
    $_SESSION['account_locked'] = true;
    $_SESSION['lockout_time'] = time();
}

function isAccountLocked()
{
    $maxAttempts = 3;
    $lockoutTime = 3600; // 1 hour in seconds

    if (getLoginAttempts() >= $maxAttempts) {
        if (!isset($_SESSION['account_locked'])) {
            lockAccount();
        } elseif (time() - $_SESSION['lockout_time'] > $lockoutTime) {
            resetLoginAttempts();
            unset($_SESSION['account_locked']);
        }
    }

    return isset($_SESSION['account_locked']) && (time() - $_SESSION['lockout_time'] < $lockoutTime);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_ID = $_POST['user_ID'];
    $password = $_POST["password"];

    if (isAccountLocked()) {
        echo "<script>alert('Account locked due to too many failed login attempts. Please try again after 1 hour.');
              window.location.href = '../pages/login.html'; 
              </script>";
        exit;
    }

    $user = getUser($mysqli, $user_ID);

    if ($user === null || !verifyPassword($password, $user)) {
        incrementLoginAttempt();
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

    resetLoginAttempts();

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
?>
