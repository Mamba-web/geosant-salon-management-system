<?php
session_start();
date_default_timezone_set('Africa/Accra');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

// Get appointments with customer and service
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
  <h4>Record Payment</h4>
 </div>

<div class="card-body">
<form action="store.php" method="POST">
    <div class="mb-3">
    <label class="form-label">Appointment</label>
    <select name="appointment_id" class="form-select" required>
        <option value="">Select Appointment</option>
        <?php while($row = mysqli_fetch_assoc($appointments)) { ?>
        <option value="<?php echo $row['id']; ?>">
        <?php
            echo $row['customer_name'] . " - " . $row['service_name'];
        ?></option>
        <?php } ?>

    </select>
</div>
                    
<div class="mb-3">
    <label class="form-label">Amount (GHS)</label>
        <input
            type="number"
            name="amount"
            class="form-control"
            step="0.01"
            min="0"
            required>
</div>

<div class="mb-3">
    <label class="form-label">Payment Method</label>

    <select name="payment_method" class="form-select" required>
        <option value="">Select Method</option>
        <option value="Cash">Cash</option>
        <option value="Mobile Money">Mobile Money</option>
        <option value="Card">Card</option>

    </select>

<div class="mb-3">
    <label class="form-label">Payment Date</label>

    <input
        type="text"
        class="form-control"
        value="<?php echo date('l, d F Y   H:i:s'); ?>"
        readonly>
</div>

<button type="submit" class="btn btn-success">
    Save Payment
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