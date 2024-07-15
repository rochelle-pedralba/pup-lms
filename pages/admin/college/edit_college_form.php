<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Manila');

require_once '../../../php/includes/dbh_inc.php'; 
require_once '../../../php/includes/execute_query_inc.php';
require_once '../../../php/includes/error_model_inc.php';

function sanitize_input($data) {
    return htmlspecialchars(trim($data));
}

$college_ID = isset($_GET['college_ID']) ? sanitize_input($_GET['college_ID']) : null;

if ($college_ID) {
    $sql = $mysqli->prepare("SELECT college_name, description FROM COLLEGE WHERE college_ID = ?");
    $sql->bind_param("s", $college_ID);
    $sql->execute();
    $sql->bind_result($college_name, $college_desc);
    $sql->fetch();
    $sql->close();
} else {
    echo "<script>alert('No college ID provided.');</script>";
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
            <form action="../../../php/edit_college.php" method="POST">
                <h2>Edit College</h2><br>

                <label for="college_ID">College ID: <p><?= $college_ID ?></p></label>
                <input type="hidden" name="college_ID" value="<?= $college_ID ?>"><br>

                <label for="college_name">College Name:</label>
                <input type="text" id="college_name" name="college_name" maxlength="50" value="<?= $college_name ?>" required><br>

                <label for="college_desc">College Description:</label>
                <input type="text" id="college_desc" name="college_desc" maxlength="100" value="<?= $college_desc ?>" required><br>

                <button type="submit">Update College</button>
            </form>
        </div>

        <footer id="footer">
            <div>
              <p>&copy; 2021 PUP Learning Management System</p>
            </div>
        </footer>
    </body>
</html>
