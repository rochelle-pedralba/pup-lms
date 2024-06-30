<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Manila');

require_once 'includes/dbh_inc.php';
require_once 'includes/execute_query_inc.php';
require_once 'includes/error_model_inc.php';

function checkUserID(object $mysqli, string $user_ID): bool
{
  $query = "SELECT * FROM user_information WHERE user_ID = ?;";
  $queryResult = executeQuery($mysqli, $query, "s", [$user_ID]);

  return $queryResult['success'] && $queryResult['result']->num_rows > 0;
}

function checkEmailAddress(object $mysqli, string $email): bool
{
  $query = "SELECT * FROM user_information WHERE email_address = ?;";
  $queryResult = executeQuery($mysqli, $query, "s", [$email]);

  return $queryResult['success'] && $queryResult['result']->num_rows > 0;
}

function checkContactNumber(object $mysqli, string $contact_number): bool
{
  $query = "SELECT * FROM user_information WHERE mobile_number = ?;";
  $queryResult = executeQuery($mysqli, $query, "s", [$contact_number]);

  return $queryResult['success'] && $queryResult['result']->num_rows > 0;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $last_name = $_POST['last_name'];
  $first_name = $_POST['first_name'];
  $middle_name = $_POST['middle_name'];
  $date_of_birth = $_POST['dob'];
  $contact_number = $_POST['mobile'];
  $region = $_POST['region'];
  $city = $_POST['city'];
  $country = $_POST['country'];
  $province = $_POST['province'];
  $zip_code = $_POST['zip_code'];
  $user_ID = $_POST['user_ID'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];
  $role = $_POST['role'];

  $time_created = date("H:i:s");
  $date_created = date("Y-m-d");
  $id_number = $_POST['user_ID'];
  $acc_status = '1';

  if (checkContactNumber($mysqli, $contact_number)) {
    $error_message = "The contact number is already connected to an existing account";
    redirectWithError($error_message);
    exit;
  }

  if (checkUserID($mysqli, $user_ID)) {
    $error_message = "User ID already exists";
    redirectWithError($error_message);
    exit;
  }

  if (checkEmailAddress($mysqli, $email)) {
    $error_message = "The email address is already connected to an existing account";
    redirectWithError($error_message);
    exit;
  }

  if (strlen($password) < 8) {
    $errors[] = 'Password must be at least 8 characters long';
  }

  if (!preg_match('/[A-Z]/', $password)) {
    $errors[] = 'Password must contain at least one uppercase letter';
  }

  if (!preg_match('/[a-z]/', $password)) {
    $errors[] = 'Password must contain at least one lowercase letter';
  }

  if (!preg_match('/[0-9]/', $password)) {
    $errors[] = 'Password must contain at least one number';
  }

  if (!preg_match('/[!@#$%^&*()_+\\-=\\[\\]{};:\'",.<>\\/?|\\\\]/', $password)) {
    $errors[] = 'Password must contain at least one special character';
  }

  if ($password !== $confirm_password) {
    $error_message = "Passwords do not match";
    redirectWithError($error_message);
    exit;
  }

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
    $user_ID,
    $email,
    $time_created,
    $date_created,
    $id_number,
    $acc_status
  ];

  $params_2 = [
    $user_ID,
    $role,
    $date_created
  ];

  $params_3 = [
    $user_ID,
    $hashed_password
  ];

  // Execute queries
  $query_1 = "INSERT INTO user_information (first_Name, last_Name, middle_Name, date_Of_Birth, mobile_Number, region, city, country, province, zip_Code, user_ID, email_Address, time_Created, date_Created, id_Number, account_Status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  $queryResult_1 = executeQuery($mysqli, $query_1, "ssssssssssssssss", $params_1);

  if (!$queryResult_1['success']) {
    $error_message = "An internal error has occured. Please try again later or contact the administrator";
    redirectWithError($error_message);
    exit;
  }

  $query_2 = "INSERT INTO user_role (user_ID, user_Role, date_Assigned) VALUES (?, ?, ?)";
  $queryResult_2 = executeQuery($mysqli, $query_2, "sss", $params_2);

  if (!$queryResult_2['success']) {
    $error_message = "An internal error has occured. Please try again later or contact the administrator";
    redirectWithError($error_message);
    exit;
  }

  $query_3 = "INSERT INTO user_access (user_ID, user_Password) VALUES (?, ?)";
  $queryResult_3 = executeQuery($mysqli, $query_3, "ss", $params_3);

  if (!$queryResult_3['success']) {
    $error_message = "An internal error has occured. Please try again later or contact the administrator";
    redirectWithError($error_message);
    exit;
  }

  $mysqli->close();

  echo "<script>alert('User has been successfully registered');</script>";
  echo "<meta http-equiv='refresh' content='0;url=../pages/login.html'>";
  exit;
}

$error_message = "Form submission method not allowed";
redirectWithError($error_message);
exit;

