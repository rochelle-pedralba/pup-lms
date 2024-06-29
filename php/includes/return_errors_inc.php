<?php
function returnErrors($errors)
{
    echo "<p>The form could not be saved. Please check the following errors: </p>";
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul>";
}

function checkForErrors($errors)
{
    if (!empty($errors)) {
        returnErrors($errors);
        exit;
    }
}
