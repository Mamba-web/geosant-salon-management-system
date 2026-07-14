<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Payment not found.";
    header("Location: index.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

$payment = mysqli_query($conn, "SELECT * FROM payments WHERE id='$id'");

if (mysqli_num_rows($payment) == 0) {
    $_SESSION['error'] = "Payment not found.";
    header("Location: index.php");
    exit();
}

$row = mysqli_fetch_assoc($payment);

$sql = "SELECT
            appointments.id,
            customers.customer_name,
            services.service_name
        FROM appointments
        INNER JOIN customers ON appointments.customer_id = customers.id
        INNER JOIN services ON appointments.service_id = services.id
        ORDER BY appointments.id DESC";

$appointments = mysqli_query($conn, $sql);

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/navbar.php");
?>

<div class="main-content">
<div class="container-fluid">

<div class="card shadow-sm">

<div class="card-header">
<h4>Edit Payment</h4>
</div>

<div class="card-body">

<form action="update.php" method="POST">

<input type="hidden" name="id" value="<?php echo $row['id']; ?>">

<div class="mb-3">
<label>Appointment</label>

<select name="appointment_id" class="form-select">

<?php while($app=mysqli_fetch_assoc($appointments)){ ?>

<option
value="<?php echo $app['id']; ?>"
<?php if($app['id']==$row['appointment_id']) echo "selected"; ?>
>

<?php echo $app['customer_name']." - ".$app['service_name']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="mb-3">
<label>Amount</label>

<input
type="number"
step="0.01"
name="amount"
class="form-control"
value="<?php echo $row['amount']; ?>"
required>

</div>

<div class="mb-3">

<label>Payment Method</label>

<select name="payment_method" class="form-select">

<option value="Cash"
<?php if($row['payment_method']=="Cash") echo "selected"; ?>>
Cash
</option>

<option value="Mobile Money"
<?php if($row['payment_method']=="Mobile Money") echo "selected"; ?>>
Mobile Money
</option>

<option value="Card"
<?php if($row['payment_method']=="Card") echo "selected"; ?>>
Card
</option>

</select>

</div>

<div class="mb-3">

<label>Payment Date</label>

<input
type="date"
name="payment_date"
class="form-control"
value="<?php echo $row['payment_date']; ?>">

</div>

<button class="btn btn-success">
Update Payment
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