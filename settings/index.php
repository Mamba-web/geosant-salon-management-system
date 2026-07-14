<?php
require_once '../auth/check_auth.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../dashboard/index.php");
    exit();
}

include("../config/database.php");

$result = mysqli_query($conn, "SELECT * FROM settings LIMIT 1");
$settings = mysqli_fetch_assoc($result);

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/navbar.php");
?>

<div class="main-content dashboard-section">
<div class="container-fluid">

<?php
if(isset($_GET['success'])){
?>
<div class="alert alert-success alert-dismissible fade show">

    Settings updated successfully.

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

</div>
<?php } ?>


<h2 class="mb-4">System Settings</h2>

<form action="update.php" method="POST">

<input type="hidden" name="id" value="<?php echo $settings['id']; ?>">

<div class="mb-3">
<label>Salon Name</label>
<input type="text" name="salon_name" class="form-control"
value="<?php echo $settings['salon_name']; ?>">
</div>

<div class="mb-3">
<label>Phone</label>
<input type="text" name="phone" class="form-control"
value="<?php echo $settings['phone']; ?>">
</div>

<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control"
value="<?php echo $settings['email']; ?>">
</div>

<div class="mb-3">
<label>Address</label>
<textarea name="address" class="form-control"><?php echo $settings['address']; ?></textarea>
</div>

<div class="mb-3">
<label>Business Hours</label>
<input type="text" name="business_hours" class="form-control"
value="<?php echo $settings['business_hours']; ?>">
</div>

<div class="mb-3">
<label>Currency</label>
<input type="text" name="currency" class="form-control"
value="<?php echo $settings['currency']; ?>">
</div>

<div class="mb-3">
<label>Footer Text</label>
<input type="text" name="footer_text" class="form-control"
value="<?php echo $settings['footer_text']; ?>">
</div>

<button class="btn btn-success">
Save Settings
</button>

</form>

</div>
<?php include("../includes/footer_content.php"); ?>
</div>

<?php include("../includes/footer.php"); ?>