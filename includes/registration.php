<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $first_name = $_POST["first_name"];
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  echo $first_name;
  // Use the values as needed
  // ...
}