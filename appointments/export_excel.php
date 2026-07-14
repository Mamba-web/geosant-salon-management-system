<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=staff.xls");

$result = mysqli_query($conn,"SELECT * FROM appiontments ORDER BY customer_name ASC");

echo "ID\tCustomer Name\tStaff\tService\tDate Added\tTime\tStatus\n";

while($row=mysqli_fetch_assoc($result)){

    echo $row['id']."\t";
    echo $row['customer_id']."\t";
    echo $row['staff_id']."\t";
    echo $row['service_id']."\t";
    echo $row['created_at']."\n";
    echo $row['time']."\t";
    echo $row['status']."\t";

}
?>