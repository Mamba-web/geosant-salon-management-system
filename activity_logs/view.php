<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Invalid Activity Log.";
    header("Location: index.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

$result = mysqli_query($conn, "
    SELECT *
    FROM activity_logs
    WHERE id='$id'
");

if (mysqli_num_rows($result) == 0) {
    $_SESSION['error'] = "Activity Log not found.";
    header("Location: index.php");
    exit();
}

$log = mysqli_fetch_assoc($result);

$page_title = "View Activity Log";

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/navbar.php");
?>

<div class="main-content">
<div class="container-fluid">

<?php include("../includes/messages.php"); ?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2>Activity Details</h2>
        <p class="text-muted mb-0">
            View complete activity information.
        </p>
    </div>

    <div>

        <a href="print.php?id=<?php echo $log['id']; ?>"
           target="_blank"
           class="btn btn-dark me-2">
            <i class="fas fa-print"></i> Print
        </a>

        <a href="index.php"
           class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>

    </div>

</div>

<div class="card shadow-sm border-0">

<div class="card-body">

<table class="table table-bordered">

<tr>
    <th width="250">Activity ID</th>
    <td><?php echo $log['id']; ?></td>
</tr>

<tr>
    <th>User ID</th>
    <td><?php echo $log['user_id']; ?></td>
</tr>

<tr>
    <th>Full Name</th>
    <td><?php echo htmlspecialchars($log['full_name']); ?></td>
</tr>

<tr>
    <th>Module</th>
    <td>
        <span class="badge bg-primary">
            <?php echo htmlspecialchars($log['module']); ?>
        </span>
    </td>
</tr>

<tr>
    <th>Activity</th>
    <td><?php echo htmlspecialchars($log['activity']); ?></td>
</tr>

<tr>
    <th>Activity Time</th>
    <td>
        <?php echo date("d M Y h:i A", strtotime($log['activity_time'])); ?>
    </td>
</tr>

</table>

</div>

</div>

</div>
</div>

<?php include("../includes/footer.php"); ?>