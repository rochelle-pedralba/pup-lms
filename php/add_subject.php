<?php

require_once 'includes/config_session_inc.php';

if (!isset($_SESSION['user_ID'])) {
    header("Location: login.html");
    exit;
}

date_default_timezone_set('Asia/Manila');

$dbhost = "localhost:3307";
$dbuser = "root";
$dbpass = "";
$dbname = "pup_lms";

$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

$user_ID = $_SESSION['user_ID'];

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

require_once 'includes/execute_query_inc.php'; // Assuming this file contains your executeQuery function

$insertSuccess = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming these are your form field names
    $cohort_ID = $_POST['cohort_ID'];
    $subject_ID = $_POST['subject_ID'];
    $subject_Name = $_POST['subject_name'];
    $subject_Description = $_POST['subject_description'];
    $ay = $_POST['ay'];
    $semester = $_POST['semester'];
    $course_ID = $_POST['course_ID'];
    $year = $_POST['year'];
    $section = $_POST['section'];

    // Insert query using prepared statements to prevent SQL injection
    $queryInsert = "INSERT INTO `subject`
                    (`user_ID`, `cohort_ID`, `subject_ID`, `subject_Name`, `subject_Description`, `ay`, `semester`, `course_ID`, `year`, `section`) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $insertResult = executeQuery($mysqli, $queryInsert, "ssssssssss", [$user_ID, $cohort_ID, $subject_ID, $subject_Name, $subject_Description, $ay, $semester, $course_ID, $year, $section]);

    if ($insertResult) {
        $insertSuccess = true;
    } else {
        echo "Error: " . $mysqli->error;
    }
}

$mysqli->commit();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Subject</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f0f0;
        }
        #subject_form, .success-container {
            text-align: center;
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .success-container h1 {
            color: maroo;
            margin-bottom: 20px;
        }
        .success-container p {
            margin-bottom: 20px;
        }
        .success-container a {
            display: inline-block;
            background-color: #118613;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
        }
        .success-container a:hover {
            background-color: #0f7011;
        }
        label {
            font-weight: bold;
            padding: 5px;
        }
        input, select, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
            margin-top: 10px;
            margin-bottom: 10px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        textarea {
            height: 80px;
            resize: vertical;
        }
        button {
            background-color: #118613;
            justify-content: center;
            color: white;
            border: none;
            padding: 10px 60px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <?php if ($insertSuccess): ?>
        <div class="success-container">
            <h1>Record Inserted Successfully!</h1>
            <p>Your record has been added to the database.</p>
        </div>
    <?php endif; ?>
    <script>
        setTimeout(function() {
        window.location.href = '../pages/faculty/subject/subject_view_list.php';
            }, 2000);
    </script>
</body>
</html>
