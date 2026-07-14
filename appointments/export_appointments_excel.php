<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    exit();
}

include("../config/database.php");

$search = $_GET['search'] ?? '';
$from   = $_GET['from'] ?? '';
$to     = $_GET['to'] ?? '';

$sql = "
SELECT
customers.customer_name,
staff.full_name,
services.service_name,
appointments.appointment_date,
appointments.status
FROM appointments
INNER JOIN customers ON appointments.customer_id = customers.id
INNER JOIN staff ON appointments.staff_id = staff.id
INNER JOIN services ON appointments.service_id = services.id
WHERE 1=1
";

if($search != ""){
    $search = mysqli_real_escape_string($conn,$search);

    $sql .= " AND (
        customers.customer_name LIKE '%$search%'
        OR staff.full_name LIKE '%$search%'
        OR services.service_name LIKE '%$search%'
    )";
}

if($from != ""){
    $sql .= " AND DATE(appointments.appointment_date) >= '$from'";
}

if($to != ""){
    $sql .= " AND DATE(appointments.appointment_date) <= '$to'";
}

$sql .= " ORDER BY appointments.appointment_date DESC";

$result = mysqli_query($conn,$sql);

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Appointment_Report.xls");

echo "<table border='1'>";

echo "<tr>
<th>Customer</th>
<th>Staff</th>
<th>Service</th>
<th>Appointment Date</th>
<th>Status</th>
</tr>";

while($row=mysqli_fetch_assoc($result)){

    echo "<tr>";

    echo "<td>".$row['customer_name']."</td>";
    echo "<td>".$row['full_name']."</td>";
    echo "<td>".$row['service_name']."</td>";
    echo "<td>".$row['appointment_date']."</td>";
    echo "<td>".$row['status']."</td>";

    echo "</tr>";

}

echo "</table>";
?>