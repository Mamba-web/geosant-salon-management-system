<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

/* ==========================
   STAFF STATISTICS
========================== */

$totalStaff = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM staff"));
$maleStaff = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM staff WHERE gender='Male'"));
$femaleStaff = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM staff WHERE gender='Female'"));

/* ==========================
   PAGINATION
========================== */

$recordsPerPage = 10;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if($page < 1){
    $page = 1;
}

$offset = ($page-1) * $recordsPerPage;

/* ==========================
   SEARCH
========================== */

$search = "";

if(isset($_GET['search'])){
    $search = mysqli_real_escape_string($conn, trim($_GET['search']));
}

if($search != ""){

    $count = mysqli_query($conn,"
    SELECT COUNT(*) total
    FROM staff
    WHERE full_name LIKE '%$search%'
    OR phone LIKE '%$search%'
    OR role LIKE '%$search%'
    ");

    $totalRows = mysqli_fetch_assoc($count)['total'];

    $sql = "
    SELECT *
    FROM staff
    WHERE full_name LIKE '%$search%'
    OR phone LIKE '%$search%'
    OR role LIKE '%$search%'
    ORDER BY id DESC
    LIMIT $recordsPerPage OFFSET $offset
    ";

}else{

    $count = mysqli_query($conn,"SELECT COUNT(*) total FROM staff");

    $totalRows = mysqli_fetch_assoc($count)['total'];

    $sql = "
    SELECT *
    FROM staff
    ORDER BY id DESC
    LIMIT $recordsPerPage OFFSET $offset
    ";

}

$totalPages = ceil($totalRows/$recordsPerPage);


$result = mysqli_query($conn,$sql);


include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/navbar.php");

?>

<div class="main-content">

<div class="container-fluid">
<?php include("../includes/messages.php"); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
<div>
<h2>Staff Management</h2>
<p class="text-muted mb-0">
Manage all salon staff members.
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
    <i class="fas fa-plus"></i> Add Staff
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
        placeholder="Search by name, phone or role..."
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
 <div class="col-md-4">
   <div class="card border-0 shadow-sm text-center">
    <div class="card-body">
     <i class="fas fa-users fa-2x text-primary mb-2"></i>
      <h3><?php echo $totalStaff['total']; ?></h3>
 
        <p class="text-muted mb-0">Total Staff</p>
</div>
 </div>
  </div>

<div class="col-md-4">
 <div class="card border-0 shadow-sm text-center">
   <div class="card-body">
     <i class="fas fa-male fa-2x text-info mb-2"></i>
       <h3><?php echo $maleStaff['total']; ?></h3>
        <p class="text-muted mb-0">Male Staff</p>
</div>
</div>
</div>

<div class="col-md-4">
  <div class="card border-0 shadow-sm text-center">
    <div class="card-body">
      <i class="fas fa-female fa-2x text-danger mb-2"></i>
        <h3><?php echo $femaleStaff['total']; ?></h3>
        <p class="text-muted mb-0">Female Staff</p>
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
    <th>Full Name</th>
    <th>Gender</th>
    <th>Phone</th>
    <th>Role</th>
    <th>Date Added</th>
    <th>Actions</th>
</tr>
</thead>

<tbody>
<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>
<td><?php echo $row['id']; ?></td>
<td>
<?php

$name = trim($row['full_name']);
$parts = explode(" ",$name);

$initials="";

foreach($parts as $part){
    $initials .= strtoupper(substr($part,0,1));
}

?>

<div class="d-flex align-items-center">

<div class="customer-avatar">

<?php echo substr($initials,0,2); ?>

</div>

<div class="ms-3">

<strong><?php echo $row['full_name']; ?></strong>

</div>

</div>

</td>

<td>

<?php if($row['gender']=="Male"){ ?>

<span class="badge bg-primary">Male</span>

<?php }else{ ?>

<span class="badge bg-danger">Female</span>

<?php } ?>

</td>

<td><?php echo $row['phone']; ?></td>

<td>

<span class="badge bg-success">

<?php echo $row['role']; ?>

</span>

</td>

<td>

<?php echo date("d M Y",strtotime($row['created_at'])); ?>

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

<h5 class="modal-title">Delete Staff</h5>

<button class="btn-close" data-bs-dismiss="modal"></button>

</div>

<div class="modal-body">

Delete

<strong><?php echo $row['full_name']; ?></strong>

?

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