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

// GET - Ambil semua layanan
if ($method === 'GET') {
    $stmt = $pdo->query("SELECT * FROM id_layanan ORDER BY id_layanan");
    $layanan = $stmt->fetchAll();
    
    echo json_encode(['success' => true, 'data' => $layanan]);
}

// POST - Tambah/Edit layanan
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['nama_layanan']) || !isset($data['harga_per_kg'])) {
        echo json_encode(['success' => false, 'message' => 'Nama layanan dan harga harus diisi']);
        exit;
    }
    
    if (isset($data['id_layanan']) && !empty($data['id_layanan'])) {
        // Update
        $stmt = $pdo->prepare("UPDATE id_layanan SET nama_layanan = ?, harga_per_kg = ? WHERE id_layanan = ?");
        $stmt->execute([$data['nama_layanan'], $data['harga_per_kg'], $data['id_layanan']]);
        
        echo json_encode(['success' => true, 'message' => 'Layanan berhasil diupdate']);
    } else {
        // Insert
        $stmt = $pdo->prepare("INSERT INTO id_layanan (nama_layanan, harga_per_kg) VALUES (?, ?)");
        $stmt->execute([$data['nama_layanan'], $data['harga_per_kg']]);
        
        echo json_encode(['success' => true, 'message' => 'Layanan berhasil ditambahkan']);
    }
}

// DELETE - Hapus layanan
elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id_layanan'])) {
        echo json_encode(['success' => false, 'message' => 'ID layanan tidak valid']);
        exit;
    }
    
    $stmt = $pdo->prepare("DELETE FROM id_layanan WHERE id_layanan = ?");
    $stmt->execute([$data['id_layanan']]);
    
    echo json_encode(['success' => true, 'message' => 'Layanan berhasil dihapus']);
}
?>
