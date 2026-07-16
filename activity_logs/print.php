<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

$result = mysqli_query($conn, "
SELECT *
FROM activity_logs
ORDER BY activity_time DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<title>Activity Log Report</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    padding:30px;
    font-family:Arial,sans-serif;
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

th,td{
    font-size:14px;
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

<h2>GeoSant Unisex Salon</h2>

<p>Activity Log Report</p>

<table class="table table-bordered">

<thead class="table-dark">

<tr>

    <th>ID</th>
    <th>Date & Time</th>
    <th>User</th>
    <th>Module</th>
    <th>Activity</th>

</tr>

</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

    <td><?php echo $row['id']; ?></td>

    <td>
        <?php echo date("d M Y h:i A", strtotime($row['activity_time'])); ?>
    </td>

    <td><?php echo htmlspecialchars($row['full_name']); ?></td>

    <td><?php echo htmlspecialchars($row['module']); ?></td>

    <td><?php echo htmlspecialchars($row['activity']); ?></td>

</tr>

<?php } ?>

</tbody>

</table>

<script>
window.onload = function(){
    window.print();
}
</script>

</body>
</html>