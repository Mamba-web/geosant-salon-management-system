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
header("Content-Disposition: attachment; filename=Service_Report.xls");

$sql="SELECT * FROM services WHERE 1=1";

if($search!=""){
    $search=mysqli_real_escape_string($conn,$search);
    $sql.=" AND service_name LIKE '%$search%'";
}

if($from!=""){
    $sql.=" AND DATE(created_at)>='$from'";
}

if($to!=""){
    $sql.=" AND DATE(created_at)<='$to'";
}

$sql.=" ORDER BY created_at DESC";

$result=mysqli_query($conn,$sql);

echo "ID\tService\tPrice\tDate Added\n";

while($row=mysqli_fetch_assoc($result)){

echo $row['id']."\t";
echo $row['service_name']."\t";
echo $row['price']."\t";
echo date("d M Y",strtotime($row['created_at']))."\n";

}
?>