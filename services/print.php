<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

$result = mysqli_query($conn,"
SELECT *
FROM services
ORDER BY service_name ASC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<title>Service Report</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    padding:30px;
    font-family:Arial,sans-serif;
}
h2,p{
    text-align:center;
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

<p>Service Report</p>

<table class="table table-bordered">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Service Name</th>
<th>Price (GHS)</th>
<th>Date Added</th>

</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['service_name']; ?></td>

<td>GHS <?php echo number_format($row['price'],2); ?></td>

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