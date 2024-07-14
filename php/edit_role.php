<?php
header('Content-Type: application/json');

require_once 'includes/dbh_inc.php';
require_once 'includes/execute_query_inc.php';
require_once 'includes/config_session_inc.php';
require_once 'includes/error_model_inc.php';

function getEditorData($mysqli, $editor_ID)
{
    $query = "SELECT user_Role 
              FROM user_role
              WHERE user_ID = ?";

    $queryResult = executeQuery($mysqli, $query, "s", [$editor_ID]);

    if (!$queryResult['success']) {
        echo json_encode(['error' => 'An internal error has occurred. Please try again later or contact the administrator.']);
        exit;
    }

    return $queryResult['result'] ? $queryResult['result']->fetch_assoc() : null;
}

function getUserData($mysqli, $user_ID)
{
    $query = "SELECT ui.user_ID, ui.first_Name, ui.middle_Name, ui.last_Name, ur.user_Role, ur.date_Assigned, ur.previous_Role, ur.date_Change
            FROM USER_INFORMATION ui
            JOIN USER_ROLE ur ON ui.user_ID = ur.user_ID
            WHERE ui.user_ID = ?;";

    $queryResult = executeQuery($mysqli, $query, "s", [$user_ID]);

    if (!$queryResult['success']) {
        echo json_encode(['error' => 'An internal error has occurred. Please try again later or contact the administrator.']);
        exit;
    }

    return $queryResult['result'] ? $queryResult['result']->fetch_assoc() : null;
}

$editor_ID = $_SESSION['user_ID'] ?? null;

$editor_data = getEditorData($mysqli, $editor_ID);

if ($editor_data['user_Role'] != '1') {
    echo json_encode(['error' => 'Permission denied. Only an admin can modify user role.']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['user_ID'])) {
    $user_ID = $_GET["user_ID"] ?? null;
    $user_Data = getUserData($mysqli, $user_ID);

    if (!$user_Data) {
        echo json_encode(['error' => 'User not found']);
        exit;
    }

    $first_Name = $user_Data['first_Name'];
    $middle_Name = $user_Data['middle_Name'];
    $last_Name = $user_Data['last_Name'];
    $user_Role = $user_Data['user_Role'] ? $user_Data['user_Role'] : 'NONE';
    $date_Assigned = $user_Data['date_Assigned'] ? date("Y-m-d", strtotime($user_Data['date_Assigned'])) : 'NONE';
    $previous_Role = $user_Data['previous_Role'] ? $user_Data['previous_Role'] : 'NONE';
    $date_Change = $user_Data['date_Change'] ? date("Y-m-d", strtotime($user_Data['date_Change'])) : 'NONE';

    echo json_encode([
        'user_ID' => $user_ID,
        'first_Name' => $first_Name,
        'middle_Name' => $middle_Name,
        'last_Name' => $last_Name,
        'user_Role' => $user_Role,
        'date_Assigned' => $date_Assigned,
        'previous_Role' => $previous_Role,
        'date_Change' => $date_Change
    ]);
} else {
    echo json_encode(['error' => 'Invalid submission method. Form submission method not allowed']);
    exit;
}

$mysqli->close();
