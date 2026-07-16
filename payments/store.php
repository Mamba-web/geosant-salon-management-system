<?php
session_start();

date_default_timezone_set("Africa/Accra");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");
require_once "../sms/send_sms.php";
include("../includes/activity_log.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $appointment_id = mysqli_real_escape_string($conn, $_POST['appointment_id']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $payment_date = date("Y-m-d");

    $sql = "INSERT INTO payments
            (appointment_id, amount, payment_method, payment_date)
            VALUES
            ('$appointment_id', '$amount', '$payment_method', '$payment_date')";

    if (mysqli_query($conn, $sql)) {

        // Automatically mark appointment as completed
        mysqli_query($conn, "
            UPDATE appointments
            SET status = 'Completed'
            WHERE id = '$appointment_id'
        ");

        // Generate SMS
        sendSMS($appointment_id);

        // Get customer name from the appointment
        $result = mysqli_query($conn,"
            SELECT customers.customer_name
            FROM appointments
            INNER JOIN customers
            ON appointments.customer_id = customers.id
            WHERE appointments.id = '$appointment_id'
        ");

        if($row = mysqli_fetch_assoc($result)){
            $customer_name = $row['customer_name'];
        }else{
            $customer_name = "Unknown Customer";
        }

        // Activity Log
        logActivity(
            $conn,
            $_SESSION['user_id'],
            $_SESSION['full_name'],
            "Payments",
            "Recorded payment of GHS " . number_format($amount,2) . " for " . $customer_name
        );

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