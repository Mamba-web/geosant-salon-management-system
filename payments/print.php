<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

$result=mysqli_query($conn,"
SELECT
payments.id,
customers.customer_name,
services.service_name,
payments.amount,
payments.payment_method,
payments.payment_date
FROM payments
INNER JOIN appointments ON payments.appointment_id=appointments.id
INNER JOIN customers ON appointments.customer_id=customers.id
INNER JOIN services ON appointments.service_id=services.id
ORDER BY payments.id DESC
");
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>Payment Report</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
padding:30px;
font-family:Arial,sans-serif;
}

@media print{

button{
display:none;
}

}

</style>

</head>

<body>

<button onclick="window.print()" class="btn btn-primary mb-3">
Print Report
</button>

<h2 class="text-center">GeoSant Salon</h2>

<p class="text-center">Payment Report</p>

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

<script>

window.onload=function(){

window.print();

}

</script>

</body>

</html>