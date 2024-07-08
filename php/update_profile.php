<?php
header('Content-Type: application/json');

require_once 'includes/dbh_inc.php';
require_once 'includes/execute_query_inc.php';
require_once 'includes/config_session_inc.php';
require_once 'includes/error_model_inc.php';

function updateUserData($mysqli, $user_ID, $mobile, $email, $country, $region, $province, $city, $zip_code)
{
    $query = "UPDATE user_information 
              SET mobile_Number = ?, email_Address = ?, country = ?, region = ?, province = ?, city = ?, zip_Code = ?
              WHERE user_ID = ?";

    $queryResult = executeQuery($mysqli, $query, "ssssssss", [$mobile, $email, $country, $region, $province, $city, $zip_code, $user_ID]);
    return $queryResult;
}

$user_ID = $_SESSION['user_ID'] ?? null;

if ($_SERVER["REQUEST_METHOD"] === "POST" && $user_ID) {
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $zip_code = $_POST['zip_code'];
    $country = $_POST['countries'];

    if ($country === "Philippines") {
        $region = $_POST['regions'];
        $province = $_POST['provinces'];
        $city = $_POST['cities'];
    } else {
        $region = $_POST['_regions'];
        $province = $_POST['_provinces'];
        $city = $_POST['_cities'];
    }


    $queryResult = updateUserData($mysqli, $user_ID, $mobile, $email, $country, $region, $province, $city, $zip_code);

    if ($queryResult['success']) {
        echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update profile']);
        $error_message = "An internal error has occured. Please try again later or contact the administrator";
        redirectWithError($error_message);
        exit;
    }

    header("Location: ../pages/profile.php");
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    $error_message = "An internal error has occured. Please try again later or contact the administrator";
    redirectWithError($error_message);
    exit;
}

$mysqli->close();

