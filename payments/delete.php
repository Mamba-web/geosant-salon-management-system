<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");
include("../includes/activity_log.php");

if(isset($_GET['id'])){

    $id = mysqli_real_escape_string($conn,$_GET['id']);

    // Get payment details before deleting
    $result = mysqli_query($conn,"
        SELECT
            payments.amount,
            customers.customer_name
        FROM payments
        INNER JOIN appointments
            ON payments.appointment_id = appointments.id
        INNER JOIN customers
            ON appointments.customer_id = customers.id
        WHERE payments.id='$id'
    ");

    if($row = mysqli_fetch_assoc($result)){
        $amount = $row['amount'];
        $customer_name = $row['customer_name'];
    }else{
        $amount = 0;
        $customer_name = "Unknown Customer";
    }

    $sql = "DELETE FROM payments WHERE id='$id'";

    if(mysqli_query($conn,$sql)){

        // Activity Log
        logActivity(
            $conn,
            $_SESSION['user_id'],
            $_SESSION['full_name'],
            "Payments",
            "Deleted payment of GHS " . number_format($amount,2) . " for " . $customer_name
        );

        $_SESSION['success']="Payment deleted successfully.";

    }else{

        $_SESSION['error']="Unable to delete payment.";

    }

}else{

    $_SESSION['error']="Invalid payment ID.";

}

header("Location:index.php");
exit();
?>