<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Activity_Log_Report.xls");
header("Pragma: no-cache");
header("Expires: 0");

$result = mysqli_query($conn, "
SELECT *
FROM activity_logs
ORDER BY activity_time DESC
");
?>

<table border="1">

<tr style="font-weight:bold; background:#dddddd;">

    <th>ID</th>
    <th>User ID</th>
    <th>Full Name</th>
    <th>Module</th>
    <th>Activity</th>
    <th>Activity Time</th>

</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

    <td><?php echo $row['id']; ?></td>

    <td><?php echo $row['user_id']; ?></td>

    <td><?php echo htmlspecialchars($row['full_name']); ?></td>

    <td><?php echo htmlspecialchars($row['module']); ?></td>

    <td><?php echo htmlspecialchars($row['activity']); ?></td>

    <td><?php echo date("d M Y h:i A", strtotime($row['activity_time'])); ?></td>

</tr>

<?php } ?>

</table>