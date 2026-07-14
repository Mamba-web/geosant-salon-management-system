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

$sql = "SELECT * FROM services WHERE 1=1";

if($search!=""){

$search=mysqli_real_escape_string($conn,$search);

$sql.=" AND (
service_name LIKE '%$search%'
)";

}

if($from!=""){

$sql.=" AND DATE(created_at)>='$from'";

}

if($to!=""){

$sql.=" AND DATE(created_at)<='$to'";

}

$sql.=" ORDER BY created_at DESC";

$services=mysqli_query($conn,$sql);

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/navbar.php");
?>

<div class="main-content">

<div class="container-fluid">

<h2 class="fw-bold">
Service Report
</h2>

<p class="text-muted">
Manage and monitor all salon services.
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
placeholder="Service Name"
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

<button
class="btn btn-primary w-100">

<i class="fas fa-filter"></i>

</button>

</div>

<div class="col-lg-1">

<a
href="service_report.php"
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
href="export_service_excel.php?search=<?php echo urlencode($search); ?>&from=<?php echo urlencode($from); ?>&to=<?php echo urlencode($to); ?>"
class="btn btn-success w-100">

<i class="fas fa-file-excel"></i>

Export

</a>

</div>

</div>

</form>

</div>

</div>

<div class="card shadow-sm border-0 rounded-4">

<div class="card-body">

<table class="table align-middle">

<thead>

<tr>

<th>ID</th>
<th>Service</th>
<th>Price</th>
<th>Date Added</th>

</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($services)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td>

<strong>

<?php echo $row['service_name']; ?>

</strong>

</td>

<td>

GHS <?php echo number_format($row['price'],2); ?>

</td>

<td>

<?php echo date("d M Y",strtotime($row['created_at'])); ?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>
<?php include("../includes/footer_content.php"); ?>
</div>

<script>

document.getElementById("printReport").addEventListener("click",function(){

window.print();

});

</script>

<?php include("../includes/footer.php"); ?>