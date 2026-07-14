<?php
require_once '../auth/check_auth.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../dashboard/index.php");
    exit();
}

include("../config/database.php");

// Check if ID is provided
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// Get the user details
$sql = "SELECT * FROM users WHERE id = '$id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) != 1) {
    header("Location: index.php");
    exit();
}

$user = mysqli_fetch_assoc($result);

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/navbar.php");
?>

<div class="main-content dashboard-section">
    <div class="container-fluid">

        <h2 class="mb-4">Edit User</h2>

        <form action="update.php" method="POST">

            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

            <div class="mb-3">
                <label>Full Name</label>
                <input type="text" name="full_name" class="form-control"
                    value="<?php echo $user['full_name']; ?>" required>
            </div>

            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control"
                    value="<?php echo $user['username']; ?>" required>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control"
                    value="<?php echo $user['email']; ?>">
            </div>

            <div class="mb-3">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control"
                    value="<?php echo $user['phone']; ?>">
            </div>

            <div class="mb-3">
                <label>Role</label>
                <select name="role" class="form-control">

                    <option value="admin"
                        <?php if(strtolower($user['role'])=="admin") echo "selected"; ?>>
                        Admin
                    </option>

                    <option value="staff"
                        <?php if(strtolower($user['role'])=="staff") echo "selected"; ?>>
                        Staff
                    </option>

                </select>
            </div>

            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-control">

                    <option value="active"
                        <?php if(strtolower($user['status'])=="active") echo "selected"; ?>>
                        Active
                    </option>

                    <option value="inactive"
                        <?php if(strtolower($user['status'])=="inactive") echo "selected"; ?>>
                        Inactive
                    </option>

                </select>
            </div>

            <button type="submit" class="btn btn-success">
                Update User
            </button>

            <a href="index.php" class="btn btn-secondary">
                Cancel
            </a>

        </form>

    </div>
<?php include("../includes/footer_content.php"); ?>
</div>

<?php include("../includes/footer.php"); ?>