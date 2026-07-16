<?php
require_once '../auth/check_auth.php';

include("../config/database.php");

// Dashboard Statistics
$customers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM customers"));
$staff = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM staff"));
$services = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM services"));
$appointments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM appointments"));
$payments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM payments"));
$revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) AS total FROM payments"));
$totalRevenue = $revenue['total'] ?? 0;


//    TODAY'S OVERVIEW

// Today's Appointments
$todayAppointments = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM appointments
WHERE DATE(appointment_date)=CURDATE()
"));

// Today's Revenue
$todayRevenue = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(amount) AS total
FROM payments
WHERE DATE(payment_date)=CURDATE()
"));

// New Customers Today
$newCustomers = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM customers
WHERE DATE(created_at)=CURDATE()
"));

// Next Appointment Today
$nextAppointment = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT
customers.customer_name,
appointment_date
FROM appointments
INNER JOIN customers
ON appointments.customer_id=customers.id
WHERE appointment_date>=NOW()
ORDER BY appointment_date ASC
LIMIT 1
"));

// Monthly Revenue
$monthlyRevenue = mysqli_query($conn, "
SELECT
    MONTH(payment_date) AS month,
    SUM(amount) AS total
FROM payments
GROUP BY MONTH(payment_date)
ORDER BY MONTH(payment_date)
");

// Monthly Appointments
$monthlyAppointments = mysqli_query($conn, "
SELECT
    MONTH(appointment_date) AS month,
    COUNT(*) AS total
FROM appointments
GROUP BY MONTH(appointment_date)
ORDER BY MONTH(appointment_date)
");

// Arrays for Chart.js
$revenueData = array_fill(0, 12, 0);
$appointmentData = array_fill(0, 12, 0);

// Store Monthly Revenue
while($row = mysqli_fetch_assoc($monthlyRevenue)){

    $revenueData[$row['month'] - 1] = $row['total'];

}

// Store Monthly Appointments
while($row = mysqli_fetch_assoc($monthlyAppointments)){

    $appointmentData[$row['month'] - 1] = $row['total'];

}

// Recent Appointments
$recentAppointments = mysqli_query($conn,"
SELECT

appointments.appointment_date,
appointments.status,
customers.customer_name,
staff.full_name,
services.service_name

FROM appointments

INNER JOIN customers
ON appointments.customer_id=customers.id

INNER JOIN staff
ON appointments.staff_id=staff.id

INNER JOIN services
ON appointments.service_id=services.id

ORDER BY appointments.id DESC

LIMIT 5
");

// Recent Payments
$recentPayments = mysqli_query($conn, "
SELECT
    customers.customer_name,
    payments.amount,
    payments.payment_method,
    payments.payment_date
FROM payments
INNER JOIN appointments
    ON payments.appointment_id = appointments.id
INNER JOIN customers
    ON appointments.customer_id = customers.id
ORDER BY payments.id DESC
LIMIT 5
");

// RECENT ACTIVITY
$activities = mysqli_query($conn, "

SELECT
'customer' AS activity_type,
customer_name AS activity_name,
created_at
FROM customers

UNION ALL

SELECT
'appointment',
CONCAT('Appointment #', id),
created_at
FROM appointments

UNION ALL

SELECT
'payment',
CONCAT('Payment GHS ', amount),
created_at
FROM payments

UNION ALL

SELECT
'service',
service_name,
created_at
FROM services

ORDER BY created_at DESC

LIMIT 8

");

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/navbar.php");
?>

<div class="main-content dashboard-section">
<div class="container-fluid">

        <!-- TODAY'S OVERVIEW -->
<h4 class="section-title mb-3">Today's Overview</h4>
<div class="row g-4 mb-4">

    <!-- Today's Appointments -->
    <div class="col-lg-3 col-md-6">
        <div class="today-card">
            <div class="today-icon bg-primary">
                <i class="fas fa-calendar-day"></i>
            </div>

            <div class="today-info">
                <h3><?php echo $todayAppointments['total']; ?></h3>
                <p>Today's Appointments</p>
            </div>
        </div>
    </div>

    <!-- Today's Revenue -->
    <div class="col-lg-3 col-md-6">
        <div class="today-card">
            <div class="today-icon bg-success">
                <i class="fas fa-coins"></i>
            </div>

            <div class="today-info">
                <h3>GHS <?php echo number_format($todayRevenue['total'] ?? 0,2); ?></h3>
                <p>Today's Revenue</p>
            </div>
        </div>
    </div>

    <!-- New Customers -->
    <div class="col-lg-3 col-md-6">
        <div class="today-card">
            <div class="today-icon bg-info">
                <i class="fas fa-user-plus"></i>
            </div>

            <div class="today-info">
                <h3><?php echo $newCustomers['total']; ?></h3>
                <p>New Customers Today</p>
            </div>
        </div>
    </div>

    <!-- Next Appointment -->
<div class="col-lg-3 col-md-6">
    <div class="today-card">
        <div class="today-icon bg-warning">
            <i class="fas fa-clock"></i>
        </div>
    <div class="today-info">
     <?php if($nextAppointment){ ?>
        <h6><?php echo $nextAppointment['customer_name']; ?></h6>

    <small>
        <?php echo date("g:i A",strtotime($nextAppointment['appointment_date'])); ?>
    </small>

<?php }else{ ?>
   <h6>No Appointment</h6>
       <small>Today</small>
<?php } ?>

</div>
</div>
</div>
</div>

<div class="row">
<div class="row g-4">

<!-- Customers -->
<div class="col-lg-4 col-md-6">
    <div class="card dashboard-card bg-customers">
        <div class="card-body">

            <div class="icon-circle">
                <i class="fas fa-users"></i>
            </div>

            <h6>Customers</h6>

            <h2><?php echo $customers['total']; ?></h2>

            <div class="card-footer-link">
                <a href="../customers/index.php">View Details</a>
                <i class="fas fa-arrow-right"></i>
            </div>

        </div>
    </div>
</div>

<!-- Staff -->
<div class="col-lg-4 col-md-6">
    <div class="card dashboard-card bg-staff">
        <div class="card-body">

            <div class="icon-circle">
                <i class="fas fa-user-tie"></i>
            </div>

            <h6>Staff</h6>

            <h2><?php echo $staff['total']; ?></h2>

            <div class="card-footer-link">
                <a href="../staff/index.php">View Details</a>
                <i class="fas fa-arrow-right"></i>
            </div>

        </div>
    </div>
</div>

<!-- Services -->
<div class="col-lg-4 col-md-6">
    <div class="card dashboard-card bg-services">
        <div class="card-body">

            <div class="icon-circle">
                <i class="fas fa-cut"></i>
            </div>

            <h6>Services</h6>

            <h2><?php echo $services['total']; ?></h2>

            <div class="card-footer-link">
                <a href="../services/index.php">View Details</a>
                <i class="fas fa-arrow-right"></i>
            </div>

        </div>
    </div>
</div>

<!-- Appointments -->
<div class="col-lg-4 col-md-6">
    <div class="card dashboard-card bg-appointments">
        <div class="card-body">

            <div class="icon-circle">
                <i class="fas fa-calendar-check"></i>
            </div>

            <h6>Appointments</h6>

            <h2><?php echo $appointments['total']; ?></h2>

            <div class="card-footer-link">
                <a href="../appointments/index.php">View Details</a>
                <i class="fas fa-arrow-right"></i>
            </div>

        </div>
    </div>
</div>

<!-- Payments -->
<div class="col-lg-4 col-md-6">
    <div class="card dashboard-card bg-payments">
        <div class="card-body">

            <div class="icon-circle">
                <i class="fas fa-money-bill-wave"></i>
            </div>

            <h6>Payments</h6>

            <h2><?php echo $payments['total']; ?></h2>

            <div class="card-footer-link">
                <a href="../payments/index.php">View Details</a>
                <i class="fas fa-arrow-right"></i>
            </div>

        </div>
    </div>
</div>

<!-- Revenue -->
<div class="col-lg-4 col-md-6">
    <div class="card dashboard-card bg-revenue">
        <div class="card-body">

            <div class="icon-circle">
                <i class="fas fa-coins"></i>
            </div>

            <h6>Revenue</h6>

            <h2>GHS <?php echo number_format($totalRevenue,2); ?></h2>

            <div class="card-footer-link">
                <a href="../payments/index.php">View Details</a>
                <i class="fas fa-arrow-right"></i>
            </div>

        </div>
    </div>
</div>

</div>

</div>

</div>

      <!-- QUICK ACTIONS -->
<div class="card quick-card mt-4">

<div class="card-body">

<div class="d-flex justify-content-between align-items-center mb-4">

    <h4 class="mb-0">Quick Actions</h4>

</div>

<div class="row g-3">

    <div class="col-lg-3 col-md-6">

        <a href="../customers/create.php" class="quick-action action-blue">

            <i class="fas fa-user-plus"></i>

            <span>Add Customer</span>

        </a>

    </div>

    <div class="col-lg-3 col-md-6">

        <a href="../appointments/create.php" class="quick-action action-orange">

            <i class="fas fa-calendar-plus"></i>

            <span>Book Appointment</span>

        </a>

    </div>

    <div class="col-lg-3 col-md-6">

        <a href="../payments/create.php" class="quick-action action-green">

            <i class="fas fa-money-check-alt"></i>

            <span>Record Payment</span>

        </a>

    </div>

    <div class="col-lg-3 col-md-6">

        <a href="../services/create.php" class="quick-action action-cyan">

            <i class="fas fa-cut"></i>

            <span>Add Service</span>

        </a>

    </div>

</div>

</div>

</div>


     <!-- RECENT ACTIVITY -->

<div class="row mt-4">

<!-- Recent Appointments -->
<div class="col-lg-7 mb-4">

<div class="card dashboard-table-card">

<div class="card-header d-flex justify-content-between align-items-center">

<h5 class="mb-0">
Recent Appointments
</h5>

<a href="../appointments/index.php">View All</a>

</div>

<div class="card-body p-0">

<div class="table-responsive">

<table class="table dashboard-table table-hover mb-0">

<thead>

    <tr>

        <th>Customer</th>
        <th>Staff</th>
        <th>Service</th>
        <th>Date</th>
        <th>Status</th>

    </tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($recentAppointments)){ ?>

<tr>

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

<!-- Recent Payments -->

<div class="col-lg-5 mb-4">

<div class="card dashboard-table-card">

<div class="card-header d-flex justify-content-between align-items-center">

<h5 class="mb-0">
    Recent Payments
</h5>

<a href="../payments/index.php">View All</a>

</div>

<div class="card-body p-0">

<div class="table-responsive">

<table class="table dashboard-table table-hover mb-0">

        <thead>

            <tr>

                <th>Customer</th>
                <th>Amount</th>
                <th>Method</th>

            </tr>

        </thead>

        <tbody>

        <?php while($pay=mysqli_fetch_assoc($recentPayments)){ ?>

            <tr>

                <td><?php echo $pay['customer_name']; ?></td>

                <td>
                    GHS <?php echo number_format($pay['amount'],2); ?>
                </td>

                <td><?php echo $pay['payment_method']; ?></td>

            </tr>

        <?php } ?>

        </tbody>

    </table>

</div>

</div>

</div>

</div>

</div>


        <!-- DASHBOARD CHARTS -->

<div class="row mt-4">

    <!-- Revenue Chart -->
    <div class="col-lg-6 mb-4">

        <div class="card dashboard-table-card">

            <div class="card-header">
                <h5 class="mb-0">Revenue Overview</h5>
            </div>

            <div class="card-body">

                <canvas id="revenueChart" height="120"></canvas>

            </div>

        </div>

    </div>

    <!-- Appointment Chart -->
    <div class="col-lg-6 mb-4">

        <div class="card dashboard-table-card">

            <div class="card-header">
                <h5 class="mb-0">Appointments Overview</h5>
            </div>

            <div class="card-body">

                <canvas id="appointmentChart" height="120"></canvas>

            </div>

        </div>

    </div>

</div>

<!-- ==========================
        RECENT ACTIVITY
========================== -->

<div class="row mt-5">

<div class="col-12">

<div class="card dashboard-table-card">

<div class="card-header d-flex justify-content-between align-items-center">

<h5 class="mb-0">
<i class="fas fa-history text-primary"></i>
Recent Activity
</h5>

</div>

<div class="card-body p-0">

<ul class="list-group list-group-flush">

<?php while($activity = mysqli_fetch_assoc($activities)){ ?>

<li class="list-group-item activity-item">

<div class="activity-icon">

    <?php
    if($activity['activity_type']=="customer"){
        echo '<i class="fas fa-user-plus text-primary"></i>';
    }
    elseif($activity['activity_type']=="appointment"){
        echo '<i class="fas fa-calendar-check text-warning"></i>';
    }
    elseif($activity['activity_type']=="payment"){
        echo '<i class="fas fa-money-bill-wave text-success"></i>';
    }
    elseif($activity['activity_type']=="service"){
        echo '<i class="fas fa-cut text-info"></i>';
    }
    ?>

</div>

<div class="activity-content">

    <strong><?php echo $activity['activity_name']; ?></strong>

    <small class="text-muted d-block">

        <?php echo date("d M Y, h:i A", strtotime($activity['created_at'])); ?>

    </small>

</div>

</li>

<?php } ?>

</ul>

</div>

</div>

</div>

</div>


<!-- REPORTS -->

<div class="card reports-card mt-4">

<div class="card-header">
<h4 class="mb-0">Reports</h4>
</div>

<div class="card-body">

<div class="row g-4">

<div class="col-lg-4 col-md-6">

<a href="../reports/appointment_report.php" class="report-item">

<div class="report-icon bg-primary">
    <i class="fas fa-calendar-check"></i>
</div>

<div class="report-text">
    <h6>Appointment Report</h6>
    <p>View all appointment records</p>
</div>

<i class="fas fa-chevron-right report-arrow"></i>

</a>

</div>

<div class="col-lg-4 col-md-6">

<a href="../reports/revenue_report.php" class="report-item">

<div class="report-icon bg-success">
    <i class="fas fa-coins"></i>
</div>

<div class="report-text">
    <h6>Revenue Report</h6>
    <p>View payment summaries</p>
</div>

<i class="fas fa-chevron-right report-arrow"></i>

</a>

</div>

<div class="col-lg-4 col-md-6">

<a href="../reports/customer_report.php" class="report-item">

<div class="report-icon bg-info">
    <i class="fas fa-users"></i>
</div>

<div class="report-text">
    <h6>Customer Report</h6>
    <p>View customer records</p>
</div>

<i class="fas fa-chevron-right report-arrow"></i>

</a>

</div>

<div class="col-lg-4 col-md-6">

<a href="../reports/staff_report.php" class="report-item">

<div class="report-icon bg-warning">
    <i class="fas fa-user-tie"></i>
</div>

<div class="report-text">
    <h6>Staff Report</h6>
    <p>View staff information</p>
</div>

<i class="fas fa-chevron-right report-arrow"></i>

</a>

</div>

<div class="col-lg-4 col-md-6">

<a href="../reports/service_report.php" class="report-item">

<div class="report-icon bg-danger">
    <i class="fas fa-cut"></i>
</div>

<div class="report-text">
    <h6>Services Report</h6>
    <p>View salon services</p>
</div>

<i class="fas fa-chevron-right report-arrow"></i>

</a>

</div>

</div>

</div>

</div>



<script>

const revenueData = <?php echo json_encode($revenueData); ?>;

const appointmentData = <?php echo json_encode($appointmentData); ?>;

</script>

<?php include("../includes/footer_content.php"); ?>
<?php include("../includes/footer.php"); ?>