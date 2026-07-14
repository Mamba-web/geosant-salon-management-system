<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    $sql = "INSERT INTO staff (full_name, gender, phone, role)
            VALUES ('$full_name', '$gender', '$phone', '$role')";

    if (mysqli_query($conn, $sql)) {

        $_SESSION['success'] = "Staff added successfully.";

    } else {

        $_SESSION['error'] = "Failed to add staff.";

    }

    header("Location: index.php");
    exit();

} else {

    $_SESSION['error'] = "Invalid request.";

    header("Location: index.php");
    exit();

}