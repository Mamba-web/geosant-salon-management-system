<?php
session_start();
include("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    // Reset failed login attempts
mysqli_query($conn, "
UPDATE users
SET failed_attempts = 0,
    locked_until = NULL
WHERE id = {$user['id']}
");
    header("Location: login.php");
    exit();
}

$username = trim($_POST['username']);
$password = trim($_POST['password']);

$sql = "SELECT * FROM users WHERE username = ? LIMIT 1";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 1) {

    $user = mysqli_fetch_assoc($result);

    // Check if account is temporarily locked
if (!empty($user['locked_until']) && strtotime($user['locked_until']) > time()) {

$_SESSION['error'] = "Too many failed login attempts. Please try again later.";

header("Location: login.php");
exit();
}

    // Check account status
    if (strtolower($user['status']) != "active") {

        $_SESSION['error'] = "Your account is inactive. Please contact the administrator.";

        header("Location: login.php");
        exit();
    }

    // Check hashed password first
    if (password_verify($password, $user['password'])) {

        // Password is already hashed

    } elseif ($password === $user['password']) {

        // Old plain-text password detected.
        // Upgrade it automatically to a hashed password.
        $newHash = password_hash($password, PASSWORD_DEFAULT);

        $update = mysqli_prepare(
            $conn,
            "UPDATE users SET password = ? WHERE id = ?"
        );

        mysqli_stmt_bind_param($update, "si", $newHash, $user['id']);
        mysqli_stmt_execute($update);

    } else {

       // Increase failed login attempts
$failed = $user['failed_attempts'] + 1;

if ($failed >= 3) {

    mysqli_query($conn, "
        UPDATE users
        SET failed_attempts = 0,
            locked_until = DATE_ADD(NOW(), INTERVAL 15 MINUTE)
        WHERE id = {$user['id']}
    ");

    $_SESSION['error'] = "Too many failed login attempts. Your account has been locked for 15 minutes🤣🥹.";

} else {

    mysqli_query($conn, "
        UPDATE users
        SET failed_attempts = $failed
        WHERE id = {$user['id']}
    ");

    $_SESSION['error'] = "Invalid username or password. Attempt $failed of 3😒.";
}

header("Location: login.php");
exit();
    }

    // Successful login
    session_regenerate_id(true);

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['role'] = strtolower($user['role']);

    // Update last login
    mysqli_query($conn, "UPDATE users SET last_login = NOW() WHERE id = {$user['id']}");

    // Remember Me
    if (isset($_POST['remember'])) {

        $token = bin2hex(random_bytes(32));

        mysqli_query($conn, "
            UPDATE users
            SET remember_token = '$token'
            WHERE id = {$user['id']}
        ");

        setcookie(
            "remember_token",
            $token,
            time() + (86400 * 30),
            "/"
        );
    }

    header("Location: ../dashboard/index.php");
    exit();

} else {

    $_SESSION['error'] = "Invalid username or password.";

    header("Location: login.php");
    exit();
}
?>