<?php

date_default_timezone_set("Africa/Accra");


$host = "localhost";
$username = "root";
$password = "";
$database = "salon_management";

// Create connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

?>