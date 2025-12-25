<?php
session_start();
include '../config/db.php';
require('../lib/fpdf/fpdf.php');

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['student_id'];

/* Fetch student info */
$student = $conn->query(
    "SELECT name, roll_no, class, email 
     FROM students 
     WHERE student_id = $id"
)->fetch_assoc();

/* Fetch marks */
$result = $conn->query(
    "SELECT subjects.subject_name, marks.marks
     FROM marks
     JOIN subjects ON marks.subject_id = subjects.subject_id
     WHERE marks.student_id = $id"
);

$pdf = new FPDF();
$pdf->AddPage();

/* Title */
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Student Result',0,1,'C');

/* Student Info */
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,8,"Name: {$student['name']}",0,1);
$pdf->Cell(0,8,"Roll No: {$student['roll_no']}",0,1);
$pdf->Cell(0,8,"Class: {$student['class']}",0,1);
$pdf->Cell(0,8,"Email: {$student['email']}",0,1);

$pdf->Ln(5);

/* Table Header */
$pdf->SetFont('Arial','B',12);
$pdf->Cell(100,8,'Subject',1);
$pdf->Cell(40,8,'Marks',1);
$pdf->Ln();

/* Table Data */
$total = 0;
$count = 0;

$pdf->SetFont('Arial','',12);
while ($row = $result->fetch_assoc()) {
    $pdf->Cell(100,8,$row['subject_name'],1);
    $pdf->Cell(40,8,$row['marks'],1);
    $pdf->Ln();

    $total += $row['marks'];
    $count++;
}

$percentage = ($count > 0) ? $total / $count : 0;

/* Result Summary */
$pdf->Ln(5);
$pdf->Cell(0,8,"Total Marks: $total",0,1);
$pdf->Cell(0,8,"Percentage: ".number_format($percentage,2)."%",0,1);

$pdf->Output();
