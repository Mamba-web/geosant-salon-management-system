<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>GeoSant | Forgot Password</title>

<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/all.min.css">
<link rel="stylesheet" href="../assets/css/auth.css">

<style>

body{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#1f2937,#4b5563);
    font-family:'Poppins',sans-serif;
}

.auth-card{
    width:100%;
    max-width:520px;
    border-radius:20px;
    overflow:hidden;
    background:#fff;
    box-shadow:0 20px 50px rgba(0,0,0,.25);
}

.auth-header{
    background:linear-gradient(135deg,#0d6efd,#2563eb);
    color:#fff;
    text-align:center;
    padding:35px 30px;
}

.auth-header img{
    width:90px;
    margin-bottom:15px;
}

.auth-header h2{
    font-weight:700;
    margin-bottom:8px;
}

.auth-header p{
    opacity:.9;
    font-size:17px;
}

.auth-body{
    padding:35px;
}

.info-box{
    background:#f8fbff;
    border-left:5px solid #0d6efd;
    border-radius:12px;
    padding:20px;
    margin-bottom:30px;
}

.info-box h5{
    font-weight:700;
    margin-bottom:12px;
}

.info-box p{
    color:#555;
    line-height:1.7;
    margin-bottom:0;
}

.contact-box{
    background:#f8f9fa;
    border-radius:12px;
    padding:18px;
    margin-bottom:25px;
}

.contact-box div{
    margin-bottom:10px;
}

.contact-box div:last-child{
    margin-bottom:0;
}

.contact-box i{
    color:#0d6efd;
    width:25px;
}

.btn-primary{
    border-radius:10px;
    padding:12px;
    font-weight:600;
}

</style>

</head>

<body>

<div class="auth-card">

<div class="auth-header">

<img src="../assets/images/logo.png" alt="GeoSant Logo">

<h2>GeoSant Unisex Salon</h2>

<p>Forgot Password?</p>

</div>

<div class="auth-body">

<div>

<i class="fas fa-lock"></i>

</div>

<div class="info-box">

<h5>Need help signing in?</h5>

<p>

For your security, passwords cannot be reset from this page.

If you have forgotten your password, please contact the
<strong>System Administrator</strong>. They can securely create a new password for your account from the User Management module.

</p>

</div>

<div class="contact-box">

<div>
<i class="fas fa-user-shield"></i>
<strong>Administrator Access Required</strong>
</div>

<div>
<i class="fas fa-shield-alt"></i>
Password resets are performed securely by an authorized administrator.
</div>

</div>

<div class="d-grid">

<a href="login.php" class="btn btn-primary">

<i class="fas fa-arrow-left me-2">Back to Login</i></a>

</div>

</div>

</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>

</body>
</html>
