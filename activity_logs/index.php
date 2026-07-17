<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

$page_title = "Activity Log";


//    DASHBOARD STATISTICS

$totalLogs = mysqli_fetch_assoc(
    mysqli_query($conn,"
        SELECT COUNT(*) AS total
        FROM activity_logs
    ")
)['total'];

$todayLogs = mysqli_fetch_assoc(
    mysqli_query($conn,"
        SELECT COUNT(*) AS total
        FROM activity_logs
        WHERE DATE(activity_time)=CURDATE()
    ")
)['total'];

$thisMonthLogs = mysqli_fetch_assoc(
    mysqli_query($conn,"
        SELECT COUNT(*) AS total
        FROM activity_logs
        WHERE MONTH(activity_time)=MONTH(CURDATE())
        AND YEAR(activity_time)=YEAR(CURDATE())
    ")
)['total'];

$activeUsers = mysqli_fetch_assoc(
    mysqli_query($conn,"
        SELECT COUNT(DISTINCT user_id) AS total
        FROM activity_logs
    ")
)['total'];


/* =====================================
   SEARCH & FILTER
===================================== */

$search = "";
$module = "";

$where = [];

if(isset($_GET['search']) && trim($_GET['search']) != ""){

    $search = mysqli_real_escape_string(
        $conn,
        trim($_GET['search'])
    );

    $where[] = "(
        full_name LIKE '%$search%'
        OR activity LIKE '%$search%'
    )";
}

if(isset($_GET['module']) && $_GET['module'] != ""){

    $module = mysqli_real_escape_string(
        $conn,
        $_GET['module']
    );

    $where[] = "module='$module'";
}


/* =====================================
   FETCH LOGS
===================================== */

$sql = "
SELECT *
FROM activity_logs
";

if(count($where) > 0){

    $sql .= " WHERE " . implode(" AND ", $where);

}

$sql .= " ORDER BY activity_time DESC";

$logs = mysqli_query($conn,$sql);


/* =====================================
   LOAD LAYOUT
===================================== */

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/navbar.php");
?>

<div class="main-content">
    <div class="container-fluid py-4">

    <?php include("../includes/messages.php"); ?>

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                <i class="fas fa-history text-primary me-2"></i>
                Activity Log
            </h2>

            <p class="text-muted mb-0">
                Monitor every activity performed by users within the system.
            </p>

        </div>

        <div>

            <a href="clear.php"class="btn btn-danger ms-2"
                onclick="return confirm('Are you sure you want to clear all activity logs?');">
                <i class="fas fa-trash"></i> Clear All Logs
            </a>

            <a href="print.php" class="btn btn-outline-dark me-2">
                <i class="fas fa-print me-1"></i>
                Print
            </a>

            <a href="export_excel.php" class="btn btn-success">
                <i class="fas fa-file-excel me-1"></i>
                Export Excel
            </a>

        </div>

    </div>

<!-- Statistics Cards -->

<div class="row g-4 mb-4">

    <div class="col-lg-3 col-md-6">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <h6 class="text-muted">
                    Total Activities
                </h6>

                <h2 class="fw-bold">
                    <?php echo $totalLogs; ?>
                </h2>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <h6 class="text-muted">
                    Today's Activities
                </h6>

                <h2 class="fw-bold text-primary">
                    <?php echo $todayLogs; ?>
                </h2>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <h6 class="text-muted">
                    This Month
                </h6>

                <h2 class="fw-bold text-success">
                    <?php echo $thisMonthLogs; ?>
                </h2>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <h6 class="text-muted">
                    Active Users
                </h6>

                <h2 class="fw-bold text-warning">
                    <?php echo $activeUsers; ?>
                </h2>

            </div>

        </div>

    </div>

</div>


<!-- Search & Filter -->

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
    <form method="GET">
        <div class="row g-3">
            <div class="col-md-6">
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search user or activity..."
                value="<?php echo htmlspecialchars($search); ?>">

</div>

<div class="col-md-4">

    <select
        name="module"
        class="form-select">

        <option value="">All Modules</option>

        <option value="Customers" <?php if($module=="Customers") echo "selected"; ?>>
            Customers
        </option>

        <option value="Staff" <?php if($module=="Staff") echo "selected"; ?>>
            Staff
        </option>

        <option value="Services" <?php if($module=="Services") echo "selected"; ?>>
            Services
        </option>

        <option value="Appointments" <?php if($module=="Appointments") echo "selected"; ?>>
            Appointments
        </option>

        <option value="Payments" <?php if($module=="Payments") echo "selected"; ?>>
            Payments
        </option>

        <option value="Users" <?php if($module=="Users") echo "selected"; ?>>
            Users
        </option>

        <option value="Authentication" <?php if($module=="Authentication") echo "selected"; ?>>
            Authentication
        </option>
    </select>

</div>
<div class="col-md-2 d-grid">
    <button class="btn btn-primary">
        <i class="fas fa-search me-1"></i>

            Search
    </button>
        </div>
    </div>
</form>
</div>
</div>


<!-- Activity Table -->

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>

            Activity History

        </h5>

    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
            <thead class="table-light">

<tr>

    <th>#</th>
    <th>Date & Time</th>
    <th>User</th>
    <th>Module</th>
    <th>Activity</th>
    <th width="120">Action</th>

</tr>

</thead>

<tbody>

<?php

$count = 1;

if(mysqli_num_rows($logs) > 0){

    while($row = mysqli_fetch_assoc($logs)){

        $badge = "secondary";

        switch($row['module']){

            case "Customers":
                $badge = "primary";
                break;

            case "Staff":
                $badge = "info";
                break;

            case "Services":
                $badge = "success";
                break;

            case "Appointments":
                $badge = "warning";
                break;

            case "Payments":
                $badge = "dark";
                break;

            case "Users":
                $badge = "secondary";
                break;

            case "Authentication":
                $badge = "danger";
                break;

        }

?>

<tr>

    <td>
        <?php echo $count++; ?>
    </td>

    <td>

        <strong>
            <?php echo date("d M Y", strtotime($row['activity_time'])); ?>
        </strong>

        <br>

        <small class="text-muted">
            <?php echo date("h:i A", strtotime($row['activity_time'])); ?>
        </small>

    </td>

    <td>

        <strong>
            <?php echo htmlspecialchars($row['full_name']); ?>
        </strong>

    </td>

    <td>

        <span class="badge bg-<?php echo $badge; ?>">
            <?php echo htmlspecialchars($row['module']); ?>
        </span>

    </td>

    <td>

        <?php echo htmlspecialchars($row['activity']); ?>

    </td>

    <td>

        <a href="view.php?id=<?php echo $row['id']; ?>"
           class="btn btn-info btn-sm">

            <i class="fas fa-eye"></i> View

        </a>

    </td>

</tr>

<?php

    }

}else{

?>

<tr>

    <td colspan="6" class="text-center py-5">

        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>

        <h5 class="mt-3 text-muted">

            No activity logs found

        </h5>

        <p class="text-muted mb-0">

            There are currently no activities matching your search.

        </p>

    </td>

</tr>

<?php

}

?>

</tbody>

            </table>

        </div>

    </div>

</div>

</div>

<?php include("../includes/footer.php"); ?>