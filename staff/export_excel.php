<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=staff.xls");

$result = mysqli_query($conn,"SELECT * FROM staff ORDER BY full_name ASC");

echo "ID\tFull Name\tGender\tPhone\tRole\tDate Added\n";

while($row=mysqli_fetch_assoc($result)){

    echo $row['id']."\t";
    echo $row['full_name']."\t";
    echo $row['gender']."\t";
    echo $row['phone']."\t";
    echo $row['role']."\t";
    echo $row['created_at']."\n";

}
?>