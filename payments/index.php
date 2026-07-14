<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

/* ==========================
   PAYMENT STATISTICS
========================== */

$totalPayments = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM payments"));

$totalRevenue = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(amount) total FROM payments"));

/* ==========================
   PAGINATION + SEARCH
========================== */

$recordsPerPage = 10;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if($page<1) $page=1;

$offset = ($page-1)*$recordsPerPage;

$search="";

if(isset($_GET['search'])){
    $search=mysqli_real_escape_string($conn,$_GET['search']);
}

$where="";

if($search!=""){
    $where="WHERE customers.customer_name LIKE '%$search%' OR services.service_name LIKE '%$search%' OR payments.payment_method LIKE '%$search%'";
}

$count=mysqli_query($conn,"
SELECT COUNT(*) total
FROM payments
INNER JOIN appointments ON payments.appointment_id=appointments.id
INNER JOIN customers ON appointments.customer_id=customers.id
INNER JOIN services ON appointments.service_id=services.id
$where
");

$totalRows=mysqli_fetch_assoc($count)['total'];

$sql="
SELECT
payments.*,
customers.customer_name,
services.service_name
FROM payments
INNER JOIN appointments ON payments.appointment_id=appointments.id
INNER JOIN customers ON appointments.customer_id=customers.id
INNER JOIN services ON appointments.service_id=services.id
$where
ORDER BY payments.id DESC
LIMIT $recordsPerPage OFFSET $offset
";

$totalPages=ceil($totalRows/$recordsPerPage);

$result=mysqli_query($conn,$sql);

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/navbar.php");
?>

<div class="main-content">

<div class="container-fluid">

<?php include("../includes/messages.php"); ?>

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2>Payments</h2>

<p class="text-muted mb-0">
Manage all payment records.
</p>

</div>

<div>

<a href="print.php" target="_blank" class="btn btn-dark me-2">
<i class="fas fa-print"></i> Print
</a>

<a href="export_excel.php" class="btn btn-success me-2">
<i class="fas fa-file-excel"></i> Excel
</a>

<a href="create.php" class="btn btn-primary">
<i class="fas fa-plus"></i> Record Payment
</a>

</div>

</div>

<div class="card mb-4">

<div class="card-body">

<form method="GET">

<div class="row">

<div class="col-md-10">

<input
type="text"
name="search"
class="form-control"
placeholder="Search customer, service or payment method..."
value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">

</div>

<div class="col-md-2">

<div class="d-flex gap-2">

<button class="btn btn-primary flex-fill">
<i class="fas fa-search"></i>
</button>

<a href="index.php" class="btn btn-secondary">
<i class="fas fa-rotate-left"></i>
</a>

</div>

</div>

</div>

</form>

</div>

</div>

<div class="row mb-4">

<div class="col-md-6">

<div class="card border-0 shadow-sm text-center">

<div class="card-body">

<i class="fas fa-money-bill-wave fa-2x text-success mb-2"></i>

<h3>GHS <?php echo number_format($totalRevenue['total'] ?? 0,2); ?></h3>

<p class="text-muted mb-0">Total Revenue</p>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card border-0 shadow-sm text-center">

<div class="card-body">

<i class="fas fa-credit-card fa-2x text-primary mb-2"></i>

<h3><?php echo $totalPayments['total']; ?></h3>

<p class="text-muted mb-0">Total Payments</p>

</div>

</div>

</div>

</div>
</div>

<div class="table-responsive">

<div class="card-body">

<table class="table table-bordered table-hover">
<thead class="table-dark">

<tr>
<th>ID</th>
<th>Customer</th>
<th>Service</th>
<th>Amount (GHS)</th>
<th>Method</th>
<th>Payment Date</th>
<th>Actions</th>
</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td>

<strong><?php echo $row['customer_name']; ?></strong>

</td>

<td>

<?php echo $row['service_name']; ?>

</td>

<td>

<span class="badge bg-success">

GHS <?php echo number_format($row['amount'],2); ?>

</span>

</td>

<td>

<?php

$method = strtolower($row['payment_method']);

if($method=="cash"){

echo "<span class='badge bg-primary'>Cash</span>";

}elseif($method=="momo"){

echo "<span class='badge bg-warning text-dark'>MoMo</span>";

}else{

echo "<span class='badge bg-info text-dark'>Card</span>";

}

?>

</td>

<td>

<?php echo date("d M Y",strtotime($row['payment_date'])); ?>

</td>

<td>

<a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm me-1">

<i class="fas fa-edit"></i>

</a>

<button
class="btn btn-danger btn-sm"
data-bs-toggle="modal"
data-bs-target="#deleteModal<?php echo $row['id']; ?>">

<i class="fas fa-trash"></i>

</button>

</td>

</tr>

<div class="modal fade" id="deleteModal<?php echo $row['id']; ?>" tabindex="-1">

<div class="modal-dialog">

<div class="modal-content">

<div class="modal-header">

<h5 class="modal-title">

Delete Payment

</h5>

<button class="btn-close" data-bs-dismiss="modal"></button>

</div>

<div class="modal-body">

Delete payment for

<strong><?php echo $row['customer_name']; ?></strong>?

</div>

<div class="modal-footer">

<button class="btn btn-secondary" data-bs-dismiss="modal">

Cancel

</button>

<a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">

Delete

</a>

</div>

</div>

</div>

</div>

<?php } ?>

</tbody>

</table>

</div>

</div>
<?php include("../includes/footer_content.php"); ?>
</div>
</div>

<?php include("../includes/footer.php"); ?>