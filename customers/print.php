<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

$result = mysqli_query($conn, "
SELECT *
FROM customers
ORDER BY customer_name ASC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<title>Customer Report</title>

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

<p>Customer Report</p>

<table class="table table-bordered">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Name</th>
<th>Phone</th>
<th>Gender</th>
<th>Date Added</th>

</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['customer_name']; ?></td>

<td><?php echo $row['phone']; ?></td>

<td><?php echo $row['gender']; ?></td>

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