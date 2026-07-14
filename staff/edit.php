<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Staff ID not found.";
    header("Location: index.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

$sql = "SELECT * FROM staff WHERE id='$id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['error'] = "Staff member not found.";
    header("Location: index.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/navbar.php");
?>

<div class="main-content">

<div class="container-fluid">
    <div class="card shadow-sm">
    <div class="card-header">
    <h4>Edit Staff</h4>
</div>

<div class="card-body">

    <form action="update.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

<div class="mb-3">
    <label class="form-label">Full Name</label>
    <input type="text" name="full_name" class="form-control" value="<?php echo $row['full_name']; ?>" required>
</div>

<div class="mb-3">
    <label class="form-label">Gender</label>
        <select name="gender" class="form-select" required>
         <option value="Male"<?php if($row['gender']=="Male") echo "selected"; ?>>Male</option>
         <option value="Female"<?php if($row['gender']=="Female") echo "selected"; ?>>Female</option>
        </select>
</div>

 <div class="mb-3">
    <label class="form-label">Phone</label>
    <input type="text" name="phone" class="form-control" value="<?php echo $row['phone']; ?>" required>
</div>

<div class="mb-3">
    <label class="form-label">Role</label>
        <select name="role" class="form-select" required>
         <option value="Barber" <?php if($row['role']=="Staff") echo "selected"; ?>>Staff</option>
         <option value="Manager" <?php if($row['role']=="Manager") echo "selected"; ?>>Manager</option>
         </select>
</div>

<button class="btn btn-success">
    Update Staff
</button>

<a href="index.php" class="btn btn-secondary">
    Cancel
</a>

</form>
</div>
</div>
</div>
<?php include("../includes/footer_content.php"); ?>
</div>

<?php include("../includes/footer.php"); ?>