<?php
session_start();
date_default_timezone_set('Africa/Accra');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");


$customers = mysqli_query($conn, "SELECT * FROM customers ORDER BY customer_name ASC");
$staff = mysqli_query($conn, "SELECT * FROM staff ORDER BY full_name ASC");
$services = mysqli_query($conn, "SELECT * FROM services ORDER BY service_name ASC");

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/navbar.php");
?>

<div class="main-content">
<div class="container-fluid">

  <div class="card shadow-sm">
    <div class="card-header">
      <h4>New Appointment</h4>
    </div>

<div class="card-body">
<form action="store.php" method="POST">

  <div class="mb-3">

<label class="form-label">Customer</label>

<select name="customer_id" class="form-select" required>

<option value="">Select Customer</option>

<?php while($row=mysqli_fetch_assoc($customers)){ ?>

<option value="<?php echo $row['id']; ?>">

<?php echo $row['customer_name']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="mb-3">

<label class="form-label">Staff</label>

<select name="staff_id" class="form-select" required>

<option value="">Select Staff</option>

<?php while($row=mysqli_fetch_assoc($staff)){ ?>

<option value="<?php echo $row['id']; ?>">

<?php echo $row['full_name']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="mb-3">

<label class="form-label">Service</label>

<select name="service_id" class="form-select" required>

<option value="">Select Service</option>

<?php while($row=mysqli_fetch_assoc($services)){ ?>

<option value="<?php echo $row['id']; ?>">

<?php echo $row['service_name']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="mb-3">

<label class="form-label">Appointment Date</label>

<input
type="text"
class="form-control"
value="<?php echo date('d M Y'); ?>"
readonly>

</div>

<div class="mb-3">

<label class="form-label">Appointment Time</label>

<input
type="time"
name="appointment_time"
class="form-control"
required>

</div>

<button class="btn btn-success">
Save Appointment
</button>

<a href="index.php" class="btn btn-secondary">
Cancel
</a>

</form>

</div>

</div>

</div>
</div>
<?php include("../includes/footer.php"); ?>