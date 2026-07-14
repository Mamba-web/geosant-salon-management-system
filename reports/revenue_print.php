<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

$search = $_GET['search'] ?? '';
$from   = $_GET['from'] ?? '';
$to     = $_GET['to'] ?? '';

$sql = "
SELECT
payments.*,
customers.customer_name,
services.service_name
FROM payments
INNER JOIN appointments ON payments.appointment_id=appointments.id
INNER JOIN customers ON appointments.customer_id=customers.id
INNER JOIN services ON appointments.service_id=services.id
WHERE 1=1
";

if($search!=""){
    $search=mysqli_real_escape_string($conn,$search);
    $sql.=" AND (
        customers.customer_name LIKE '%$search%'
        OR services.service_name LIKE '%$search%'
        OR payments.payment_method LIKE '%$search%'
    )";
}

if($from!=""){
    $sql.=" AND DATE(payments.payment_date)>='$from'";
}

if($to!=""){
    $sql.=" AND DATE(payments.payment_date)<='$to'";
}

$sql.=" ORDER BY payments.payment_date DESC";

$result=mysqli_query($conn,$sql);

$total=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(amount) AS total
FROM payments
"));
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>Revenue Report</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
padding:30px;
font-family:Arial;
}

@media print{
button{
display:none;
}
}

</style>

</head>

<body>

<button onclick="window.print()" class="btn btn-dark mb-3">
Print Report
</button>

<h2 class="text-center">GeoSant Salon</h2>

<p class="text-center">Revenue Report</p>

<h4 class="text-end mb-3">
Total Revenue:
<strong>
GHS <?php echo number_format($total['total'] ?? 0,2); ?>
</strong>
</h4>

<table class="table table-bordered">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Customer</th>
<th>Service</th>
<th>Amount</th>
<th>Method</th>
<th>Date</th>

</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>
<td><?php echo $row['customer_name']; ?></td>
<td><?php echo $row['service_name']; ?></td>
<td>GHS <?php echo number_format($row['amount'],2); ?></td>
<td><?php echo $row['payment_method']; ?></td>
<td><?php echo date("d M Y",strtotime($row['payment_date'])); ?></td>

</tr>

<?php } ?>

</tbody>

</table>

</body>
</html>