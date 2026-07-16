<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");
include("../includes/activity_log.php");

if (isset($_GET['id'])) {

    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Get staff name before deleting
    $result = mysqli_query(
        $conn,
        "SELECT full_name FROM staff WHERE id='$id'"
    );

    if ($row = mysqli_fetch_assoc($result)) {
        $full_name = $row['full_name'];
    } else {
        $full_name = "Unknown Staff";
    }

    // Delete staff
    $sql = "DELETE FROM staff WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {

        // Activity Log
        logActivity(
            $conn,
            $_SESSION['user_id'],
            $_SESSION['full_name'],
            "Staff",
            "Deleted staff: " . $full_name
        );

        $_SESSION['success'] = "Staff deleted successfully.";

    } else {

        $_SESSION['error'] = "Failed to delete staff.";

    }

} else {

    $_SESSION['error'] = "Invalid staff ID.";

}

header("Location: index.php");
exit();
?>