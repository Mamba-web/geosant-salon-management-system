<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

$search = $_GET['search'] ?? '';
$from   = $_GET['from'] ?? '';
$to     = $_GET['to'] ?? '';

$sql = "
SELECT
payments.*,
customers.customer_name,
services.service_name
FROM payments
INNER JOIN appointments ON payments.appointment_id=appointments.id
INNER JOIN customers ON appointments.customer_id=customers.id
INNER JOIN services ON appointments.service_id=services.id
WHERE 1=1
";

if($search!=""){

$search=mysqli_real_escape_string($conn,$search);

$sql.=" AND (
customers.customer_name LIKE '%$search%'
OR services.service_name LIKE '%$search%'
OR payments.payment_method LIKE '%$search%'
)";

}

if($from!=""){

$sql.=" AND DATE(payments.payment_date)>='$from'";

}

if($to!=""){

$sql.=" AND DATE(payments.payment_date)<='$to'";

}

$sql.=" ORDER BY payments.payment_date DESC";

$payments=mysqli_query($conn,$sql);

$total=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(amount) AS total
FROM payments
"));
include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/navbar.php");
?>

<div class="main-content">

<div class="container-fluid">

<h2 class="fw-bold">
Revenue Report
</h2>

<p class="text-muted">
Manage and monitor all salon revenue.
</p>

<!-- Report Toolbar -->

<div class="card shadow-sm border-0 mb-4">

<div class="card-body">

<form method="GET">

<div class="row g-3 align-items-end">

<div class="col-lg-3">

<label class="form-label">Search</label>

<input
type="text"
name="search"
class="form-control"
placeholder="Customer, Service or Method"
value="<?php echo htmlspecialchars($search); ?>">

</div>

<div class="col-lg-2">

<label class="form-label">From</label>

<input
type="date"
name="from"
class="form-control"
value="<?php echo $from; ?>">

</div>

<div class="col-lg-2">

<label class="form-label">To</label>

<input
type="date"
name="to"
class="form-control"
value="<?php echo $to; ?>">

</div>

<div class="col-lg-1">

<button class="btn btn-primary w-100">

<i class="fas fa-filter"></i>

</button>

</div>

<div class="col-lg-1">

<a href="revenue_report.php"
class="btn btn-secondary w-100">

<i class="fas fa-rotate-left"></i>

</a>

</div>

<div class="col-lg-1">

<button
type="button"
id="printReport"
class="btn btn-dark w-100">

<i class="fas fa-print"></i>

</button>

</div>

<div class="col-lg-2">

<a
href="export_revenue_excel.php?search=<?php echo urlencode($search); ?>&from=<?php echo urlencode($from); ?>&to=<?php echo urlencode($to); ?>"
class="btn btn-success w-100">

<i class="fas fa-file-excel"></i>

Export

</a>

</div>

</div>

</form>

</div>

</div>

<div class="alert alert-success mb-4">

<h5 class="mb-0">

Total Revenue :

<strong>

GHS <?php echo number_format($total['total'] ?? 0,2); ?>

</strong>

</h5>

</div>


<div class="card shadow-sm border-0 rounded-4">

<div class="card-body">

<table class="table align-middle">

<thead>

<tr>

<th>ID</th>
<th>Customer</th>
<th>Service</th>
<th>Amount</th>
<th>Method</th>
<th>Date</th>

</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($payments)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['customer_name']; ?></td>

<td><?php echo $row['service_name']; ?></td>

<td>

<strong>

GHS <?php echo number_format($row['amount'],2); ?>

</strong>

</td>

<td>

<span class="status-badge status-pending">

<?php echo $row['payment_method']; ?>

</span>

</td>

<td>

<?php echo date("d M Y",strtotime($row['payment_date'])); ?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<script>

document.getElementById("printReport").addEventListener("click",function(){

window.print();

});

</script>

<?php include("../includes/footer.php"); ?>