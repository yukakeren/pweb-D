<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

// Cek autentikasi
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$dari = $_GET['dari'] ?? null;
$sampai = $_GET['sampai'] ?? null;

if (!$dari || !$sampai) {
    echo json_encode(['success' => false, 'message' => 'Parameter tidak lengkap']);
    exit;
}

// Summary
$stmt = $pdo->prepare("
    SELECT 
        COUNT(*) as total_transaksi,
        COALESCE(SUM(total_harga), 0) as total_pendapatan,
        COALESCE(AVG(total_harga), 0) as rata_rata
    FROM id_transaksi
    WHERE DATE(tanggal_masuk) BETWEEN ? AND ?
");
$stmt->execute([$dari, $sampai]);
$summary = $stmt->fetch();

// Grafik per hari
$stmt = $pdo->prepare("
    SELECT 
        DATE(tanggal_masuk) as tanggal,
        COALESCE(SUM(total_harga), 0) as total
    FROM id_transaksi
    WHERE DATE(tanggal_masuk) BETWEEN ? AND ?
    GROUP BY DATE(tanggal_masuk)
    ORDER BY tanggal
");
$stmt->execute([$dari, $sampai]);
$grafik = $stmt->fetchAll();

// Detail transaksi
$stmt = $pdo->prepare("
    SELECT t.*, p.nama as nama_pelanggan, l.nama_layanan
    FROM id_transaksi t
    JOIN id_pelanggan p ON t.id_pelanggan = p.id_pelanggan
    JOIN id_layanan l ON t.id_layanan = l.id_layanan
    WHERE DATE(t.tanggal_masuk) BETWEEN ? AND ?
    ORDER BY t.tanggal_masuk DESC
");
$stmt->execute([$dari, $sampai]);
$detail = $stmt->fetchAll();

echo json_encode([
    'success' => true,
    'summary' => $summary,
    'grafik' => $grafik,
    'detail' => $detail
]);
?>
