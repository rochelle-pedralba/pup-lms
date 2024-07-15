<?php

// Itong tatlo for the connection lang ng db, dito sa file na to lahat ng pang display ng data sa sub
require_once '../../../php/includes/dbh_inc.php'; // for db connection
require_once '../../../php/includes/execute_query_inc.php';
require_once '../../../php/includes/error_model_inc.php';

$user_ID = "FA0123561212";

// change into something like FA0123561212
// user_ID yung user_ID ng professor
// Palagyan nalang din muna sa db niyo sa subject table, di ko pa naiinform sa grp nila jasper

// s for subject, u for user_information
$querySubject = "SELECT s.course_ID, s.subject_Name, s.subject_ID, s.semester, s.ay, s.year, s.section, s.subject_Description, u.first_Name, u.last_Name FROM subject s JOIN user_information u ON s.user_ID = u.user_ID WHERE s.user_ID = ?";
$queryResult = executeQuery($mysqli, $querySubject, "s", [$user_ID]);

if (!$queryResult['success']) {
    $error_message = "An error has occured. Please try again later or contact the administrator.";
    redirectWithError($error_message);
    exit;
}

$row = $queryResult['result']->fetch_assoc();

$subjectName = $row['subject_Name'];
$subjectID = $row['subject_ID'];
$semester = $row['semester'];
$academicYear = $row['ay'];
$subjectDescription = $row['subject_Description'];
$ayYear1 = substr($academicYear, 0, 2);
$ayYear2 = substr($academicYear, 2, 2);
$year = $row['year'];
$section = $row['section'];
$courseID = $row['course_ID'];
$firstName = $row['first_Name'];
$lastName = $row['last_Name'];
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        h1,
        h3 {
            text-align: center;
        }

        h4 {
            text-align: right !important;
            margin-right: 50px;
            margin-top: 20px;
        }

        .button-container {
            display: flex;
            justify-content: right !important;
            margin-right: 50px;
            margin-bottom: 20px;
        }

        .year-section-container {
            margin-top: 20px;
        }

        .placeholder-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            width: 100%;
        }

        .content-placeholder {
            display: flex;
            flex-direction: column;
            align-self: center;
            background-color: gray;
            height: 500px;
            width: 95%;
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <div>
        <div>
            <h4>Professor: <?php echo htmlspecialchars($firstName) . " " . htmlspecialchars($lastName); ?></h4>

        </div>
        
        <div>
            <h3 class="year-section-container"><?php echo htmlspecialchars($courseID) . " " . htmlspecialchars($year) . "-" . htmlspecialchars($section); ?></h3>
            <h1><?php echo htmlspecialchars($subjectID) . ': ' . htmlspecialchars($subjectName); ?></h1>
            <h3>Semester <?php echo htmlspecialchars($semester) . " A.Y. " . htmlspecialchars($ayYear1) . " - " . htmlspecialchars($ayYear2); ?></h3>
        </div>

        <div class="button-container">
            <button>View Classmates</button>
            <button href="edit_subject.php?subjectID=<?php echo urlencode($subjectID); ?>" class="btn btn-primary">Edit</a>
        </div>
    </div>

    <div class="placeholder-container">
        <div class="content-placeholder">
            <h3><?php echo $subjectDescription ?></h3>
        </div>
    </div>
</body>

</html>