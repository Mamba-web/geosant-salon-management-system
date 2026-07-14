<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $customer_id = mysqli_real_escape_string($conn, $_POST['customer_id']);
    $staff_id = mysqli_real_escape_string($conn, $_POST['staff_id']);
    $service_id = mysqli_real_escape_string($conn, $_POST['service_id']);
    $appointment_date = mysqli_real_escape_string($conn, $_POST['appointment_date']);
    $appointment_time = mysqli_real_escape_string($conn, $_POST['appointment_time']);

    $sql = "INSERT INTO appointments
            (customer_id, staff_id, service_id, appointment_date, appointment_time)
            VALUES
            ('$customer_id', '$staff_id', '$service_id', '$appointment_date', '$appointment_time')";

    if (mysqli_query($conn, $sql)) {

        $_SESSION['success'] = "Appointment booked successfully.";

    } else {

        $_SESSION['error'] = "Failed to book appointment.<br>" . mysqli_error($conn);

    }

    header("Location: index.php");
    exit();

} else {

    $_SESSION['error'] = "Invalid request.";

    header("Location: index.php");
    exit();

}
?>