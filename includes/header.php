<?php
include("../config/database.php");

$settingsResult = mysqli_query($conn, "SELECT * FROM settings LIMIT 1");
$settings = mysqli_fetch_assoc($settingsResult);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $settings['salon_name']; ?></title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Dashboard CSS -->
    <link rel="stylesheet" href="../assets/css/dashboard.css">

</head>
<body>