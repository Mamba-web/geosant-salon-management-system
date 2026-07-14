<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

if(isset($_GET['id'])){

$id=mysqli_real_escape_string($conn,$_GET['id']);

$sql="DELETE FROM services WHERE id='$id'";

if(mysqli_query($conn,$sql)){

$_SESSION['success']="Service deleted successfully.";

}else{

$_SESSION['error']="Unable to delete service.";

}

}else{

$_SESSION['error']="Invalid Service ID.";

}

header("Location:index.php");
exit();
?>