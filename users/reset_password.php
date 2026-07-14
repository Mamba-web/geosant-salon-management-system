<?php
require_once "../auth/check_auth.php";
include("../config/database.php");

// Only Admin can access
if ($_SESSION['role'] != 'admin') {
    header("Location: ../dashboard/index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int) $_GET['id'];

$stmt = mysqli_prepare($conn, "SELECT full_name, username FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit();
}

$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>GeoSant | Reset Password</title>

<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/all.min.css">
<link rel="stylesheet" href="../assets/css/password.css">
</head>

<body>

<div class="reset-card">

<div class="card-header-custom">

<img src="../assets/images/logo.png" alt="Logo">

<h2>GeoSant Unisex Salon</h2>

<p>Reset User Password</p>

</div>

<div class="card-body-custom">

<div class="text-center mb-4">
    <i class="fas fa-user-shield fa-4x text-primary"></i>
</div>

<div class="user-box">

    <div class="user-box-title">
        <i class="fas fa-user-circle me-2"></i>
        User Information
    </div>

    <div class="info-row">
        <span class="info-label">Full Name</span>
        <span class="info-value">
            <?php echo htmlspecialchars($user['full_name']); ?>
        </span>
    </div>

    <div class="info-row">
        <span class="info-label">Username</span>
        <span class="info-value">
            <?php echo htmlspecialchars($user['username']); ?>
        </span>
    </div>

</div>

<form action="reset_password_process.php" method="POST">

<input type="hidden" name="id" value="<?php echo $id; ?>">

<div class="mb-3">

<label class="form-label fw-bold">

New Password

</label>

<div class="input-group">

<span class="input-group-text">

<i class="fas fa-lock"></i>

</span>

<div class="password-wrapper">

<input
type="password"
name="password"
id="password"
class="form-control"
placeholder="Enter new password"
required>

<button
type="button"
class="password-toggle"
onclick="togglePassword('password',this)">

<i class="fas fa-eye"></i>

</button>

</div>

</div>

<div class="mb-4">

<label class="form-label fw-bold">

Confirm Password

</label>

<div class="input-group">

<span class="input-group-text">

<i class="fas fa-lock"></i>

</span>

<div class="password-wrapper">

<input
type="password"
name="password"
id="password"
class="form-control"
placeholder="Enter new password"
required>

<button
type="button"
class="password-toggle"
onclick="togglePassword('password',this)">

<i class="fas fa-eye"></i>

</button>

</div>

</div>

<div class="action-buttons">
<a href="index.php" class="btn btn-secondary w-50">
    <i class="fas fa-arrow-left"></i>
    Back
</a>

<button type="submit" class="btn btn-primary w-50">
    <i class="fas fa-key"></i>
    Reset Password
</button>
</div>

</form>

</div>

</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>

<script>

function togglePassword(id,btn){

let input=document.getElementById(id);
let icon=btn.querySelector("i");

if(input.type==="password"){

input.type="text";
icon.classList.remove("fa-eye");
icon.classList.add("fa-eye-slash");

}else{

input.type="password";
icon.classList.remove("fa-eye-slash");
icon.classList.add("fa-eye");

}

}

</script>

</body>

</html>