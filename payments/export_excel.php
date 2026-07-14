<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=payments.xls");

$result=mysqli_query($conn,"
SELECT
payments.id,
customers.customer_name,
services.service_name,
payments.amount,
payments.payment_method,
payments.payment_date
FROM payments
INNER JOIN appointments ON payments.appointment_id=appointments.id
INNER JOIN customers ON appointments.customer_id=customers.id
INNER JOIN services ON appointments.service_id=services.id
ORDER BY payments.id DESC
");

echo "ID\tCustomer\tService\tAmount\tMethod\tPayment Date\n";

while($row=mysqli_fetch_assoc($result)){

echo $row['id']."\t";
echo $row['customer_name']."\t";
echo $row['service_name']."\t";
echo $row['amount']."\t";
echo $row['payment_method']."\t";
echo $row['payment_date']."\n";

}
?>