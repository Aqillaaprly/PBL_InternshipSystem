<?php
session_start();
include('../db.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'company') {
    header('Location: ../');
    exit;
}

$company_id = $_SESSION['user']['id'];
$posisi = $_POST['posisi'] ?? '';
$deskripsi = $_POST['deskripsi'] ?? '';
$tanggal_dibuka = $_POST['tanggal_dibuka'] ?? '';

if ($posisi && $deskripsi && $tanggal_dibuka) {
    $stmt = $conn->prepare("INSERT INTO lowongan (company_id, posisi, deskripsi, tanggal_dibuka) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $company_id, $posisi, $deskripsi, $tanggal_dibuka);
    $stmt->execute();
}

header('Location: dashboard.php');
exit;
