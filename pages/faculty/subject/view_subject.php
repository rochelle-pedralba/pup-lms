<?php

  // Itong tatlo for the connection lang ng db, dito sa file na to lahat ng pang display ng data sa sub
  require_once '../../../php/includes/dbh_inc.php'; // for db connection
  require_once '../../../php/includes/execute_query_inc.php';
  require_once '../../../php/includes/error_model_inc.php';

  $creator_ID = "2021XXXXXXXX";

  // creator_ID yung user_ID ng professor
  // Palagyan nalang din muna sa db niyo sa subject table, di ko pa naiinform sa grp nila jasper

  $querySubject = "SELECT subject_Name, subject_ID FROM subject WHERE creator_ID = ?";
  $queryResult = executeQuery($mysqli, $querySubject, "s", [$creator_ID]);

  if (!$queryResult['success']) {
    $error_message = "An error has occured. Please try again later or contact the administrator.";
    redirectWithError($error_message);
    exit;
  }

  $row = $queryResult['result']->fetch_assoc();

  $subjectName = $row['subject_Name'];
  $subjectID = $row['subject_ID'];
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

      h1, h3 {
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

  <!-- Yung year and section, A.Y. sa subject table din ifefetch, palagyan nalang ulit sa column niyo huhu -->
  <body>
    <div>
      <div>
        <h4>Professor Name</h4>
      </div>
      <div>
        <h3 class="year-section-container">Year and Section</h3>
        <h1><?php echo $subjectID.': '.$subjectName; ?></h1>
        <h3>Semester  A.Y.</h3>
      </div>
      <div class="button-container">
        <button>Enroll Student</button>
      </div>
    </div>

    <div class="placeholder-container">
      <div class="content-placeholder">
      </div>
    </div>
  </body>
</html>