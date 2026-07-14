<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);

    $sql = "INSERT INTO customers (customer_name, phone, gender)
            VALUES ('$customer_name', '$phone', '$gender')";

if (mysqli_query($conn, $sql)) {

    $_SESSION['success'] = "Customer added successfully.";

    header("Location: index.php");
    exit();

} else {

    $_SESSION['error'] = "Failed to add customer.";

    header("Location: index.php");
    exit();

}

} else {

    echo "Invalid Request.";

}
?>