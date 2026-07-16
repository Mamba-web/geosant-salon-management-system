<?php
require_once '../auth/check_auth.php';

include("../config/database.php");
include("../includes/activity_log.php");

if ($_SESSION['role'] != 'admin') {
    header("Location: ../dashboard/index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = trim($_POST['full_name']);
    $username  = trim($_POST['username']);
    $email     = trim($_POST['email']);
    $phone     = trim($_POST['phone']);

    // HASH THE PASSWORD
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $role = strtolower($_POST['role']);

    // Check if username already exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");

    if(mysqli_num_rows($check) > 0){

        echo "❌ Username already exists.";
        exit();

    }

    // Check if email already exists
    $checkEmail = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if(mysqli_num_rows($checkEmail) > 0){

        echo "❌ Email already exists.";
        exit();

    }

    // Check if phone already exists
    $checkPhone = mysqli_query($conn, "SELECT * FROM users WHERE phone='$phone'");

    if(mysqli_num_rows($checkPhone) > 0){

        echo "❌ Phone Number already exists.";
        exit();

    }

    // Insert user
    $sql = "INSERT INTO users
            (full_name, username, email, phone, password, role)
            VALUES
            ('$full_name','$username','$email','$phone','$password','$role')";

if(mysqli_query($conn,$sql)){

    logActivity(
        $conn,
        $_SESSION['user_id'],
        $_SESSION['full_name'],
        "Users",
        "Added user: " . $full_name . " (" . ucfirst($role) . ")"
    );

    header("Location: index.php?success=added");
    exit();

}else{

    echo "Error: ".mysqli_error($conn);

}

}else{

    echo "Invalid request";

}
?>