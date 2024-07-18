<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Manila');

require_once 'includes/dbh_inc.php';
require_once 'includes/execute_query_inc.php';
require_once 'includes/error_model_inc.php';
require_once 'includes/config_session_inc.php';

function campusID()
{
  $campus = $_POST['campus'];

  $json_data = file_get_contents('../json/pupcampus.json');
  $campus_data = json_decode($json_data, true);

  foreach ($campus_data as $data) {
    if ($data['cohort_Name'] === $campus) {
      return $data['cohort_ID'];
    }
  }

  return null;
}

$user_ID = $_SESSION['user_ID'] ?? null;

if ($_SERVER["REQUEST_METHOD"] === "POST" && $user_ID) {
  $last_name = $_POST['last_name'];
  $first_name = $_POST['first_name'];
  $middle_name = $_POST['middle_name'];
  $date_of_birth = $_POST['dob'];
  $contact_number = $_POST['mobile'];
  $region = $_POST['regions'];
  $city = $_POST['cities'];
  $country = $_POST['countries'];
  $province = $_POST['provinces'];
  $zip_code = $_POST['zip_code'];
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];
  $campus = $_POST['campus'];

  // $time_created = date("H:i:s");
  // $date_created = date("Y-m-d");
  // $acc_status = '1';

  $hashed_password = password_hash($password, PASSWORD_BCRYPT);

  // Define parameters for each query
  $params_1 = [
    $first_name,
    $last_name,
    $middle_name,
    $date_of_birth,
    $contact_number,
    $region,
    $city,
    $country,
    $province,
    $zip_code,
    $user_ID
  ];

  $params_2 = [
    $hashed_password,
    $user_ID
  ];

  $params_3 = [
    $campus,
    $campus_id = campusID(),
    $user_ID
  ];

  // Execute queries
  $query_1 = "UPDATE user_information 
              SET first_Name = ?, last_Name = ?, middle_Name = ?, date_Of_Birth = ?, mobile_Number = ?, region = ?, city = ?, country = ?, province = ?, zip_Code = ?
              where user_ID = ?";

  $queryResult_1 = executeQuery($mysqli, $query_1, "sssssssssss", $params_1);

  if (!$queryResult_1['success']) {
    $error_message = "An internal error has occured. Please try again later or contact the administrator";
    redirectWithError($error_message);
    exit;
  }

  $query_2 = "UPDATE user_access
              SET user_Password = ?
              WHERE user_ID = ?";

  $queryResult_2 = executeQuery($mysqli, $query_2, "ss", $params_2);

  if (!$queryResult_2['success']) {
    $error_message = "An internal error has occured. Please try again later or contact the administrator";
    redirectWithError($error_message);
    exit;
  }

  // $query_3 = "UPDATE cohort 
  //             SET cohort_Name = ?, cohort_ID = ? 
  //             WHERE user_ID = ?";

  // $queryResult_3 = executeQuery($mysqli, $query_3, "sss", $params_3);

  // if (!$queryResult_3['success']) {
  //   $error_message = "An internal error has occured. Please try again later or contact the administrator";
  //   redirectWithError($error_message);
  //   exit;
  // }

  $mysqli->close();

  echo "<script>alert('User has been successfully registered');</script>";
  echo "<meta http-equiv='refresh' content='0;url=../pages/login.html'>";
  exit;
}

$error_message = "Form submission method not allowed";
redirectWithError($error_message);
exit;

