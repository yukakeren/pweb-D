-- Database LaundryCrafty
CREATE DATABASE IF NOT EXISTS laundrycrafty;
USE laundrycrafty;

-- Tabel Pelanggan
CREATE TABLE IF NOT EXISTS id_pelanggan (
    id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    alamat TEXT,
    no_hp VARCHAR(15)
);

-- Tabel Layanan
CREATE TABLE IF NOT EXISTS id_layanan (
    id_layanan INT AUTO_INCREMENT PRIMARY KEY,
    nama_layanan VARCHAR(100) NOT NULL,
    harga_per_kg DECIMAL(10,2) NOT NULL
);

-- Tabel Transaksi
CREATE TABLE IF NOT EXISTS id_transaksi (
    id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    id_pelanggan INT NOT NULL,
    id_layanan INT NOT NULL,
    tanggal_masuk DATE NOT NULL,
    tanggal_selesai DATE,
    berat DECIMAL(10,2) NOT NULL,
    total_harga DECIMAL(10,2) NOT NULL,
    status ENUM('Proses', 'Selesai', 'Sudah Diambil') DEFAULT 'Proses',
    FOREIGN KEY (id_pelanggan) REFERENCES id_pelanggan(id_pelanggan) ON DELETE CASCADE,
    FOREIGN KEY (id_layanan) REFERENCES id_layanan(id_layanan) ON DELETE CASCADE
);

-- Tabel User (Admin)
CREATE TABLE IF NOT EXISTS id_user (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'kasir', 'operator') DEFAULT 'kasir'
);

-- Insert data admin default
INSERT INTO id_user (username, password, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
-- Password: password

-- Insert data layanan default
INSERT INTO id_layanan (nama_layanan, harga_per_kg) VALUES
('Cuci Setrika', 7000),
('Cuci Kering', 5000),
('Setrika', 5000),
('Express', 12000);

-- Insert data pelanggan contoh
INSERT INTO id_pelanggan (nama, alamat, no_hp) VALUES
('John Doe', 'Jl. Merdeka No. 123', '081234567890'),
('Jane Smith', 'Jl. Sudirman No. 456', '082345678901');
