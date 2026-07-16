<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");
include("../includes/activity_log.php");

if (isset($_GET['id'])) {

    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Get customer before deleting
    $result = mysqli_query($conn,"
        SELECT customers.customer_name
        FROM appointments
        INNER JOIN customers
        ON appointments.customer_id = customers.id
        WHERE appointments.id='$id'
    ");

    if($row = mysqli_fetch_assoc($result)){
        $customer_name = $row['customer_name'];
    }else{
        $customer_name = "Unknown Customer";
    }

    $sql = "DELETE FROM appointments WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {

        logActivity(
            $conn,
            $_SESSION['user_id'],
            $_SESSION['full_name'],
            "Appointments",
            "Deleted appointment for: " . $customer_name
        );

        $_SESSION['success'] = "Appointment deleted successfully.";

    } else {

        $_SESSION['error'] = "Failed to delete appointment.";

    }

} else {

    $_SESSION['error'] = "Invalid appointment ID.";

}

header("Location: index.php");
exit();
?>