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

    // Get customer name before deleting
    $result = mysqli_query(
        $conn,
        "SELECT customer_name FROM customers WHERE id='$id'"
    );

    if ($row = mysqli_fetch_assoc($result)) {
        $customer_name = $row['customer_name'];
    } else {
        $customer_name = "Unknown Customer";
    }

    // Delete customer
    $sql = "DELETE FROM customers WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {

        // Activity Log
        logActivity(
            $conn,
            $_SESSION['user_id'],
            $_SESSION['full_name'],
            "Customers",
            "Deleted customer: " . $customer_name
        );

        $_SESSION['success'] = "Customer deleted successfully.";

    } else {

        $_SESSION['error'] = "Failed to delete customer.";

    }

    header("Location: index.php");
    exit();

} else {

    $_SESSION['error'] = "Invalid customer ID.";

    header("Location: index.php");
    exit();

}
?>