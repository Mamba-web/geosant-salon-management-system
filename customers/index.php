<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

/* ==========================
   CUSTOMER STATISTICS
========================== */

$totalCustomers = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM customers")
);

$maleCustomers = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM customers WHERE gender='Male'")
);

$femaleCustomers = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM customers WHERE gender='Female'")
);

$newCustomers = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM customers
    WHERE MONTH(created_at)=MONTH(CURDATE())
    AND YEAR(created_at)=YEAR(CURDATE())")
);

/* ==========================
   PAGINATION
========================== */

$recordsPerPage = 10;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if($page < 1){
    $page = 1;
}

$offset = ($page - 1) * $recordsPerPage;

/* Total Records */

$totalQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM customers");
$totalRows = mysqli_fetch_assoc($totalQuery)['total'];

$totalPages = ceil($totalRows / $recordsPerPage);

/* ==========================
   SEARCH + PAGINATION
========================== */

$search = "";

if(isset($_GET['search'])){
    $search = mysqli_real_escape_string(
        $conn,
        trim($_GET['search'])
    );
}

if(!empty($search)){

    $totalQuery = mysqli_query($conn,"
        SELECT COUNT(*) AS total
        FROM customers
        WHERE customer_name LIKE '%$search%'
        OR phone LIKE '%$search%'
        OR gender LIKE '%$search%'
    ");

    $totalRows = mysqli_fetch_assoc($totalQuery)['total'];

    $totalPages = ceil($totalRows / $recordsPerPage);

    $sql = "
        SELECT *
        FROM customers
        WHERE customer_name LIKE '%$search%'
        OR phone LIKE '%$search%'
        OR gender LIKE '%$search%'
        ORDER BY id DESC
        LIMIT $recordsPerPage OFFSET $offset
    ";

}
else{

    $sql = "
        SELECT *
        FROM customers
        ORDER BY id DESC
        LIMIT $recordsPerPage OFFSET $offset
    ";

}

$result = mysqli_query($conn,$sql);

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/navbar.php");

?>

<div class="main-content">
    <div class="container-fluid">

    <?php include("../includes/messages.php"); ?>
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2>Customers</h2>
        <p class="text-muted mb-0">
            Manage all salon customers from one place.
        </p>
    </div>


    <div>
    <a href="create.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Customer
    </a>


<!-- PRINT Customer -->
<a href="print.php" target="_blank" class="btn btn-dark me-2">
    <i class="fas fa-print"></i> Print
</a>

<!-- Excel report -->
<a href="export_excel.php" class="btn btn-success me-2">
    <i class="fas fa-file-excel"></i> Excel
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
    placeholder="Search by customer name, phone or gender..."
    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">

</div>



<div class="col-md-2">
    <div class="d-flex gap-2">

        <button type="submit" class="btn btn-primary flex-fill">
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

<div class="col-md-3">
    <div class="card border-0 shadow-sm text-center">
        <div class="card-body">
            <i class="fas fa-users fa-2x text-primary mb-2"></i>
            <h3><?php echo $totalCustomers['total']; ?></h3>
            <p class="text-muted mb-0">Total Customers</p>
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="card border-0 shadow-sm text-center">
        <div class="card-body">
            <i class="fas fa-male fa-2x text-info mb-2"></i>
            <h3><?php echo $maleCustomers['total']; ?></h3>
            <p class="text-muted mb-0">Male</p>
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="card border-0 shadow-sm text-center">
        <div class="card-body">
            <i class="fas fa-female fa-2x text-danger mb-2"></i>
            <h3><?php echo $femaleCustomers['total']; ?></h3>
            <p class="text-muted mb-0">Female</p>
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="card border-0 shadow-sm text-center">
        <div class="card-body">
            <i class="fas fa-user-plus fa-2x text-success mb-2"></i>
            <h3><?php echo $newCustomers['total']; ?></h3>
            <p class="text-muted mb-0">New This Month</p>
        </div>
    </div>
</div>

</div>


<!-- Statistics -->
<div class="row mb-4">

<div class="col-lg-4 col-md-6 mb-3">

<div class="card dashboard-card bg-customers">

<div class="card-body">

<div class="icon-circle">
<i class="fas fa-users"></i>
</div>

<h6>Total Customers</h6>

<h2><?php echo $totalCustomers['total']; ?></h2>

</div>

</div>

</div>

<div class="col-lg-4 col-md-6 mb-3">

<div class="card dashboard-card bg-staff">

<div class="card-body">

<div class="icon-circle">
<i class="fas fa-male"></i>
</div>

<h6>Male Customers</h6>

<h2><?php echo $maleCustomers['total']; ?></h2>

</div>

</div>

</div>

<div class="col-lg-4 col-md-6 mb-3">

<div class="card dashboard-card bg-services">

<div class="card-body">

<div class="icon-circle">
<i class="fas fa-female"></i>
</div>

<h6>Female Customers</h6>

<h2><?php echo $femaleCustomers['total']; ?></h2>

</div>

</div>

</div>

</div>

<!-- Customer Table -->
<div class="card dashboard-table-card">

<div class="card-header d-flex justify-content-between align-items-center">

<h5 class="mb-0">
Customer List
</h5>

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

    <th>ID</th>
    <th>Customer Name</th>
    <th>Phone</th>
    <th>Gender</th>
    <th>Date Added</th>
    <th class="text-center">Actions</th>

</tr>

</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

    <td><?php echo $row['id']; ?></td>

    <td>

<?php

$name = trim($row['customer_name']);
$parts = explode(" ", $name);

$initials = "";

foreach($parts as $part){
    $initials .= strtoupper(substr($part,0,1));
}

?>

<div class="d-flex align-items-center">

    <div class="customer-avatar">
        <?php echo substr($initials,0,2); ?>
    </div>

    <div class="ms-3">
        <strong><?php echo $row['customer_name']; ?></strong>
    </div>

</div>

</td>

<td><?php echo $row['phone']; ?></td>

<td>

<?php if($row['gender']=="Male"){ ?>

<span class="badge bg-primary">
    Male
</span>

<?php }else{ ?>

<span class="badge bg-danger">
    Female
</span>

<?php } ?>

</td>

<td>
<?php echo date("d M Y", strtotime($row['created_at'])); ?>
</td>

<td>

<a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm me-1" title="Edit">
    <i class="fas fa-edit"></i>
</a>

<button
    class="btn btn-danger btn-sm"
    data-bs-toggle="modal"
    data-bs-target="#deleteModal<?php echo $row['id']; ?>"
    title="Delete">

    <i class="fas fa-trash"></i>

</button>

</td>

</tr>

<!-- Delete Modal -->

<div class="modal fade" id="deleteModal<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Delete Customer
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

            </div>

            <div class="modal-body">

                Are you sure you want to delete
                <strong><?php echo $row['customer_name']; ?></strong>?

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
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

</div>
<?php include("../includes/footer_content.php"); ?>
</div>

<?php include("../includes/footer.php"); ?>