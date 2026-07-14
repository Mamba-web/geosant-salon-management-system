<?php
require_once '../auth/check_auth.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../dashboard/index.php");
    exit();
}

include("../config/database.php");
include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/navbar.php");
?>

<div class="main-content dashboard-section">
    <div class="container-fluid">

    <h2>Add New User</h2>

    <form action="store.php" method="POST">

        <div class="mb-3">
            <label>Full Name</label>
            <input type="text" name="full_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control">
        </div>

        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control">
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-control">
                <option value="Admin">Admin</option>
                <option value="Staff">Staff</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Save User</button>
        <a href="index.php" class="btn btn-secondary">Back</a>

    </form>

</div>

</div>
</div>

<?php include("../includes/footer.php"); ?>