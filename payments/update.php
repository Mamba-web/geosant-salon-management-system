<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

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

$_SESSION['success']="Payment updated successfully.";

}else{

$_SESSION['error']="Unable to update payment.";

}

header("Location:index.php");
exit();
?>