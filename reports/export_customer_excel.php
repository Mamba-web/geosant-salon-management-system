<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Customer_Report.xls");

$result=mysqli_query($conn,"
SELECT * FROM customers
ORDER BY customer_name ASC
");

echo "ID\tCustomer Name\tPhone\tGender\tDate Added\n";

while($row=mysqli_fetch_assoc($result)){

echo $row['id']."\t";
echo $row['customer_name']."\t";
echo $row['phone']."\t";
echo $row['gender']."\t";
echo date("d M Y",strtotime($row['created_at']))."\n";

}
?>