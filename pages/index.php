<?php
require_once '../php/includes/config_session_inc.php';

if (!isset($_SESSION['user_ID'])) {
    header("Location: login.html");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>

<body>
    <h1>Welcome</h1>
    <p><a href="profile.php">Profile</a></p>
    <p><a href="../php/includes/logout_model_inc.php">Log Out</a></p>
</body>

</html>