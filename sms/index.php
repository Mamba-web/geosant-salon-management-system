<?php
session_start();

date_default_timezone_set("Africa/Accra");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

$search = "";

if(isset($_GET['search'])){
    $search = mysqli_real_escape_string($conn, trim($_GET['search']));
}

$sql = "SELECT * FROM sms_logs";

if(!empty($search)){
    $sql .= " WHERE customer_name LIKE '%$search%'
           OR phone LIKE '%$search%'
           OR service_name LIKE '%$search%'
           OR status LIKE '%$search%'";
}

$sql .= " ORDER BY id DESC";

$result = mysqli_query($conn,$sql);

$totalSMS = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) AS total FROM sms_logs")
);

$pendingSMS = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) AS total FROM sms_logs WHERE status='Pending'")
);

$sentSMS = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) AS total FROM sms_logs WHERE status='Sent'")
);

$failedSMS = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) AS total FROM sms_logs WHERE status='Failed'")
);

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/navbar.php");
?>

<div class="main-content">

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2>SMS History</h2>

<p class="text-muted">
View all generated SMS notifications.
</p>

</div>

</div>

<div class="row mb-4">

<div class="col-md-3">
<div class="card border-0 shadow-sm text-center">
<div class="card-body">

<i class="fas fa-sms fa-2x text-primary mb-2"></i>

<h3><?php echo $totalSMS['total']; ?></h3>

<p>Total SMS</p>

</div>
</div>
</div>

<div class="col-md-3">
<div class="card border-0 shadow-sm text-center">
<div class="card-body">

<i class="fas fa-clock fa-2x text-warning mb-2"></i>

<h3><?php echo $pendingSMS['total']; ?></h3>

<p>Pending</p>

</div>
</div>
</div>

<div class="col-md-3">
<div class="card border-0 shadow-sm text-center">
<div class="card-body">

<i class="fas fa-check-circle fa-2x text-success mb-2"></i>

<h3><?php echo $sentSMS['total']; ?></h3>

<p>Sent</p>

</div>
</div>
</div>

<div class="col-md-3">
<div class="card border-0 shadow-sm text-center">
<div class="card-body">

<i class="fas fa-times-circle fa-2x text-danger mb-2"></i>

<h3><?php echo $failedSMS['total']; ?></h3>

<p>Failed</p>

</div>
</div>
</div>

</div>

<div class="card shadow-sm">

<div class="card-header d-flex justify-content-between align-items-center">

<h4>SMS History</h4>

<form method="GET" class="d-flex">

<input
type="text"
name="search"
class="form-control me-2"
placeholder="Search..."
value="<?php echo htmlspecialchars($search); ?>">

<button class="btn btn-primary">
<i class="fas fa-search"></i>
</button>



</form>
<div>
    <a href="print.php" target="_blank" class="btn btn-dark">
        <i class="fas fa-print"></i> Print
    </a>

    <a href="pdf.php" target="_blank" class="btn btn-danger">
        <i class="fas fa-file-pdf"></i> PDF
    </a>

    <a href="excel.php" class="btn btn-success">
        <i class="fas fa-file-excel"></i> Excel
    </a>
</div>
</div>


<div class="card-body table-responsive">

<table class="table table-hover align-middle">

<thead>

<tr>

<th>ID</th>
<th>Customer</th>
<th>Phone</th>
<th>Service</th>
<th>Amount</th>
<th>Status</th>
<th>Date</th>
<th class="text-center">Action</th>

</tr>

</thead>

<tbody>

<?php if(mysqli_num_rows($result) > 0){ ?>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo htmlspecialchars($row['customer_name']); ?></td>

<td><?php echo htmlspecialchars($row['phone']); ?></td>

<td><?php echo htmlspecialchars($row['service_name']); ?></td>

<td>GHS <?php echo number_format($row['amount'],2); ?></td>

<td>

<?php
$status = strtolower($row['status']);

if($status == "pending"){
    echo '<span class="badge bg-warning">Pending</span>';
}elseif($status == "sent"){
    echo '<span class="badge bg-success">Sent</span>';
}else{
    echo '<span class="badge bg-danger">Failed</span>';
}
?>

</td>

<td>

<?php echo date("d M Y", strtotime($row['created_at'])); ?>

</td>

<td class="text-center">

<a href="view.php?id=<?php echo $row['id']; ?>"
   class="btn btn-info btn-sm">

<i class="fas fa-eye"></i>

</a>

</td>

</tr>

<!-- SMS Modal -->

<div class="modal fade"
id="smsModal<?php echo $row['id']; ?>"
tabindex="-1">

<div class="modal-dialog modal-lg">

<div class="modal-content">

<div class="modal-header">

<h5 class="modal-title">

SMS Message

</h5>

<button
type="button"
class="btn-close"
data-bs-dismiss="modal">

</button>

</div>

<div class="modal-body">

<pre style="white-space:pre-wrap;font-family:inherit;"><?php echo htmlspecialchars($row['message']); ?></pre>

</div>

<div class="modal-footer">

<button
class="btn btn-secondary"
data-bs-dismiss="modal">

Close

</button>

</div>

</div>

</div>

</div>

<?php } ?>

<?php }else{ ?>

<tr>

<td colspan="8" class="text-center">

No SMS records found yet. 

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<?php include("../includes/footer.php"); ?>