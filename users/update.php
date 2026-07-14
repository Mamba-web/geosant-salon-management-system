<?php
require_once '../auth/check_auth.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../dashboard/index.php");
    exit();
}

include("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id        = $_POST['id'];
    $full_name = $_POST['full_name'];
    $username  = $_POST['username'];
    $email     = $_POST['email'];
    $phone     = $_POST['phone'];
    $role      = strtolower($_POST['role']);
    $status    = strtolower($_POST['status']);

    $sql = "UPDATE users SET
                full_name = '$full_name',
                username = '$username',
                email = '$email',
                phone = '$phone',
                role = '$role',
                status = '$status'
            WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {

        header("Location: index.php?success=updated");
        exit();

    } else {

        echo "Error: " . mysqli_error($conn);

    }

} else {

    echo "Invalid request";

}
?>