<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=services.xls");

$result = mysqli_query($conn,"SELECT * FROM services ORDER BY service_name ASC");

echo "ID\tService Name\tPrice\tDate Added\n";

while($row=mysqli_fetch_assoc($result)){

    echo $row['id']."\t";
    echo $row['service_name']."\t";
    echo $row['price']."\t";
    echo $row['created_at']."\n";

}
?>