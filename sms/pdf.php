<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");
require("../fpdf/fpdf.php");

$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(0, 10, 'GeoSant Unisex Salon', 0, 1, 'C');

$pdf->SetFont('Arial', '', 14);
$pdf->Cell(0, 8, 'SMS History Report', 0, 1, 'C');

$pdf->Ln(5);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(100, 7, 'Printed By: ' . $_SESSION['full_name'], 0, 0);

$pdf->Cell(0, 7, 'Date: ' . date('d F Y h:i A'), 0, 1, 'R');

$pdf->Ln(5);

// Table Header
$pdf->SetFont('Arial', 'B', 10);

$pdf->Cell(12,10,'ID',1);
$pdf->Cell(45,10,'Customer',1);
$pdf->Cell(35,10,'Phone',1);
$pdf->Cell(45,10,'Service',1);
$pdf->Cell(25,10,'Amount',1);
$pdf->Cell(35,10,'Status',1);
$pdf->Cell(70,10,'Date Sent',1);

$pdf->Ln();

// Fetch records
$sql = "SELECT * FROM sms_logs ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

$pdf->SetFont('Arial','',9);

while($row = mysqli_fetch_assoc($result)){

    $pdf->Cell(12,8,$row['id'],1);
    $pdf->Cell(45,8,$row['customer_name'],1);
    $pdf->Cell(35,8,$row['phone'],1);
    $pdf->Cell(45,8,$row['service_name'],1);
    $pdf->Cell(25,8,'GHS '.number_format($row['amount'],2),1);
    $pdf->Cell(35,8,$row['status'],1);
    $pdf->Cell(70,8,date("d M Y h:i A",strtotime($row['created_at'])),1);

    $pdf->Ln();
}

$pdf->Ln(8);

// Total Records
$total = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM sms_logs"));

$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,8,'Total SMS Records: '.$total,0,1);

$pdf->Output('I','SMS_History_Report.pdf');