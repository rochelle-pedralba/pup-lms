<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Role | PUP eMabini</title>
</head>

<body>
    <h1>Modify User Role</h1>
    <form action="update_role.php" method="POST" id="searchForm">
        <label for="user_ID">Enter User ID</label>
        <input type="text" id="user_ID" name="user_ID" required>
        <input type="submit" value="Search" id="submit">
    </form>

    <div id="user_Details" style="display: none;">
        <h3>User ID:</h3>
        <p><span id="userID"></span></p>

        <h3>Name:</h3>
        <p><span id="first_Name"></span> <span id="middle_Name"></span> <span id="last_Name"></span></p>

        <h3>Account Role:</h3>
        <p><span id="user_Role"></span></p>

        <h3>Date Assigned:</h3>
        <p><span id="date_Assigned"></span></p>

        <h3>Previous Role:</h3>
        <p><span id="previous_Role"></span></p>

        <h3>Date Changed:</h3>
        <p><span id="date_Change"></span></p>

        <form action="../php/update_role.php" method="POST" id="updateForm">
            <label for="new_Role">New Role:</label>
            <select id="new_Role" name="new_Role" required></select>

            <input type="hidden" id="user_ID_hidden" name="user_ID_hidden" value="">
            <input type="submit" value="Update" id="submit">
            <button type="button" id="cancel_Update_Role_Button" onclick="cancelUpdateRole()">Cancel</button>
        </form>
    </div>

    <script src="../scripts/edit_role.js"></script>
</body>

</html>