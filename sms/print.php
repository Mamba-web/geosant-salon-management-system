<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

$sql = "SELECT * FROM sms_logs ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>

<html>

<head>

<title>SMS History</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    padding:30px;
}

h2{
    text-align:center;
    margin-bottom:20px;
}

</style>

</head>

<body onload="window.print()">

<h2>GeoSant Unisex Salon</h2>

<h5 class="text-center mb-4">

SMS History Report

</h5>

<table class="table table-bordered">

<thead>

<tr>

<th>ID</th>
<th>Customer</th>
<th>Phone</th>
<th>Service</th>
<th>Amount</th>
<th>Status</th>
<th>Date</th>

</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['customer_name']; ?></td>

<td><?php echo $row['phone']; ?></td>

<td><?php echo $row['service_name']; ?></td>

<td>GHS <?php echo number_format($row['amount'],2); ?></td>

<td><?php echo $row['status']; ?></td>

<td><?php echo date("d M Y",strtotime($row['created_at'])); ?></td>

</tr>

<?php } ?>

</tbody>

</table>

</body>

</html>