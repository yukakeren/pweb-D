<?php
session_start();
require_once '../config/database.php';

// Cek autentikasi
if (!isset($_SESSION['user_id'])) {
    die('Unauthorized');
}

$dari = $_GET['dari'] ?? null;
$sampai = $_GET['sampai'] ?? null;

if (!$dari || !$sampai) {
    die('Parameter tidak lengkap');
}

// Query data
$stmt = $pdo->prepare("
    SELECT t.*, p.nama as nama_pelanggan, l.nama_layanan
    FROM id_transaksi t
    JOIN id_pelanggan p ON t.id_pelanggan = p.id_pelanggan
    JOIN id_layanan l ON t.id_layanan = l.id_layanan
    WHERE DATE(t.tanggal_masuk) BETWEEN ? AND ?
    ORDER BY t.tanggal_masuk DESC
");
$stmt->execute([$dari, $sampai]);
$data = $stmt->fetchAll();

// Set header untuk download CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=laporan_laundry_' . $dari . '_' . $sampai . '.csv');

// Output CSV
$output = fopen('php://output', 'w');

// Header kolom
fputcsv($output, ['ID Transaksi', 'Tanggal Masuk', 'Tanggal Selesai', 'Pelanggan', 'Layanan', 'Berat (Kg)', 'Total Harga', 'Status']);

// Data
foreach ($data as $row) {
    fputcsv($output, [
        $row['id_transaksi'],
        $row['tanggal_masuk'],
        $row['tanggal_selesai'],
        $row['nama_pelanggan'],
        $row['nama_layanan'],
        $row['berat'],
        $row['total_harga'],
        $row['status']
    ]);
}

fclose($output);
?>
