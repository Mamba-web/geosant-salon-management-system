<?php
require_once '../auth/check_auth.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../dashboard/index.php");
    exit();
}

include("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST['id'];
    $salon_name = $_POST['salon_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $business_hours = $_POST['business_hours'];
    $currency = $_POST['currency'];
    $footer_text = $_POST['footer_text'];

    $sql = "UPDATE settings SET
                salon_name='$salon_name',
                phone='$phone',
                email='$email',
                address='$address',
                business_hours='$business_hours',
                currency='$currency',
                footer_text='$footer_text'
            WHERE id='$id'";

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