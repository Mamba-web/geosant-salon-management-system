<?php
include("../config/database.php");

$settingsResult = mysqli_query($conn, "SELECT * FROM settings LIMIT 1");
$settings = mysqli_fetch_assoc($settingsResult);
?>