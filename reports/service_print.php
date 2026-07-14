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

$sql = "SELECT * FROM services WHERE 1=1";

if($search!=""){
    $search=mysqli_real_escape_string($conn,$search);
    $sql.=" AND service_name LIKE '%$search%'";
}

if($from!=""){
    $sql.=" AND DATE(created_at)>='$from'";
}

if($to!=""){
    $sql.=" AND DATE(created_at)<='$to'";
}

$sql.=" ORDER BY created_at DESC";

$result=mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>Service Report</title>

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

<p class="text-center">Service Report</p>

<table class="table table-bordered">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Service</th>
<th>Price</th>
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

</body>
</html>