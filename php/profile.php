<?php
header('Content-Type: application/json');

require_once 'includes/dbh_inc.php';
require_once 'includes/execute_query_inc.php';
require_once 'includes/config_session_inc.php';
require_once 'includes/error_model_inc.php';

function getUserData($mysqli, $user_ID): ?array
{
    $query = "SELECT user_ID, first_Name, middle_Name, last_Name, date_Of_Birth, email_Address, mobile_Number, city, province, region, country, zip_Code, id_Number 
              FROM user_information 
              WHERE user_ID = ?;";
    $queryResult = executeQuery($mysqli, $query, "s", [$user_ID]);

    return $queryResult['result'] ? $queryResult['result']->fetch_assoc() : null;
}

$user_ID = $_SESSION['user_ID'] ?? null;

if ($user_ID) {
    $userData = getUserData($mysqli, $user_ID);

    if ($userData) {
        $formattedDate = date("Y-m-d", strtotime($userData['date_Of_Birth']));
        echo json_encode([
            'user_ID' => $userData['user_ID'],
            'id_Number' => $userData['id_Number'],
            'first_Name' => $userData['first_Name'],
            'middle_Name' => $userData['middle_Name'],
            'last_Name' => $userData['last_Name'],
            'date_Of_Birth' => $formattedDate,
            'email_Address' => $userData['email_Address'],
            'mobile_Number' => $userData['mobile_Number'],
            'city' => $userData['city'],
            'province' => $userData['province'],
            'region' => $userData['region'],
            'country' => $userData['country'],
            'zip_Code' => $userData['zip_Code']
        ]);
    } else {
        redirectWithError('User Not Found Error. Please try again later or contact the administrator.');
        echo json_encode(['error' => 'User not found']);
        exit;
    }
} else {
    redirectWithError('No User ID Provided Error. Please try again later or contact the administrator.');
    echo json_encode(['error' => 'No user ID provided']);
    exit;
}

$mysqli->close();
