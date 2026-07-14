<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>GeoSant Unisex Salon | Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="../assets/css/login.css">


</head>
<body>

<div class="login-container">

    <!-- Left Side -->
    <div class="left-panel">

        <div class="overlay">

            <img src="../assets/images/logo.png" class="logo">

            <h1>GeoSant</h1>

            <h5>Unisex Salon Management System</h5>

            <p>
                Manage appointments, customers,
                staff and payments effortlessly.
            </p>

        </div>

    </div>

    <!-- Right Side -->

    <div class="right-panel">

        <div class="login-card">

            <h2>Welcome Back</h2>

            <p>Please login to continue.</p>

            <?php if(isset($_SESSION['error'])){ ?>

<div class="alert alert-danger">

    <?php
        echo $_SESSION['error'];
        unset($_SESSION['error']);
    ?>

</div>

<?php } ?>

            <form action="login_process.php" method="POST">

                <div class="mb-3">

                    <label>Username</label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="fas fa-user"></i>
                        </span>

                        <input
                        type="text"
                        name="username"
                        class="form-control"
                        placeholder="Enter username"
                        required>

                    </div>

                </div>

                <div class="mb-3">

                    <label>Password</label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>

                        <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Enter password"
                        required>

                        <button
                        class="btn btn-outline-secondary"
                        type="button"
                        onclick="togglePassword()">

                        <i class="fas fa-eye"></i>

                        </button>

                    </div>

                </div>

                <div class="d-flex justify-content-between">

                    <div>

                        <input
                        type="checkbox"
                        name="remember">

                        Remember Me

                    </div>

                    <a href="forgot_password.php">
                        Forgot Password?
                    </a>

                </div>

                <button
                class="btn btn-primary w-100 mt-4">

                Login

                </button>

            </form>

            <hr>

            <p class="text-center">
                Need an account?
                <b>Please contact the system administrator.</b>
            </p>

        </div>

    </div>

</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/login.js"></script>

</body>
</html>