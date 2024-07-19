<?php
require_once '../php/includes/config_session_inc.php';
require_once '../php/includes/error_model_inc.php';

if (!isset($_SESSION['user_ID'])) {
    header("Location: login.html");
    exit;
}

if ($_SESSION['user_Role'] != '2') {
    $error_message = "Permission denied. Only a faculty member can access this page.";
    redirectWithError($error_message);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PUP | Faculty's Overview</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="stylesheet" href="../styles/admin_overview.css">
</head>
<body>
    <header class="head">
        <div class="logo">
          <img src="../assets/PUP_logo.png" alt="PUP Logo">
        </div>
        <div class="title">
          <h1>PUP Learning Management System</h1>
        </div>
    </header>

    <div class="container">
        <div class="header">
            <h1>Faculty's Overview</h1>
            <h2>Welcome, Faculty!</h2>
        </div>
        <div class="overview-grid" id="overview-grid">

            <div class="overview-item" onclick="window.location.href='profile.php'">
                <div class="overview-header">
                    <h3>Your Profile</h3>
                </div>
                <div class="overview-description">
                    <p>View and manage your personal profile information and settings.</p>
                </div>
            </div>
            
            <div class="overview-item" onclick="window.location.href='faculty/subject/add_subject.php'">
                <div class="overview-header">
                    <h3>Add Subject</h3>
                </div>
                <div class="overview-description">
                    <p>Add a new subject you hanlde by providing the necessary details.</p>
                </div>
            </div>

            <div class="overview-item" onclick="window.location.href='faculty/subject/view_subject_list.php'">
                <div class="overview-header">
                    <h3>View Subject List</h3>
                </div>
                <div class="overview-description">
                    <p>Browse the complete list of subjects you hanlde, including important details.</p>
                </div>
            </div>
        </div>
        <div class="logout-item" onclick="window.location.href='../../php/includes/logout_model_inc.php'">
            <div class="logout-header">
                <h3>Log Out</h3>
            </div>
        </div> 
    </div>

    <footer id="footer">
        <div>
          <p>&copy; 2021 PUP Learning Management System</p>
        </div>
    </footer>
</body>
</html>                