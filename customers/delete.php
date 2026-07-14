<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

if (isset($_GET['id'])) {

    $id = mysqli_real_escape_string($conn, $_GET['id']);

    $sql = "DELETE FROM customers WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {

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