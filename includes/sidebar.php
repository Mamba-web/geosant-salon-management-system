<?php
include("../includes/settings.php");
?>

<div class="sidebar">

    <div class="logo">
    <h3><?php echo $settings['salon_name']; ?></h3>
    </div>

    <ul>

        <li><a href="../dashboard/index.php"><i class="fas fa-home"></i> Dashboard</a></li>

        <li><a href="../customers/index.php"><i class="fas fa-user-friends"></i> Customers</a></li>

        <?php if ($_SESSION['role'] == 'admin') { ?>
        <li><a href="../staff/index.php"><i class="fas fa-user-tie"></i> Staff</a></li>
        <?php } ?>

        <li><a href="../services/index.php"><i class="fas fa-cut"></i> Services</a></li>

        <li><a href="../appointments/index.php"><i class="fas fa-calendar-check"></i> Appointments</a></li>

        <li><a href="../payments/index.php"><i class="fas fa-money-bill-wave"></i> Payments</a></li>

        <?php if ($_SESSION['role'] == 'admin') { ?>
            <li><a href="../reports/index.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
        <?php } ?>

        <?php if ($_SESSION['role'] == 'admin') { ?>
            <li><a href="../users/index.php"><i class="fas fa-users-cog"></i> Users</a></li>
        <?php } ?>
        
        <?php if($_SESSION['role'] == 'admin'){ ?>
            <li><a href="../settings/index.php"><i class="fas fa-cog"></i> Settings</a></li>
        <?php } ?>

        <li><a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>


    </ul>

</div>