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

// GET - Ambil semua transaksi
if ($method === 'GET') {
    $stmt = $pdo->query("
        SELECT t.*, p.nama as nama_pelanggan, l.nama_layanan
        FROM id_transaksi t
        JOIN id_pelanggan p ON t.id_pelanggan = p.id_pelanggan
        JOIN id_layanan l ON t.id_layanan = l.id_layanan
        ORDER BY t.id_transaksi DESC
    ");
    $transaksi = $stmt->fetchAll();
    
    echo json_encode(['success' => true, 'data' => $transaksi]);
}

// POST - Tambah transaksi
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id_pelanggan']) || !isset($data['id_layanan']) || !isset($data['berat'])) {
        echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
        exit;
    }
    
    $stmt = $pdo->prepare("
        INSERT INTO id_transaksi (id_pelanggan, id_layanan, tanggal_masuk, tanggal_selesai, berat, total_harga, status)
        VALUES (?, ?, ?, ?, ?, ?, 'Proses')
    ");
    $stmt->execute([
        $data['id_pelanggan'],
        $data['id_layanan'],
        $data['tanggal_masuk'],
        $data['tanggal_selesai'] ?: null,
        $data['berat'],
        $data['total_harga']
    ]);
    
    echo json_encode(['success' => true, 'message' => 'Transaksi berhasil ditambahkan']);
}

// PUT - Update status
elseif ($method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id_transaksi']) || !isset($data['status'])) {
        echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
        exit;
    }
    
    $stmt = $pdo->prepare("UPDATE id_transaksi SET status = ? WHERE id_transaksi = ?");
    $stmt->execute([$data['status'], $data['id_transaksi']]);
    
    echo json_encode(['success' => true, 'message' => 'Status berhasil diupdate']);
}

// DELETE - Hapus transaksi
elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id_transaksi'])) {
        echo json_encode(['success' => false, 'message' => 'ID transaksi tidak valid']);
        exit;
    }
    
    $stmt = $pdo->prepare("DELETE FROM id_transaksi WHERE id_transaksi = ?");
    $stmt->execute([$data['id_transaksi']]);
    
    echo json_encode(['success' => true, 'message' => 'Transaksi berhasil dihapus']);
}
?>
