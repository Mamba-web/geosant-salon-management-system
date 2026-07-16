<?php
require_once '../auth/check_auth.php';

include("../config/database.php");
include("../includes/activity_log.php");

if ($_SESSION['role'] != 'admin') {
    header("Location: ../dashboard/index.php");
    exit();
}

if (isset($_GET['id'])) {

    $id = (int)$_GET['id'];

    // Get user before deleting
    $result = mysqli_query($conn,
        "SELECT full_name FROM users WHERE id=$id"
    );

    if($row = mysqli_fetch_assoc($result)){
        $full_name = $row['full_name'];
    }else{
        $full_name = "Unknown User";
    }

    $sql = "DELETE FROM users WHERE id = $id";

    if (mysqli_query($conn, $sql)) {

        logActivity(
            $conn,
            $_SESSION['user_id'],
            $_SESSION['full_name'],
            "Users",
            "Deleted user: " . $full_name
        );

        header("Location: index.php?success=deleted");
        exit();

    } else {

        echo "Error deleting record: " . mysqli_error($conn);

    }

} else {

    echo "Invalid request";

}