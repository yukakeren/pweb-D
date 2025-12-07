<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

// Cek autentikasi
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Total pelanggan
$stmt = $pdo->query("SELECT COUNT(*) as total FROM id_pelanggan");
$totalPelanggan = $stmt->fetch()['total'];

// Transaksi hari ini
$stmt = $pdo->query("SELECT COUNT(*) as total FROM id_transaksi WHERE DATE(tanggal_masuk) = CURDATE()");
$transaksiHariIni = $stmt->fetch()['total'];

// Sedang proses
$stmt = $pdo->query("SELECT COUNT(*) as total FROM id_transaksi WHERE status = 'Proses'");
$sedangProses = $stmt->fetch()['total'];

// Pendapatan hari ini
$stmt = $pdo->query("SELECT COALESCE(SUM(total_harga), 0) as total FROM id_transaksi WHERE DATE(tanggal_masuk) = CURDATE()");
$pendapatanHariIni = $stmt->fetch()['total'];

// Grafik pendapatan 7 hari terakhir
$stmt = $pdo->query("
    SELECT DATE(tanggal_masuk) as tanggal, COALESCE(SUM(total_harga), 0) as total
    FROM id_transaksi
    WHERE tanggal_masuk >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY DATE(tanggal_masuk)
    ORDER BY tanggal
");
$grafikData = $stmt->fetchAll();

$grafikLabels = [];
$grafikValues = [];
foreach ($grafikData as $row) {
    $grafikLabels[] = date('d M', strtotime($row['tanggal']));
    $grafikValues[] = $row['total'];
}

// Transaksi terbaru (5 terakhir)
$stmt = $pdo->query("
    SELECT t.*, p.nama as nama_pelanggan, l.nama_layanan
    FROM id_transaksi t
    JOIN id_pelanggan p ON t.id_pelanggan = p.id_pelanggan
    JOIN id_layanan l ON t.id_layanan = l.id_layanan
    ORDER BY t.tanggal_masuk DESC
    LIMIT 5
");
$transaksiTerbaru = $stmt->fetchAll();

echo json_encode([
    'success' => true,
    'totalPelanggan' => $totalPelanggan,
    'transaksiHariIni' => $transaksiHariIni,
    'sedangProses' => $sedangProses,
    'pendapatanHariIni' => $pendapatanHariIni,
    'grafikLabels' => $grafikLabels,
    'grafikData' => $grafikValues,
    'transaksiTerbaru' => $transaksiTerbaru
]);
?>
