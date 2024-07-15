<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Manila');

require_once '../../../php/includes/dbh_inc.php'; 
require_once '../../../php/includes/execute_query_inc.php';
require_once '../../../php/includes/error_model_inc.php';

function sanitize_input($data) {
    return htmlspecialchars(trim($data));
}

$course_ID = isset($_GET['course_ID']) ? sanitize_input($_GET['course_ID']) : null;

if ($course_ID) {
    $sql = $mysqli->prepare("SELECT creator_ID, cohort_ID, course_Description, college_ID, no_Of_Years FROM COURSE WHERE course_ID = ?");
    $sql->bind_param("s", $course_ID);
    $sql->execute();
    $sql->bind_result($creator_ID, $cohort_ID, $course_desc, $college_ID, $no_of_years);
    $sql->fetch();
    $sql->close();
} else {
    echo "<script>alert('No course ID provided.');</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" type="text/css" href="../../../styles/admin_add.css">
    </head>

    <body>
        <header>
            <div class="logo">
              <img src="../../../assets/PUP_logo.png" alt="PUP Logo">
            </div>
            <div class="title">
              <h1>PUP Learning Management System</h1>
            </div>
        </header>

        <div class="edit_container">
            <form action="../../../php/edit_course.php" method="POST">
                <h2>Edit Course</h2><br>

                <label for="course_ID">Course ID: <p><?= $course_ID ?></p></label><br>
                <input type="hidden" name="course_ID" value="<?= $course_ID ?>">

                <label for="creator_ID">Creator ID:</label>
                <input type="text" id="creator_ID" name="creator_ID" maxlength="12" value="<?= $creator_ID ?>" required><br>
                
                <label for="cohort_ID">Cohort ID:</label>
                <input type="text" id="cohort_ID" name="cohort_ID" maxlength="5" value="<?= $cohort_ID ?>" required><br>

                <label for="course_desc">Course Description:</label>
                <input type="text" id="course_desc" name="course_desc" maxlength="50" value="<?= $course_desc ?>" required><br>

                <label for="college_ID">College ID:</label>
                <input type="text" id="college_ID" name="college_ID" maxlength="10" value="<?= $college_ID ?>" required><br>

                <label for="no_of_years">No. of Years:</label>
                    <select id="no_of_years" name="no_of_years" required>
                        <option value="<?= $no_of_years ?>" selected="<?= $no_of_years ?>"></option> 
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select><br><br>
                
                <button type="submit">Update Course</button>
            </form>
        </div>

        <footer id="footer">
            <div>
              <p>&copy; 2021 PUP Learning Management System</p>
            </div>
        </footer>
    </body>
</html>
