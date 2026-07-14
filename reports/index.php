<?php
require_once '../auth/check_auth.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../dashboard/index.php");
    exit();
}

include("../config/database.php");;
include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/navbar.php");
?>

<div class="main-content">

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>
<h2><i class="fas fa-chart-bar text-primary"></i> Reports Dashboard</h2>
<p class="text-muted mb-0">
View, print and export salon reports.
</p>
</div>

</div>

<div class="row g-4">

    <!-- Customer Report -->
    <div class="col-lg-4 col-md-6">

        <div class="reports-card card h-100">

            <div class="card-body text-center">

                <div class="report-icon bg-primary mx-auto mb-3">
                    <i class="fas fa-users"></i>
                </div>

                <h5>Customer Report</h5>

                <p class="text-muted">
                    View all registered customers.
                </p>

                <a href="customer_report.php" class="btn btn-primary">
                    <i class="fas fa-folder-open"></i>
                    Open Report
                </a>

            </div>

        </div>

    </div>

    <!-- Staff Report -->
    <div class="col-lg-4 col-md-6">

        <div class="reports-card card h-100">

            <div class="card-body text-center">

                <div class="report-icon bg-success mx-auto mb-3">
                    <i class="fas fa-user-tie"></i>
                </div>

                <h5>Staff Report</h5>

                <p class="text-muted">
                    View all staff records.
                </p>

                <a href="staff_report.php" class="btn btn-success">
                    <i class="fas fa-folder-open"></i>
                    Open Report
                </a>

            </div>

        </div>

    </div>

    <!-- Service Report -->
    <div class="col-lg-4 col-md-6">

        <div class="reports-card card h-100">

            <div class="card-body text-center">

                <div class="report-icon bg-info mx-auto mb-3">
                    <i class="fas fa-cut"></i>
                </div>

                <h5>Service Report</h5>

                <p class="text-muted">
                    View all salon services.
                </p>

                <a href="service_report.php" class="btn btn-info text-white">
                    <i class="fas fa-folder-open"></i>
                    Open Report
                </a>

            </div>

        </div>

    </div>

    <!-- Appointment Report -->
    <div class="col-lg-4 col-md-6">

        <div class="reports-card card h-100">

            <div class="card-body text-center">

                <div class="report-icon bg-warning mx-auto mb-3">
                    <i class="fas fa-calendar-check"></i>
                </div>

                <h5>Appointment Report</h5>

                <p class="text-muted">
                    View appointment history.
                </p>

                <a href="appointment_report.php" class="btn btn-warning text-dark">
                    <i class="fas fa-folder-open"></i>
                    Open Report
                </a>

            </div>

        </div>

    </div>

    <!-- Revenue Report -->
    <div class="col-lg-4 col-md-6">

        <div class="reports-card card h-100">

            <div class="card-body text-center">

                <div class="report-icon mx-auto mb-3"
                     style="background:#6f42c1;">
                    <i class="fas fa-chart-line"></i>
                </div>

                <h5>Revenue Report</h5>

                <p class="text-muted">
                    View salon revenue summary.
                </p>

                <a href="revenue_report.php"
                   class="btn text-white"
                   style="background:#6f42c1;">

                    <i class="fas fa-folder-open"></i>
                    Open Report

                </a>

            </div>

        </div>

    </div>

</div>

</div>
</div>

<?php include("../includes/footer.php"); ?>