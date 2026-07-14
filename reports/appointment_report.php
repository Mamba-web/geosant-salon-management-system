<?php
session_start();

if (!isset($_SESSION['user_id'])) {
header("Location: ../auth/login.php");
exit();
}

include("../config/database.php");

/* ==========================
REPORT FILTERS
========================== */

$search = $_GET['search'] ?? '';
$from   = $_GET['from'] ?? '';
$to     = $_GET['to'] ?? '';

$sql = "
SELECT
appointments.id,
customers.customer_name,
staff.full_name,
services.service_name,
appointments.appointment_date,
appointments.status
FROM appointments
INNER JOIN customers ON appointments.customer_id = customers.id
INNER JOIN staff ON appointments.staff_id = staff.id
INNER JOIN services ON appointments.service_id = services.id
WHERE 1=1
";

if($search != ""){

$search = mysqli_real_escape_string($conn,$search);

$sql .= " AND (
customers.customer_name LIKE '%$search%'
OR staff.full_name LIKE '%$search%'
OR services.service_name LIKE '%$search%'
)";

}

if($from != ""){

$sql .= " AND DATE(appointments.appointment_date) >= '$from'";

}

if($to != ""){

$sql .= " AND DATE(appointments.appointment_date) <= '$to'";

}

$sql .= " ORDER BY appointments.appointment_date DESC";

$appointments = mysqli_query($conn,$sql);

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/navbar.php");
?>

<div class="main-content">

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="fw-bold">Appointment Report</h2>

<p class="text-muted mb-0">
Manage and monitor all salon appointments
</p>

</div>

</div>

<!-- Report Toolbar -->

<div class="card shadow-sm border-0 mb-4">

<div class="card-body">
<form method="GET">

<div class="row g-3 align-items-end">

<!-- Search -->

<div class="col-lg-3">

<label class="form-label">Search</label>

<input
type="text"
name="search"
class="form-control"
placeholder="Customer or Staff"
value="<?php echo htmlspecialchars($search); ?>">

</div>

<!-- From Date -->

<div class="col-lg-2">

<label class="form-label">From</label>

<input
type="date"
name="from"
class="form-control"
value="<?php echo $from; ?>">

</div>

<!-- To Date -->

<div class="col-lg-2">

<label class="form-label">To</label>

<input
type="date"
name="to"
class="form-control"
value="<?php echo $to; ?>">

</div>

<!-- Filter -->

<div class="col-lg-2">

<button
type="submit"
class="btn btn-primary w-100">

    <i class="fas fa-filter"></i>

    Filter

</button>

</div>

<!-- Print -->

<div class="col-lg-1">

<button
type="button"
id="printReport"
class="btn btn-dark w-100">

<i class="fas fa-print"></i>

</button>

</div>

<!-- Export -->

<div class="col-lg-2">

<a
href="export_appointments_excel.php?search=<?php echo urlencode($search); ?>&from=<?php echo urlencode($from); ?>&to=<?php echo urlencode($to); ?>"
class="btn btn-success w-100">

<i class="fas fa-file-excel"></i>

Export

</a>

</div>

</div>

</div>

</form>

</div>

<div class="card dashboard-table-card">

<div class="card-body">

    <div class="table-responsive">

        <table class="table dashboard-table table-hover">

            <thead>

                <tr>

                    <th>ID</th>
                    <th>Customer</th>
                    <th>Staff</th>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Status</th>

                </tr>

            </thead>

            <tbody>

            <?php while($row=mysqli_fetch_assoc($appointments)){ ?>

                <tr>

                    <td><?php echo $row['id']; ?></td>

                    <td><?php echo $row['customer_name']; ?></td>

                    <td><?php echo $row['full_name']; ?></td>

                    <td><?php echo $row['service_name']; ?></td>

                    <td><?php echo $row['appointment_date']; ?></td>

                    <td>

                    <?php

                    $status = strtolower($row['status']);

                    if($status=="pending"){

                        echo "<span class='status-badge status-pending'>Pending</span>";

                    }

                    elseif($status=="completed"){

                        echo "<span class='status-badge status-completed'>Completed</span>";

                    }

                    elseif($status=="cancelled"){

                        echo "<span class='status-badge status-cancelled'>Cancelled</span>";

                    }

                    ?>

                    </td>

                </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

</div>

</div>
<?php include("../includes/footer_content.php"); ?>
</div>

<script>

document.getElementById("printReport").addEventListener("click", function(){

window.print();

});

</script>

<?php include("../includes/footer.php"); ?>