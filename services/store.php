<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");
include("../includes/activity_log.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $service_name = mysqli_real_escape_string($conn, $_POST['service_name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);

    $sql = "INSERT INTO services (service_name, price)
            VALUES ('$service_name', '$price')";

    if (mysqli_query($conn, $sql)) {

        // Activity Log
        logActivity(
            $conn,
            $_SESSION['user_id'],
            $_SESSION['full_name'],
            "Services",
            "Added service: " . $service_name
        );

        $_SESSION['success'] = "Service added successfully.";

    } else {

        $_SESSION['error'] = "Failed to add service.";

    }

    header("Location: index.php");
    exit();

} else {

    $_SESSION['error'] = "Invalid request.";

    header("Location: index.php");
    exit();

}
?>