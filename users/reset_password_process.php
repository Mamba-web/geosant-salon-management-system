<?php
require_once "../auth/check_auth.php";

include("../config/database.php");
include("../includes/activity_log.php");

// Only Admin can reset passwords
if ($_SESSION['role'] != 'admin') {
    header("Location: ../dashboard/index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.php");
    exit();
}

$id = (int) $_POST['id'];
$password = trim($_POST['password']);
$confirm_password = trim($_POST['confirm_password']);

// Check if passwords match
if ($password !== $confirm_password) {
    die("❌ Passwords do not match.");
}

// Hash the new password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Update the password and clear login security counters
$stmt = mysqli_prepare($conn, "
    UPDATE users
    SET password = ?,
        failed_attempts = 0,
        locked_until = NULL,
        remember_token = NULL
    WHERE id = ?
");

mysqli_stmt_bind_param($stmt, "si", $hashedPassword, $id);

if (mysqli_stmt_execute($stmt)) {

// Get user's full name
$result = mysqli_query(
    $conn,
    "SELECT full_name FROM users WHERE id=$id"
);

if($row = mysqli_fetch_assoc($result)){
    $full_name = $row['full_name'];
}else{
    $full_name = "Unknown User";
}

logActivity(
    $conn,
    $_SESSION['user_id'],
    $_SESSION['full_name'],
    "Users",
    "Reset password for user: " . $full_name
);

    header("Location: index.php?success=password_reset");
    exit();

} else {

    echo "Error: " . mysqli_error($conn);

}
?>