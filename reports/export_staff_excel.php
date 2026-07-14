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
header("Content-Disposition: attachment; filename=Staff_Report.xls");

$sql="SELECT * FROM staff WHERE 1=1";

if($search!=""){
    $search=mysqli_real_escape_string($conn,$search);
    $sql.=" AND (
        full_name LIKE '%$search%'
        OR phone LIKE '%$search%'
        OR role LIKE '%$search%'
    )";
}

if($from!=""){
    $sql.=" AND DATE(created_at)>='$from'";
}

if($to!=""){
    $sql.=" AND DATE(created_at)<='$to'";
}

$sql.=" ORDER BY created_at DESC";

$result=mysqli_query($conn,$sql);

echo "ID\tFull Name\tPhone\tGender\tRole\tDate Added\n";

while($row=mysqli_fetch_assoc($result)){

echo $row['id']."\t";
echo $row['full_name']."\t";
echo $row['phone']."\t";
echo $row['gender']."\t";
echo $row['role']."\t";
echo date("d M Y",strtotime($row['created_at']))."\n";

}
?>