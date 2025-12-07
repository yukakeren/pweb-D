<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

// Cek autentikasi
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

// GET /api/laporan?periode=bulan - Laporan keuangan bulanan
if ($method === 'GET' && isset($_GET['periode'])) {
    $periode = $_GET['periode'];
    $bulan = $_GET['bulan'] ?? date('Y-m');
    
    if ($periode === 'bulan') {
        $stmt = $pdo->prepare("
            SELECT 
                DATE(tanggal_masuk) as tanggal,
                COUNT(*) as jumlah_transaksi,
                SUM(total_harga) as total_pendapatan
            FROM id_transaksi
            WHERE DATE_FORMAT(tanggal_masuk, '%Y-%m') = ?
            GROUP BY DATE(tanggal_masuk)
            ORDER BY tanggal
        ");
        $stmt->execute([$bulan]);
        $data = $stmt->fetchAll();
        
        echo json_encode(['success' => true, 'data' => $data]);
    }
}

// GET /api/laporan/transaksi?id={id} - Detail transaksi
elseif ($method === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $stmt = $pdo->prepare("
        SELECT t.*, p.nama as nama_pelanggan, p.alamat, p.no_hp, l.nama_layanan, l.harga_per_kg
        FROM id_transaksi t
        JOIN id_pelanggan p ON t.id_pelanggan = p.id_pelanggan
        JOIN id_layanan l ON t.id_layanan = l.id_layanan
        WHERE t.id_transaksi = ?
    ");
    $stmt->execute([$id]);
    $transaksi = $stmt->fetch();
    
    if ($transaksi) {
        echo json_encode(['success' => true, 'data' => $transaksi]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Transaksi tidak ditemukan']);
    }
}

// GET /api/laporan/nota?id={id} - Cetak nota
elseif ($method === 'GET' && isset($_GET['nota'])) {
    $id = $_GET['nota'];
    
    $stmt = $pdo->prepare("
        SELECT t.*, p.nama as nama_pelanggan, p.alamat, p.no_hp, l.nama_layanan, l.harga_per_kg
        FROM id_transaksi t
        JOIN id_pelanggan p ON t.id_pelanggan = p.id_pelanggan
        JOIN id_layanan l ON t.id_layanan = l.id_layanan
        WHERE t.id_transaksi = ?
    ");
    $stmt->execute([$id]);
    $transaksi = $stmt->fetch();
    
    if ($transaksi) {
        // Return HTML untuk print
        header('Content-Type: text/html; charset=utf-8');
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Nota Transaksi #<?= $transaksi['id_transaksi'] ?></title>
            <style>
                body { font-family: Arial, sans-serif; max-width: 400px; margin: 20px auto; }
                .header { text-align: center; margin-bottom: 20px; }
                .nota-info { margin: 10px 0; }
                .line { border-top: 1px dashed #000; margin: 10px 0; }
                table { width: 100%; }
                .total { font-weight: bold; font-size: 1.2em; }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>LAUNDRYCRAFTY</h2>
                <p>Jl. Contoh No. 123<br>Telp: 0812-3456-7890</p>
            </div>
            <div class="line"></div>
            <div class="nota-info">
                <table>
                    <tr><td>No. Transaksi</td><td>: <?= $transaksi['id_transaksi'] ?></td></tr>
                    <tr><td>Tanggal</td><td>: <?= date('d/m/Y', strtotime($transaksi['tanggal_masuk'])) ?></td></tr>
                    <tr><td>Pelanggan</td><td>: <?= $transaksi['nama_pelanggan'] ?></td></tr>
                    <tr><td>No. HP</td><td>: <?= $transaksi['no_hp'] ?></td></tr>
                </table>
            </div>
            <div class="line"></div>
            <div class="nota-info">
                <table>
                    <tr><td>Layanan</td><td>: <?= $transaksi['nama_layanan'] ?></td></tr>
                    <tr><td>Berat</td><td>: <?= $transaksi['berat'] ?> Kg</td></tr>
                    <tr><td>Harga/Kg</td><td>: Rp <?= number_format($transaksi['harga_per_kg'], 0, ',', '.') ?></td></tr>
                </table>
            </div>
            <div class="line"></div>
            <div class="nota-info total">
                <table>
                    <tr><td>TOTAL</td><td>: Rp <?= number_format($transaksi['total_harga'], 0, ',', '.') ?></td></tr>
                </table>
            </div>
            <div class="line"></div>
            <div style="text-align: center; margin-top: 20px;">
                <p>Terima Kasih<br>Cucian akan selesai pada:<br><?= $transaksi['tanggal_selesai'] ? date('d/m/Y', strtotime($transaksi['tanggal_selesai'])) : '-' ?></p>
            </div>
            <script>window.print();</script>
        </body>
        </html>
        <?php
    } else {
        echo json_encode(['success' => false, 'message' => 'Transaksi tidak ditemukan']);
    }
}
?>
