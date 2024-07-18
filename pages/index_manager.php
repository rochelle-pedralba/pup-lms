<?php
require_once '../php/includes/config_session_inc.php';
require_once '../php/includes/error_model_inc.php';

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
    <title>PUP eMabini | Home</title>
</head>

<body>
    <h1>Welcome, Admin</h1>
    <p><a href="profile.php">Profile</a></p>
    <p><a href="edit_role.php">Edit Role of a User</a></p>
    <p><a href="admin/create_account.php">Create an account</a></p>
    <p><a href="../php/includes/logout_model_inc.php">Log Out</a></p>
</body>

</html>