<?php
session_start();
include("../config/database.php");

// Remove remember token from database
if (isset($_SESSION['user_id'])) {

    $id = $_SESSION['user_id'];

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE users SET remember_token = NULL WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
}

// Delete Remember Me cookie
setcookie(
    "remember_token",
    "",
    time() - 3600,
    "/"
);

// Destroy session
session_unset();
session_destroy();

// Redirect to login
header("Location: login.php");
exit();
?>