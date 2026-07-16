<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");
include("../includes/activity_log.php");

if(isset($_GET['id'])){

    $id = mysqli_real_escape_string($conn,$_GET['id']);

    // Get service name before deleting
    $result = mysqli_query(
        $conn,
        "SELECT service_name FROM services WHERE id='$id'"
    );

    if($row = mysqli_fetch_assoc($result)){
        $service_name = $row['service_name'];
    }else{
        $service_name = "Unknown Service";
    }

    $sql="DELETE FROM services WHERE id='$id'";

    if(mysqli_query($conn,$sql)){

        // Activity Log
        logActivity(
            $conn,
            $_SESSION['user_id'],
            $_SESSION['full_name'],
            "Services",
            "Deleted service: " . $service_name
        );

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