<?php
date_default_timezone_set('Asia/Manila');

require_once 'includes/dbh_inc.php';
require_once 'includes/execute_query_inc.php';
require_once 'includes/error_model_inc.php';

if (isset($_GET['subject_id'])) {
    $subject_ID = $_GET['subject_id'];

    // Fetch the subject details
    $queryFetch = "SELECT * FROM `subject` WHERE `subject_ID` = ?";
    $paramsFetch = [$subject_ID];
    $resultFetch = executeQuery($mysqli, $queryFetch, 's', $paramsFetch);

    if ($resultFetch['success'] && $resultFetch['result']->num_rows > 0) {
        $subject = $resultFetch['result']->fetch_assoc();
    } else {
        echo "Error fetching subject: " . $resultFetch['error'];
        exit;
    }
} elseif (isset($_POST['subject_id'])) {
    $subject_ID = $_POST['subject_id'];
    $subject_Name = $_POST['subject_name'];
    $subject_Description = $_POST['subject_description']; 
    $ay = $_POST['ay'];
    $semester = $_POST['semester'];
    $course_ID = $_POST['course_ID'];
    $year = $_POST['year'];
    $section = $_POST['section'];

    // Update the subject details
    $queryUpdate = "UPDATE `subject` SET 
                    `subject_Name` = ?, 
                    `subject_Description` = ?, 
                    `ay` = ?, 
                    `semester` = ?, 
                    `course_ID` = ?, 
                    `year` = ?, 
                    `section` = ? 
                    WHERE `subject_ID` = ?";
    
    $paramsUpdate = [
        $subject_Name, $subject_Description, $ay, $semester, $course_ID, $year, $section, $subject_ID
    ];

    $resultUpdate = executeQuery($mysqli, $queryUpdate, 'sssssiis', $paramsUpdate);

    if ($resultUpdate['success']) {
        echo "Subject updated successfully!";
    } else {
        echo "Error updating subject: " . $resultUpdate['error'];
    }
    exit;
} else {
    echo "No subject selected for editing.";
    exit;
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Subject</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: baseline;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f0f0;
        }

        #subject_form {
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #subject_form h1 {
            text-align: center;
            margin: 20px;
            padding: 10px;
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
    <div id="subject_form">
        <h1>EDIT SUBJECT</h1>
        <form method="POST" action="edit_subject.php" id="subject_form">
            <input type="hidden" name="subject_id" value="<?php echo $subject['subject_ID']; ?>">

            <label for="subject_name">Subject Name:</label>
            <input type="text" id="subject_name" name="subject_name" value="<?php echo $subject['subject_Name']; ?>" required>

            <label for="subject_description">Subject Description:</label>
            <textarea id="subject_description" name="subject_description"><?php echo $subject['subject_Description']; ?></textarea>

            <label for="ay">Academic Year:</label>
            <input type="text" id="ay" name="ay" value="<?php echo $subject['ay']; ?>" required>

            <label for="semester">Semester:</label>
            <input type="text" id="semester" name="semester" value="<?php echo $subject['semester']; ?>" required>

            <label for="course_ID">Course ID:</label>
            <input type="text" id="course_ID" name="course_ID" value="<?php echo $subject['course_ID']; ?>" required>

            <label for="year">Year:</label>
            <input type="number" id="year" name="year" value="<?php echo $subject['year']; ?>" min="1" max="5" required>

            <label for="section">Section:</label>
            <input type="text" id="section" name="section" value="<?php echo $subject['section']; ?>" required>

            <button type="submit">UPDATE SUBJECT</button>
        </form>
    </div>
</body>
</html>