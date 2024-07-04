<?php
require_once '../php/includes/config_session_inc.php';

if (!isset($_SESSION['user_ID'])) {
    header("Location: login.html");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PUP eMabini | Profile</title>
</head>

<body>
    <h1>Personal Data</h1>

    <h2><span id="first_Name_Name_Display"></span> <span id="middle_Name_Name_Display"> </span> <span
            id="last_Name_Name_Display"></span> (<span id="user_ID_Name_Display"></span>)</h2>

    <h3>User ID:</h3>
    <p><span id="user_ID"></span></p>

    <h3>ID Number:</h3>
    <p><span id="id_Number"></span></p>

    <h3>Name:</h3>
    <p><span id="first_Name"></span> <span id="middle_Name"> </span> <span id="last_Name"></span></p>

    <h3>Birth Date:</h3>
    <p><span id="date_Of_Birth"></span></p>

    <h3>Email Address:</h3>
    <p><span id="email_Address"></span></p>

    <h3>Mobile Number:</h3>
    <p><span id="mobile_Number"></span></p>

    <h3>City:</h3>
    <p><span id="city"></span></p>

    <h3>Province:</h3>
    <p><span id="province"></span></p>

    <h3>Region:</h3>
    <p><span id="region"></span></p>

    <h3>Country:</h3>
    <p><span id="country"></span></p>

    <h3>Zip Code:</h3>
    <p><span id="zip_Code"></span></p>

    <button id="edit_Profile_Button" onclick="redirectToEditProfile()">Edit Profile</button>

    <script src="../scripts/profile.js"></script>
</body>

</html>