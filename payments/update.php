<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");
include("../includes/activity_log.php");

$id = mysqli_real_escape_string($conn,$_POST['id']);
$appointment_id = mysqli_real_escape_string($conn,$_POST['appointment_id']);
$amount = mysqli_real_escape_string($conn,$_POST['amount']);
$payment_method = mysqli_real_escape_string($conn,$_POST['payment_method']);
$payment_date = mysqli_real_escape_string($conn,$_POST['payment_date']);

$sql = "UPDATE payments SET
appointment_id='$appointment_id',
amount='$amount',
payment_method='$payment_method',
payment_date='$payment_date'
WHERE id='$id'";

if(mysqli_query($conn,$sql)){

    // Get customer name
    $result = mysqli_query($conn,"
        SELECT customers.customer_name
        FROM appointments
        INNER JOIN customers
        ON appointments.customer_id = customers.id
        WHERE appointments.id='$appointment_id'
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
        "Updated payment of GHS " . number_format($amount,2) . " for " . $customer_name
    );

    $_SESSION['success']="Payment updated successfully.";

}else{

    $_SESSION['error']="Unable to update payment.";

}

header("Location:index.php");
exit();
?>