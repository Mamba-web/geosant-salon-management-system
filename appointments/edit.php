<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Appointment not found.";
    header("Location: index.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

$appointment = mysqli_query($conn,"
SELECT * FROM appointments
WHERE id='$id'
");

if(mysqli_num_rows($appointment)==0){

    $_SESSION['error']="Appointment not found.";
    header("Location:index.php");
    exit();

}

$row = mysqli_fetch_assoc($appointment);

$customers = mysqli_query($conn,"SELECT * FROM customers ORDER BY customer_name ASC");
$staff = mysqli_query($conn,"SELECT * FROM staff ORDER BY full_name ASC");
$services = mysqli_query($conn,"SELECT * FROM services ORDER BY service_name ASC");

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/navbar.php");
?>

<div class="main-content">

<div class="container-fluid">

<div class="card shadow-sm">

<div class="card-header">
<h4>Edit Appointment</h4>
</div>

<div class="card-body">

<form action="update.php" method="POST">

<input type="hidden" name="id" value="<?php echo $row['id']; ?>">

<div class="mb-3">

<label>Customer</label>

<select name="customer_id" class="form-select">

<?php while($customer=mysqli_fetch_assoc($customers)){ ?>

<option
value="<?php echo $customer['id']; ?>"
<?php if($customer['id']==$row['customer_id']) echo "selected"; ?>
>

<?php echo $customer['customer_name']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="mb-3">

<label>Staff</label>

<select name="staff_id" class="form-select">

<?php while($member=mysqli_fetch_assoc($staff)){ ?>

<option
value="<?php echo $member['id']; ?>"
<?php if($member['id']==$row['staff_id']) echo "selected"; ?>
>

<?php echo $member['full_name']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="mb-3">

<label>Service</label>

<select name="service_id" class="form-select">

<?php while($service=mysqli_fetch_assoc($services)){ ?>

<option
value="<?php echo $service['id']; ?>"
<?php if($service['id']==$row['service_id']) echo "selected"; ?>
>

<?php echo $service['service_name']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="mb-3">

<label>Date</label>
    <input
       type="date"
       name="appointment_date"
       class="form-control"
       value="<?php echo $row['appointment_date']; ?>">

</div>

<div class="mb-3">

<label>Time</label>
    <input
       type="time"
       name="appointment_time"
       class="form-control"
       value="<?php echo $row['appointment_time']; ?>">

</div>

<div class="mb-3">

<label>Status</label>

<select name="status" class="form-select">
    <option value="Pending"
      <?php if($row['status']=="Pending") echo "selected"; ?>>
            Pending
    </option>

    <option value="Completed"
      <?php if($row['status']=="Completed") echo "selected"; ?>>
           Completed
    </option>

    <option value="Cancelled"
       <?php if($row['status']=="Cancelled") echo "selected"; ?>>
         Cancelled
    </option>
</select>
</div>

<button class="btn btn-success">
Update Appointment
</button>

<a href="index.php" class="btn btn-secondary">Cancel</a>

</form>

</div>

</div>

</div>
<?php include("../includes/footer_content.php"); ?>
</div>

<?php include("../includes/footer.php"); ?>