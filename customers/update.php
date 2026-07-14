<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);

    $sql = "UPDATE customers
            SET customer_name='$customer_name',
                phone='$phone',
                gender='$gender'
            WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Customer updated successfully.";

header("Location: index.php");
exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }

} else {
    echo "Invalid Request.";
}
?>