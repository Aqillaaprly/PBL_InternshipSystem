<?php
session_start();
include('../db.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'company') {
    header('Location: ../');
    exit;
}

$pendaftar_id = $_POST['pendaftar_id'] ?? 0;
$aksi = $_POST['aksi'] ?? '';

if ($pendaftar_id && in_array($aksi, ['diterima', 'ditolak'])) {
    // Update status pendaftar
    $stmt = $conn->prepare("UPDATE pendaftar SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $aksi, $pendaftar_id);
    $stmt->execute();
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
