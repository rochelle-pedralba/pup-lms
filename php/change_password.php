<?php
include_once '../includes/config_session_inc.php';
include_once '../includes/dbh_inc.php';
include_once '../includes/error_model_inc.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $retype_new_password = $_POST['retype_new_password'];

    session_start();
    $user_id = $_SESSION['user_id'];

    if ($new_password !== $retype_new_password) {
        echo "New passwords do not match.";
        exit;
    }

    // Check if old password matches
    $sql = "SELECT user_Password FROM user_access WHERE user_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($hashed_old_password);
    $stmt->fetch();

    if (password_verify($old_password, $hashed_old_password)) {
        // Old password matches, proceed to update
        $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
        $date_Created = date('Y-m-d');
        $expiry_Days = 90; // Example expiry days
        $login_Attempt = 0;

        // Start transaction
        $conn->begin_transaction();

        try {
            // Update password in user_access table
            $sql_update = "UPDATE user_access SET user_Password = ? WHERE user_ID = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ss", $hashed_new_password, $user_id);
            $stmt_update->execute();

            // Log the change in password_maintenance table
            $sql_log = "INSERT INTO password_maintenance (user_ID, current_Password, previous_Password, date_Created, expiry_Days, login_Attempt) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_log = $conn->prepare($sql_log);
            $stmt_log->bind_param("ssssii", $user_id, $hashed_new_password, $hashed_old_password, $date_Created, $expiry_Days, $login_Attempt);
            $stmt_log->execute();

            // Commit transaction
            $conn->commit();

            echo "Password changed successfully.";
        } catch (Exception $e) {
            // Rollback transaction if any error occurs
            $conn->rollback();
            echo "Error: " . $e->getMessage();
        }

        $stmt_update->close();
        $stmt_log->close();
    } else {
        echo "Old password does not match.";
    }

    $stmt->close();
    $conn->close();
}
?>