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

// Get all users
$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);
?>

<div class="main-content dashboard-section">

    <div class="container-fluid">
<!-- <div class="container mt-5"> -->

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>User Management</h2>

<a href="create.php" class="btn btn-primary">
    <i class="fas fa-user-plus"></i> Add User
</a>

</div>

<?php

if (isset($_GET['success'])) {

    if ($_GET['success'] == "password_reset") {

        echo '<div class="alert alert-success">
                ✅ Password has been reset successfully.
              </div>';

    } elseif ($_GET['success'] == "added") {

        echo '<div class="alert alert-success">
                User added successfully.
              </div>';

    } elseif ($_GET['success'] == "updated") {

        echo '<div class="alert alert-success">
                User updated successfully.
              </div>';

    } elseif ($_GET['success'] == "deleted") {

        echo '<div class="alert alert-success">
                User deleted successfully.
              </div>';

    }

}

?>



</div>

<div class="card dashboard-table-card">

<div class="card-header">
    <h5 class="mb-0">Users List</h5>
</div>

<div class="card-body p-0">

    <div class="table-responsive">

        <table class="table dashboard-table table-hover mb-0">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['full_name']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo $row['role']; ?></td>

                <td>
                <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">
    Edit
</a>

<a href="reset_password.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
    🔑 Reset Password
</a>

<a href="delete.php?id=<?php echo $row['id']; ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('Are you sure you want to delete this user?')">
   Delete
</a>

<td>
    
</td>
</td>
</tr>
<?php } ?>
</tbody>

</table>

</div>
</div>

<?php include("../includes/footer.php"); ?>