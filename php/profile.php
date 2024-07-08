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
    $user_Data = getUserData($mysqli, $user_ID);

    if ($user_Data) {
        $formatted_Date = date("Y-m-d", strtotime($user_Data['date_Of_Birth']));
        echo json_encode([
            'user_ID' => $user_Data['user_ID'],
            'id_Number' => $user_Data['id_Number'],
            'first_Name' => $user_Data['first_Name'],
            'middle_Name' => $user_Data['middle_Name'],
            'last_Name' => $user_Data['last_Name'],
            'date_Of_Birth' => $formatted_Date,
            'email_Address' => $user_Data['email_Address'],
            'mobile_Number' => $user_Data['mobile_Number'],
            'city' => $user_Data['city'],
            'province' => $user_Data['province'],
            'region' => $user_Data['region'],
            'country' => $user_Data['country'],
            'zip_Code' => $user_Data['zip_Code']
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
