<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Manila');

require_once '../../../php/includes/dbh_inc.php'; 
require_once '../../../php/includes/execute_query_inc.php';
require_once '../../../php/includes/error_model_inc.php';

function sanitize_input($data) {
    return htmlspecialchars(trim($data));
}

$cohort_ID = 'pupsj';

if ($cohort_ID) {
    $sql = $mysqli->prepare("SELECT creator_ID, cohort_Name, cohort_Size FROM COHORT WHERE cohort_ID = ?");
    $sql->bind_param("s", $cohort_ID);
    $sql->execute();
    $sql->bind_result($creator_ID, $cohort_name, $cohort_size);
    $sql->fetch();
    $sql->close();
} else {
    echo "<script>alert('No cohort ID provided.');</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" type="text/css" href="../../../styles/admin_add.css">
    </head>

    <body>
        <!-- <header>
            <div class="logo">
              <img src="../../../assets/PUP_logo.png" alt="PUP Logo">
            </div>
            <div class="title">
              <h1>PUP Learning Management System</h1>
            </div>
        </header> -->

    
        <div class="edit_container">
            <form action="../../../php/edit_cohort.php" method="POST">
                <h2>Edit Cohort</h2>

                <label for="cohort_ID">Cohort ID: <?= $cohort_ID ?></label><br><br>
                <input type="hidden" name="cohort_ID" value="<?= $cohort_ID ?>">

                <label for="creator_ID">Creator ID:</label>
                <input type="text" id="creator_ID" name="creator_ID" maxlength="12" value="<?= $creator_ID ?>" required><br>

                <label for="cohort_name">Cohort Name:</label>
                <input type="text" id="cohort_name" name="cohort_name" maxlength="50" value="<?= $cohort_name ?>" required><br>

                <label for="cohort_size">Number of Years:</label>
                <input type="text" id="cohort_size" name="cohort_size" maxlength="3" value="<?= $cohort_size ?>" required><br>

                <button type="submit">Update Cohort</button>
            </form>
        </div>

        <!-- <footer id="footer">
            <div>
              <p>&copy; 2021 PUP Learning Management System</p>
            </div>
        </footer> -->
    </body>
</html>
