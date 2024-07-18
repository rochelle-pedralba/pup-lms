<?php
require_once '../../php/includes/config_session_inc.php';
require_once '../../php/includes/error_model_inc.php';

if (!isset($_SESSION['user_ID'])) {
    header("Location: login.html");
    exit;
}

if ($_SESSION['user_Role'] != '1') {
    $error_message = "Permission denied. Only a manager can access this page.";
    redirectWithError($error_message);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="../../../styles/admin_create.css">
</head>

<body>
    <h2>Create Account</h2>
    <form action="../../php/create_account.php" method="POST">
        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="student">Student</option>
            <option value="faculty">Faculty</option>
        </select>
        <br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>

        <label for="user_id">User ID:</label>
        <input type="text" id="user_id" name="user_id" required>
        <br>

        <label for="password">Temporary Password:</label>
        <input type="text" id="password" name="password" required>
        <br>

        <button type="submit">Create Account</button>
    </form>
</body>

</html>