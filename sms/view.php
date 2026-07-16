<?php
session_start();

date_default_timezone_set("Africa/Accra");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int) $_GET['id'];

$sql = "SELECT * FROM sms_logs WHERE id = $id";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
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

<div class="card-header d-flex justify-content-between align-items-center">

<h4>SMS Details</h4>

<a href="index.php" class="btn btn-secondary">
<i class="fas fa-arrow-left"></i> Back
</a>

</div>

<div class="card-body">

<div class="row">

<div class="col-md-6">

<p><strong>Customer:</strong><br>
<?php echo htmlspecialchars($row['customer_name']); ?>
</p>

<p><strong>Phone Number:</strong><br>
<?php echo htmlspecialchars($row['phone']); ?>
</p>

<p><strong>Service:</strong><br>
<?php echo htmlspecialchars($row['service_name']); ?>
</p>

<p><strong>Amount:</strong><br>
GHS <?php echo number_format($row['amount'],2); ?>
</p>

</div>

<div class="col-md-6">

<p><strong>Status:</strong><br>

<?php
if($row['status']=="Pending"){
    echo '<span class="badge bg-warning">Pending</span>';
}elseif($row['status']=="Sent"){
    echo '<span class="badge bg-success">Sent</span>';
}else{
    echo '<span class="badge bg-danger">Failed</span>';
}
?>

</p>

<p><strong>Provider:</strong><br>
<?php echo htmlspecialchars($row['provider']); ?>
</p>

<p><strong>Date Created:</strong><br>
<?php echo date("d F Y h:i A", strtotime($row['created_at'])); ?>
</p>

</div>

</div>

<hr>

<h5>SMS Message</h5>

<div class="border rounded p-3 bg-light">

<pre style="white-space:pre-wrap;font-family:inherit;margin:0;"><?php echo htmlspecialchars($row['message']); ?></pre>

</div>

</div>

</div>

</div>

</div>

<?php include("../includes/footer.php"); ?>