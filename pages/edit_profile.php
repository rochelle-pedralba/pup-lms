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
    <title>PUP eMabini | Edit Profile</title>
    <style>
        .hidden {
            display: none;
        }

        .input-group {
            margin-bottom: 1em;
        }
    </style>
</head>

<body>
    <h1>Edit Profile</h1>

    <h2><span id="first_Name_Name_Display"></span> <span id="middle_Name_Name_Display"> </span> <span
            id="last_Name_Name_Display"></span> (<span id="user_ID_Name_Display"></span>)</h2>

    <form method="POST" action="../php/update_profile.php">
        <div class="contact-details" id="contact_information">
            <div class="input-group">
                <label for="mobile">Mobile Number:</label>
                <input type="tel" id="mobile" name="mobile" pattern="09[0-9]{9}" placeholder="ex. 09XX XXX XXXX"
                    required>
            </div>

            <div class="input-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" required><br><br>
            </div>

            <div class="input-group">
                <label for="countries">Country:</label>
                <select id="countries" name="countries" required>
                    <option disabled>Select a Country</option>
                </select>
            </div>

            <div class="input-group">
                <label for="regions">Region:</label>
                <select id="regions" name="regions" class="toggle-select" required>
                    <option disabled>Select a Region</option>
                </select>
                <input type="text" id="_regions" name="_regions" class="hidden toggle-input">
            </div>

            <div class="input-group">
                <label for="provinces">Province:</label>
                <select id="provinces" name="provinces" class="toggle-select" required>
                    <option disabled>Select a Province</option>
                </select>
                <input type="text" id="_provinces" name="_provinces" class="hidden toggle-input">
            </div>

            <div class="input-group">
                <label for="cities">City:</label>
                <select id="cities" name="cities" class="toggle-select" required>
                    <option disabled>Select a City</option>
                </select>
                <input type="text" id="_cities" name="_cities" class="hidden toggle-input">
            </div>

            <div class="input-group">
                <label for="zip_code">Zip Code:</label>
                <input type="text" id="zip_code" name="zip_code" required>
            </div>
        </div>

        <input type="submit" value="Update Profile" id="submit">
        <button type="button" id="cancel_Update_Profile_Button" onclick="redirectToProfile()">Cancel</button>
    </form>

    <footer id="footer">
        <div>
            <p>&copy; 2021 PUP Learning Management System</p>
        </div>
    </footer>

    <script src="../scripts/edit_profile.js"></script>
</body>

</html>