<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

$sql = "TRUNCATE TABLE activity_logs";

if (mysqli_query($conn, $sql)) {

    $_SESSION['success'] = "All activity logs have been cleared successfully.";

} else {

    $_SESSION['error'] = "Failed to clear activity logs.<br>" . mysqli_error($conn);

}

header("Location: index.php");
exit();
?>