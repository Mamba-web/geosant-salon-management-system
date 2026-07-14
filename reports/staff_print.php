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

$sql = "SELECT * FROM staff WHERE 1=1";

if($search!=""){
    $search=mysqli_real_escape_string($conn,$search);
    $sql.=" AND (
        full_name LIKE '%$search%'
        OR phone LIKE '%$search%'
        OR role LIKE '%$search%'
    )";
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
<title>Staff Report</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{padding:30px;}
@media print{button{display:none;}}
</style>

</head>
<body>

<button onclick="window.print()" class="btn btn-dark mb-3">
Print Report
</button>

<h2 class="text-center">GeoSant Salon</h2>
<p class="text-center">Staff Report</p>

<table class="table table-bordered">

<thead class="table-dark">

<tr>
<th>ID</th>
<th>Name</th>
<th>Phone</th>
<th>Gender</th>
<th>Role</th>
<th>Date Added</th>
</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>
<td><?php echo $row['full_name']; ?></td>
<td><?php echo $row['phone']; ?></td>
<td><?php echo $row['gender']; ?></td>
<td><?php echo $row['role']; ?></td>
<td><?php echo date("d M Y",strtotime($row['created_at'])); ?></td>

</tr>

<?php } ?>

</tbody>

</table>

</body>
</html>