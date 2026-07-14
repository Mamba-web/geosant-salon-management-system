<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    $sql = "UPDATE staff SET
                full_name='$full_name',
                gender='$gender',
                phone='$phone',
                role='$role'
            WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {

        $_SESSION['success'] = "Staff updated successfully.";

    } else {

        $_SESSION['error'] = "Failed to update staff.";

    }

    header("Location: index.php");
    exit();

} else {

    $_SESSION['error'] = "Invalid request.";

    header("Location: index.php");
    exit();

}
?>