<?php
include_once '../includes/config_session_inc.php';
include_once '../includes/dbh_inc.php';
include_once '../includes/error_model_inc.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $retype_new_password = $_POST['retype_new_password'];

    if ($new_password !== $retype_new_password) {
        echo "New passwords do not match.";
        exit;
    }

    $sql = "SELECT user_ID, reset_Expires FROM password_resets WHERE reset_Token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $reset_expires);
    $stmt->fetch();

    if ($stmt->num_rows === 0 || new DateTime() > new DateTime($reset_expires)) {
        echo "Invalid or expired token.";
        exit;
    }

    // Hash the new password
    $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
    $date_Created = date('Y-m-d');
    $expiry_Days = 90; // Example expiry days
    $login_Attempt = 0;

    // Start transaction
    $conn->begin_transaction();

    try {
        // Get current password for logging
        $sql_current = "SELECT user_Password FROM user_access WHERE user_ID = ?";
        $stmt_current = $conn->prepare($sql_current);
        $stmt_current->bind_param("s", $user_id);
        $stmt_current->execute();
        $stmt_current->store_result();
        $stmt_current->bind_result($current_password);
        $stmt_current->fetch();

        // Update password in user_access table
        $sql_update = "UPDATE user_access SET user_Password = ? WHERE user_ID = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ss", $hashed_new_password, $user_id);
        $stmt_update->execute();

        // Log the change in password_maintenance table
        $sql_log = "INSERT INTO password_maintenance (user_ID, current_Password, previous_Password, date_Created, expiry_Days, login_Attempt) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_log = $conn->prepare($sql_log);
        $stmt_log->bind_param("ssssii", $user_id, $hashed_new_password, $current_password, $date_Created, $expiry_Days, $login_Attempt);
        $stmt_log->execute();

        // Delete the token from password_resets table
        $sql_delete = "DELETE FROM password_resets WHERE reset_Token = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("s", $token);
        $stmt_delete->execute();

        // Commit transaction
        $conn->commit();

        echo "Password has been reset successfully.";
    } catch (Exception $e) {
        // Rollback transaction if any error occurs
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    $stmt_current->close();
    $stmt_update->close();
    $stmt_log->close();
    $stmt_delete->close();
    $conn->close();
}
?>