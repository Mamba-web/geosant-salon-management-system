<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $appointment_id = mysqli_real_escape_string($conn, $_POST['appointment_id']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $payment_date = mysqli_real_escape_string($conn, $_POST['payment_date']);

    $sql = "INSERT INTO payments
            (appointment_id, amount, payment_method, payment_date)
            VALUES
            ('$appointment_id', '$amount', '$payment_method', '$payment_date')";

    if (mysqli_query($conn, $sql)) {

        $_SESSION['success'] = "Payment recorded successfully.";

    } else {

        $_SESSION['error'] = "Failed to record payment.<br>" . mysqli_error($conn);

    }

    header("Location: index.php");
    exit();

} else {

    $_SESSION['error'] = "Invalid request.";

    header("Location: index.php");
    exit();

}
?>