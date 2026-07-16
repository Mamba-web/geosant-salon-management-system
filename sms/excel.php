<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

// Excel file name
$filename = "GeoSant_SMS_History_" . date("Y-m-d_H-i-s") . ".xls";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");

// Fetch SMS records
$sql = "SELECT *
        FROM sms_history
        ORDER BY sent_at DESC";

$result = mysqli_query($conn, $sql);

echo "<table border='1'>";

// Report Title
echo "<tr>";
echo "<th colspan='7' style='font-size:18px;background:#0d6efd;color:white;'>
GeoSant Unisex Salon
</th>";
echo "</tr>";

echo "<tr>";
echo "<th colspan='7'>
SMS History Report
</th>";
echo "</tr>";

echo "<tr>";
echo "<th colspan='7'>
Generated on: " . date("d F Y h:i A") . "
</th>";
echo "</tr>";

echo "<tr></tr>";

// Table Header
echo "<tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Phone</th>
        <th>Message</th>
        <th>Status</th>
        <th>Date</th>
        <th>Time</th>
      </tr>";

// Table Data
while ($row = mysqli_fetch_assoc($result)) {

    echo "<tr>";

    echo "<td>".$row['id']."</td>";
    echo "<td>".$row['customer_name']."</td>";
    echo "<td>".$row['phone']."</td>";
    echo "<td>".$row['message']."</td>";
    echo "<td>".$row['status']."</td>";
    echo "<td>".date("d/m/Y", strtotime($row['sent_at']))."</td>";
    echo "<td>".date("h:i A", strtotime($row['sent_at']))."</td>";

    echo "</tr>";
}

echo "</table>";
exit();