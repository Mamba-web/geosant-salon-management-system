<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("../config/database.php");

// Session timeout (30 minutes)
$timeout = 1800;

if (isset($_SESSION['LAST_ACTIVITY'])) {

    if (time() - $_SESSION['LAST_ACTIVITY'] > $timeout) {

        session_unset();
        session_destroy();

        header("Location: ../auth/login.php?timeout=1");
        exit();
    }
}

$_SESSION['LAST_ACTIVITY'] = time();

// Already logged in
if (isset($_SESSION['user_id'])) {
    return;
}

// Remember Me
if (isset($_COOKIE['remember_token'])) {

    $token = $_COOKIE['remember_token'];

    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM users WHERE remember_token = ? LIMIT 1"
    );

    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role'] = strtolower($user['role']);
        $_SESSION['LAST_ACTIVITY'] = time();

        return;
    }
}

header("Location: ../auth/login.php");
exit();