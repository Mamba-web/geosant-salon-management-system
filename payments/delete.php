<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

if(isset($_GET['id'])){

$id=mysqli_real_escape_string($conn,$_GET['id']);

$sql="DELETE FROM payments WHERE id='$id'";

if(mysqli_query($conn,$sql)){

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