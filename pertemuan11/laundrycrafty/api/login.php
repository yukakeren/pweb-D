<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['username']) || !isset($data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Username dan password harus diisi']);
    exit;
}

$username = $data['username'];
$password = $data['password'];

$stmt = $pdo->prepare("SELECT * FROM id_user WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id_user'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    
    echo json_encode([
        'success' => true,
        'user' => [
            'id' => $user['id_user'],
            'username' => $user['username'],
            'role' => $user['role']
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Username atau password salah']);
}
?>
