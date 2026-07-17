<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

/* ==========================
   APPOINTMENT STATISTICS
========================== */

$totalAppointments = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM appointments"));
$pendingAppointments = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM appointments WHERE status='Pending'"));
$completedAppointments = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM appointments WHERE status='Completed'"));

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
    $where="WHERE customers.customer_name LIKE '%$search%' OR staff.full_name LIKE '%$search%' OR services.service_name LIKE '%$search%'";
}

$count=mysqli_query($conn,"
SELECT COUNT(*) total
FROM appointments
INNER JOIN customers ON appointments.customer_id=customers.id
INNER JOIN staff ON appointments.staff_id=staff.id
INNER JOIN services ON appointments.service_id=services.id
$where
");

$totalRows=mysqli_fetch_assoc($count)['total'];

$sql="
SELECT
appointments.*,
customers.customer_name,
staff.full_name,
services.service_name
FROM appointments
INNER JOIN customers ON appointments.customer_id=customers.id
INNER JOIN staff ON appointments.staff_id=staff.id
INNER JOIN services ON appointments.service_id=services.id
$where
ORDER BY appointments.id DESC
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

<h2>Appointments</h2>

<p class="text-muted mb-0">
Manage salon appointments
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
    <i class="fas fa-plus"></i> New Appointment
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
            placeholder="Search customer, staff or service..."
            value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">

</div>

<div class="col-md-2">
<div class="d-flex gap-2">

<button class="btn btn-primary flex-fill"><i class="fas fa-search"></i></button>
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

<div class="col-md-4">

<div class="card border-0 shadow-sm text-center">

<div class="card-body">

<i class="fas fa-calendar-check fa-2x text-primary mb-2"></i>

<h3><?php echo $totalAppointments['total']; ?></h3>

<p class="text-muted mb-0">Total Appointments</p>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card border-0 shadow-sm text-center">

<div class="card-body">

<i class="fas fa-hourglass-half fa-2x text-warning mb-2"></i>

<h3><?php echo $pendingAppointments['total']; ?></h3>

<p class="text-muted mb-0">Pending</p>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card border-0 shadow-sm text-center">

<div class="card-body">

<i class="fas fa-check-circle fa-2x text-success mb-2"></i>

<h3><?php echo $completedAppointments['total']; ?></h3>

<p class="text-muted mb-0">Completed</p>

</div>

</div>

</div>

</div>

<div class="card shadow-sm">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Customer</th>
<th>Staff</th>
<th>Service</th>
<th>Date</th>
<th>Time</th>
<th>Status</th>
<th>Actions</th>

</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><strong><?php echo $row['customer_name']; ?></strong></td>

<td><?php echo $row['full_name']; ?></td>

<td><?php echo $row['service_name']; ?></td>

<td><?php echo date("d M Y",strtotime($row['appointment_date'])); ?></td>

<td><?php echo date("h:i A",strtotime($row['appointment_time'])); ?></td>

<td>

<?php

$status=strtolower($row['status']);

if($status=="pending"){

echo "<span class='status-badge status-pending'>Pending</span>";

}elseif($status=="completed"){

echo "<span class='status-badge status-completed'>Completed</span>";

}else{

echo "<span class='status-badge status-cancelled'>Cancelled</span>";

}

?>

</td>

<td>

<a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm me-1">

<i class="fas fa-edit"></i>

</a>

<button class="btn btn-danger btn-sm"
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

<h5 class="modal-title">Delete Appointment</h5>

<button class="btn-close" data-bs-dismiss="modal"></button>

</div>

<div class="modal-body">

Delete appointment for

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

</div>
<?php include("../includes/footer_content.php"); ?>
</div>
<?php include("../includes/footer.php"); ?>