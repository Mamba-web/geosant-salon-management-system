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

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Revenue_Report.xls");

$sql = "
SELECT
payments.*,
customers.customer_name,
services.service_name
FROM payments
INNER JOIN appointments ON payments.appointment_id=appointments.id
INNER JOIN customers ON appointments.customer_id=customers.id
INNER JOIN services ON appointments.service_id=services.id
WHERE 1=1
";

if($search!=""){
    $search=mysqli_real_escape_string($conn,$search);
    $sql.=" AND (
        customers.customer_name LIKE '%$search%'
        OR services.service_name LIKE '%$search%'
        OR payments.payment_method LIKE '%$search%'
    )";
}

if($from!=""){
    $sql.=" AND DATE(payments.payment_date)>='$from'";
}

if($to!=""){
    $sql.=" AND DATE(payments.payment_date)<='$to'";
}

$sql.=" ORDER BY payments.payment_date DESC";

$result=mysqli_query($conn,$sql);

echo "ID\tCustomer\tService\tAmount\tMethod\tDate\n";

while($row=mysqli_fetch_assoc($result)){

echo $row['id']."\t";
echo $row['customer_name']."\t";
echo $row['service_name']."\t";
echo $row['amount']."\t";
echo $row['payment_method']."\t";
echo date("d M Y",strtotime($row['payment_date']))."\n";

}
?>