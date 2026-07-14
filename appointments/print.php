<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

$result = mysqli_query($conn, "
SELECT *
FROM appointments
ORDER BY customer_id ASC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<title>Appointment Report</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    padding:30px;
    font-family:Arial, sans-serif;
}

h2{
    text-align:center;
    margin-bottom:5px;
}

p{
    text-align:center;
    margin-bottom:25px;
}

table{
    width:100%;
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

<h2>GeoSant Salon</h2>

<p>Staff Report</p>

<table class="table table-bordered">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Customer Name</th>
<th>Staff Name</th>
<th>Service Name</th>
<th>Date Added</th>
<th>Time</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['customer_id']; ?></td>

<td><?php echo $row['staff_id']; ?></td>

<td><?php echo $row['service_id']; ?></td>

<!-- <td><?php echo $row['date']; ?></td> -->

<!-- <td><?php echo $row['time']; ?></td> -->

<td><?php echo $row['status']; ?></td>

<td><?php echo date("d M Y",strtotime($row['created_at'])); ?></td>

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