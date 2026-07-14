<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>GeoSant Unisex Salon | Login</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<link rel="stylesheet" href="../assets/css/login.css">

</head>

<body>

<div class="page-wrapper">

    <!-- LEFT SIDE -->

    <div class="left-panel">

        <div class="overlay">

            <div class="branding">

                <img src="../assets/images/logo.png" alt="GeoSant Logo" class="logo">

                <h1>GeoSant</h1>

                <h3>Unisex Salon</h3>

                <p class="tagline">
                    Beauty Begins With Great Management
                </p>

                <div class="features">

                    <div class="feature">

                        <i class="fas fa-calendar-check"></i>

                        <span>Appointment Management</span>

                    </div>

                    <div class="feature">

                        <i class="fas fa-users"></i>

                        <span>Customer Management</span>

                    </div>

                    <div class="feature">

                        <i class="fas fa-user-tie"></i>

                        <span>Staff Management</span>

                    </div>

                    <div class="feature">

                        <i class="fas fa-money-bill-wave"></i>

                        <span>Payment Management</span>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- RIGHT SIDE -->

    <div class="right-panel">

        <div class="login-card">

            <div class="card-top">

                <h2>Welcome Back</h2>

                <p>
                    Sign in to continue to your dashboard.
                </p>

            </div>

            <?php if(isset($_SESSION['error'])){ ?>

            <div class="error-message">

                <i class="fas fa-circle-exclamation"></i>

                <?php
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>

            </div>

            <?php } ?>

            <form action="login_process.php" method="POST">

                <!-- USERNAME -->

                <div class="input-box">

                    <label for="username">

                        Username

                    </label>

                    <div class="input-field">

                        <i class="fas fa-user"></i>

                        <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Enter your username"
                        required>

                    </div>

                </div>

                <!-- PASSWORD -->

                <div class="input-box">

                    <label for="password">

                        Password

                    </label>

                    <div class="input-field">

                        <i class="fas fa-lock"></i>

                        <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        required>

                        <button
                        type="button"
                        class="toggle-password"
                        onclick="togglePassword()">

                            <i class="fas fa-eye"></i>

                        </button>

                    </div>

                </div>

                <!-- OPTIONS -->

                <div class="options">

                    <label class="remember">

                        <input
                        type="checkbox"
                        name="remember">

                        <span>Remember Me</span>

                    </label>

                    <a href="forgot_password.php">

                        Forgot Password?

                    </a>

                </div>

                <!-- LOGIN BUTTON -->

                <button
                type="submit"
                class="login-btn">

                    Login

                </button>

            </form>

            <div class="divider">

                <span></span>

            </div>

            <div class="bottom-text">

                Need an account?

                <strong>

                    Contact the System Administrator

                </strong>

            </div>

        </div>

    </div>

</div>

<script src="../assets/js/login.js"></script>

</body>

</html>