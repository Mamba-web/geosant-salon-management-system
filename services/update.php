<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

$id = mysqli_real_escape_string($conn,$_POST['id']);
$service_name = mysqli_real_escape_string($conn,$_POST['service_name']);
$price = mysqli_real_escape_string($conn,$_POST['price']);

$sql = "UPDATE services
SET
service_name='$service_name',
price='$price'
WHERE id='$id'";

if(mysqli_query($conn,$sql)){

    $_SESSION['success']="Service updated successfully.";

}else{

    $_SESSION['error']="Unable to update service.";

}

header("Location:index.php");
exit();
?>