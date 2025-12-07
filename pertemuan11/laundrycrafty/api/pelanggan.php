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

// GET - Ambil semua pelanggan
if ($method === 'GET') {
    $stmt = $pdo->query("SELECT * FROM id_pelanggan ORDER BY id_pelanggan DESC");
    $pelanggan = $stmt->fetchAll();
    
    echo json_encode(['success' => true, 'data' => $pelanggan]);
}

// POST - Tambah/Edit pelanggan
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['nama'])) {
        echo json_encode(['success' => false, 'message' => 'Nama harus diisi']);
        exit;
    }
    
    if (isset($data['id_pelanggan']) && !empty($data['id_pelanggan'])) {
        // Update
        $stmt = $pdo->prepare("UPDATE id_pelanggan SET nama = ?, alamat = ?, no_hp = ? WHERE id_pelanggan = ?");
        $stmt->execute([$data['nama'], $data['alamat'], $data['no_hp'], $data['id_pelanggan']]);
        
        echo json_encode(['success' => true, 'message' => 'Pelanggan berhasil diupdate']);
    } else {
        // Insert
        $stmt = $pdo->prepare("INSERT INTO id_pelanggan (nama, alamat, no_hp) VALUES (?, ?, ?)");
        $stmt->execute([$data['nama'], $data['alamat'], $data['no_hp']]);
        
        echo json_encode(['success' => true, 'message' => 'Pelanggan berhasil ditambahkan']);
    }
}

// DELETE - Hapus pelanggan
elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id_pelanggan'])) {
        echo json_encode(['success' => false, 'message' => 'ID pelanggan tidak valid']);
        exit;
    }
    
    $stmt = $pdo->prepare("DELETE FROM id_pelanggan WHERE id_pelanggan = ?");
    $stmt->execute([$data['id_pelanggan']]);
    
    echo json_encode(['success' => true, 'message' => 'Pelanggan berhasil dihapus']);
}
?>
