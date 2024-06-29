<?php
date_default_timezone_set('Asia/Manila');

// Create connection
$conn = new mysqli("localhost", "root", "", "pup_lms");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  $last_name = $_GET['last_name'];
  $first_name = $_GET['first_name'];
  $middle_name = $_GET['middle_name'];
  $date_of_birth = $_GET['dob'];
  $contact_number = $_GET['mobile'];
  $region = $_GET['region'];
  $city = $_GET['city'];
  $country = $_GET['country'];
  $province = $_GET['province'];
  $zip_code = $_GET['zip_code'];
  $user_ID = $_GET['user_ID'];
  $email = $_GET['email'];
  $password = $_GET['password'];
  $time_created = date("H:i:s");
  $date_created = date("Y-m-d");
  $id_number = $_GET['user_ID'];
  $acc_status = '1';
  $role = $_GET['role'];

  // For validation of Email, Contact Number, and ID
  $checkEmail = "SELECT * FROM user_information WHERE email_address = '$email'";
  $checkMobile = "SELECT * FROM user_information WHERE mobile_number = '$contact_number'";
  $checkID = "SELECT * FROM user_information WHERE user_ID = '$user_ID'";
  $result = $conn->query($checkEmail);
  $result2 = $conn->query($checkMobile);
  $result3 = $conn->query($checkID);

  if ($result3->num_rows > 0) {
    echo "<script>alert('User ID is already used');</script>";
    echo "<script>window.history.back();</script>";
    exit;
  }
  else if ($result->num_rows > 0 || $result2->num_rows > 0) {
      echo "<script>alert('Email or Mobile Number is already used');</script>";
      echo "<script>window.history.back();</script>";
      exit;
  }
  else{
    $sql = "INSERT INTO user_information (first_Name, last_Name, middle_Name, date_Of_Birth, mobile_Number, region, city, country, province, zip_Code, user_ID, email_Address, time_Created, date_Created, id_Number, account_Status)
    VALUES ('$first_name', '$last_name', '$middle_name', '$date_of_birth', '$contact_number', '$region', '$city', '$country', '$province', '$zip_code', '$user_ID', '$email', '$time_created', '$date_created', '$id_number', '$acc_status')";
    
    $sql2 = "INSERT INTO user_role (user_ID, user_Role, date_Assigned) VALUES ('$user_ID', '$role', '$date_created')";

    $sql3 = "INSERT INTO user_access (user_ID, user_Password) VALUES ('$user_ID', '$password')";
    if ($conn->query($sql) === TRUE) {
      if ($conn->query($sql2) === TRUE) {
        if ($conn->query($sql3) === TRUE) {
          header("Location:index.html"); //back to log in page
        } else {
          echo "Error";
        }
      } else {
        echo "Error";
      }
    } else {
      echo "Error";
    }
  }

  $conn->close();
}

?>