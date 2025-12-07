-- Database CRUD Siswa
CREATE DATABASE IF NOT EXISTS crud_siswa;
USE crud_siswa;

-- Tabel Siswa
CREATE TABLE IF NOT EXISTS siswa (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nis VARCHAR(11) NOT NULL UNIQUE,
    nama VARCHAR(50) NOT NULL,
    jenis_kelamin VARCHAR(10) NOT NULL,
    telp VARCHAR(15),
    alamat TEXT,
    foto VARCHAR(200)
);

-- Insert sample data
INSERT INTO siswa (nis, nama, jenis_kelamin, telp, alamat, foto) VALUES
('20110070216', 'Ade Shandia Ramadina', 'Perempuan', '0894756382', 'Jl.Kopi Getanja No.101', 'default.jpg'),
('20110070011', 'Aini Lestari', 'Perempuan', '0894281777', 'Jl.Bengalo No.98', 'default.jpg'),
('20110070012', 'Imam Maulana', 'Laki-laki', '0894177108', 'Jl.PadiKubik Bdc.18', 'default.jpg'),
('20110070013', 'Kukis Mittra Kartiwan', 'Perempuan', '0894077777', 'Jl.Bumi Siliwangi No.29', 'default.jpg'),
('20110072001', 'Rosalia Mustafa Aschandi', 'Laki-laki', '0894277852', 'Jl.Kopi Sclowengi No.21', 'default.jpg');
