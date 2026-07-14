<?php
require_once '../auth/check_auth.php';
include("../config/database.php");

if ($_SESSION['role'] != 'admin') {
    header("Location: ../dashboard/index.php");
    exit();
}

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $id = (int)$_GET['id'];

$sql = "DELETE FROM users WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: index.php?success=deleted");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }

} else {
    echo "Invalid request";
}
?>