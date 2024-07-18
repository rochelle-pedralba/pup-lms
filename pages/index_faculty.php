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
    <title>PUP eMabini | Home</title>
</head>

<body>
    <h1>Welcome, faculty</h1>
    <p><a href="faculty/subject/add_subject.html">Add a subject</a></p>
    <p><a href="faculty/subject/enroll_subject.php">Enroll a subject</a></p>
    <p><a href="faculty/subject/view_subject.php">View subjects</a></p>
    <p><a href="faculty/subject/faculty_page_lectures.php">Lectures</a></p>

    <p><a href="../php/includes/logout_model_inc.php">Log Out</a></p>
</body>

</html>