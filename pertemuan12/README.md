# CRUD Biodata Siswa dengan Upload Foto

## Deskripsi Proyek
Aplikasi CRUD (Create, Read, Update, Delete) untuk mengelola biodata siswa dengan fitur upload foto. Setiap pendaftar diminta untuk mengupload pas foto ukuran 3 X 4. Gambar diupload melalui formulir Tambah Siswa dan otomatis diresize ke proporsi 3x4 (300x400 pixel).

## Teknologi yang Digunakan

### Backend
- PHP 7.4+
- MySQL/MariaDB
- PDO (PHP Data Objects)

### Frontend
- HTML5
- CSS3
- Responsive Design

## Struktur Database

### Tabel: siswa

| Field | Type | Description |
|-------|------|-------------|
| id | int(11) | Primary Key, Auto Increment |
| nis | varchar(11) | Nomor Induk Siswa (Unique) |
| nama | varchar(50) | Nama lengkap siswa |
| jenis_kelamin | varchar(10) | Laki-laki / Perempuan |
| telp | varchar(15) | Nomor telepon |
| alamat | text | Alamat lengkap |
| foto | varchar(200) | Nama file foto |

## Struktur Folder Proyek

```
crud_siswa/
├── config/
│   └── database.php          # Konfigurasi koneksi database
├── css/
│   └── style.css            # Stylesheet aplikasi
├── images/                  # Folder penyimpanan foto siswa
├── database.sql             # File SQL schema dan data sample
└── index.php                # Halaman utama aplikasi (CRUD)
```

## Fitur Aplikasi

### 1. Create (Tambah Data)
- Form input data siswa (NIS, Nama, Jenis Kelamin, Telepon, Alamat)
- Upload foto dengan validasi format (JPG, JPEG, PNG, GIF)
- Auto resize foto ke ukuran 3x4 (300x400 pixel)
- Validasi NIS unique

### 2. Read (Tampil Data)
- Tabel data siswa dengan foto thumbnail
- Menampilkan semua informasi siswa
- Data terurut dari yang terbaru

### 3. Update (Edit Data)
- Form edit dengan data yang sudah ada
- Opsi update foto atau tetap menggunakan foto lama
- Hapus foto lama jika upload foto baru

### 4. Delete (Hapus Data)
- Konfirmasi sebelum menghapus
- Hapus data dari database
- Hapus file foto dari server

## Instalasi dan Penggunaan

### Persiapan Environment
1. Install XAMPP atau Laragon
2. Pastikan Apache dan MySQL berjalan
3. Copy folder `crud_siswa` ke `htdocs` (XAMPP) atau `www` (Laragon)

### Setup Database
1. Buka phpMyAdmin (http://localhost/phpmyadmin)
2. Buat database baru dengan nama `crud_siswa`
3. Import file `database.sql` atau jalankan query SQL berikut:

```sql
CREATE DATABASE IF NOT EXISTS crud_siswa;
USE crud_siswa;

CREATE TABLE IF NOT EXISTS siswa (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nis VARCHAR(11) NOT NULL UNIQUE,
    nama VARCHAR(50) NOT NULL,
    jenis_kelamin VARCHAR(10) NOT NULL,
    telp VARCHAR(15),
    alamat TEXT,
    foto VARCHAR(200)
);
```

### Konfigurasi Database
Edit file `config/database.php` jika perlu mengubah kredensial:

```php
$host = 'localhost';
$dbname = 'crud_siswa';
$username = 'root';
$password = '';
```

### Setup Folder Upload
1. Pastikan folder `images/` sudah ada di direktori `crud_siswa/`
2. Berikan permission write pada folder `images/` (chmod 777 untuk Linux/Mac)
3. Tambahkan file `default.jpg` di folder `images/` untuk foto default

### Akses Aplikasi
Buka browser dan akses: `http://localhost/pweb-D/pertemuan12/crud_siswa/index.php`

## Cara Penggunaan

### Menambah Data Siswa
1. Isi formulir "Tambah Data Siswa"
2. Masukkan NIS (harus unique)
3. Isi nama, pilih jenis kelamin
4. Masukkan nomor telepon dan alamat
5. Pilih file foto (format: JPG, JPEG, PNG, GIF)
6. Klik tombol "Simpan"
7. Foto akan otomatis diresize ke 300x400 pixel (rasio 3x4)

### Mengedit Data Siswa
1. Klik tombol "Edit" pada data siswa yang ingin diubah
2. Form akan terisi dengan data siswa tersebut
3. Ubah data yang diperlukan
4. Jika ingin mengganti foto, pilih file foto baru
5. Klik tombol "Update"

### Menghapus Data Siswa
1. Klik tombol "Hapus" pada data siswa yang ingin dihapus
2. Konfirmasi penghapusan
3. Data dan foto akan terhapus dari sistem

## Fitur Upload Foto

### Validasi Format
- Format yang diperbolehkan: JPG, JPEG, PNG, GIF
- File akan ditolak jika format tidak sesuai

### Auto Resize
- Foto yang diupload akan otomatis diresize
- Ukuran target: 300 x 400 pixel (rasio 3:4)
- Menggunakan fungsi GD Library PHP
- Kualitas gambar tetap terjaga (90% untuk JPEG)

### Naming Convention
- Nama file foto menggunakan timestamp + nama file asli
- Format: `{timestamp}_{filename}.{ext}`
- Contoh: `1701936723_foto_siswa.jpg`

### Pengelolaan File
- Foto lama otomatis dihapus saat update dengan foto baru
- Foto ikut terhapus saat data siswa dihapus
- File `default.jpg` tidak akan dihapus

## Keamanan

### Validasi Input
- Server-side validation untuk semua input
- Prepared statements untuk mencegah SQL Injection
- Validasi format file upload

### Upload Security
- Whitelist format file (jpg, jpeg, png, gif)
- Validasi error upload
- Rename file dengan timestamp

### Error Handling
- Try-catch untuk operasi database
- Pesan error yang informatif
- Redirect setelah operasi berhasil

## Responsive Design

Aplikasi menggunakan CSS responsive dengan breakpoint:
- Desktop: >= 769px
- Tablet & Mobile: <= 768px

Fitur responsive:
- Form menyesuaikan lebar layar
- Tabel dengan horizontal scroll di mobile
- Tombol full-width di mobile
- Font size otomatis menyesuaikan

## Testing

### Manual Testing Checklist
- [ ] Tambah data siswa tanpa foto (gunakan default.jpg)
- [ ] Tambah data siswa dengan foto
- [ ] Validasi NIS unique (coba input NIS yang sama)
- [ ] Edit data tanpa mengganti foto
- [ ] Edit data dengan mengganti foto
- [ ] Hapus data siswa
- [ ] Cek foto tersimpan di folder images/
- [ ] Cek foto ter-resize ke 300x400px
- [ ] Test di berbagai browser (Chrome, Firefox, Edge)
- [ ] Test responsive di mobile/tablet

### Expected Behavior
1. Upload Success: Foto tersimpan di folder images/ dengan size 300x400px
2. Edit Success: Foto lama terhapus, foto baru tersimpan
3. Delete Success: Data dan foto terhapus dari sistem
4. Validation: Error muncul jika NIS duplicate

## Troubleshooting

### Error: "Koneksi database gagal"
- Pastikan MySQL berjalan
- Cek kredensial database di `config/database.php`
- Pastikan database `crud_siswa` sudah dibuat

### Error: "Failed to upload file"
- Cek permission folder `images/` (harus writable)
- Pastikan folder `images/` ada
- Cek ukuran file (sesuaikan `upload_max_filesize` di php.ini jika perlu)

### Foto tidak tampil
- Pastikan path foto benar: `images/{nama_file}`
- Cek file foto ada di folder `images/`
- Periksa permission file

### Foto tidak ter-resize
- Pastikan GD Library PHP sudah terinstall
- Cek dengan `phpinfo()` apakah GD enable
- Install GD jika belum ada: `sudo apt-get install php-gd` (Linux)

## Pengembangan Lanjutan

### Fitur yang bisa ditambahkan:
1. Pagination untuk tabel data
2. Search/Filter data siswa
3. Export data ke Excel/PDF
4. Crop foto sebelum upload
5. Preview foto sebelum upload
6. Validasi size file maksimal
7. Multiple file upload
8. User authentication
9. Role-based access control
10. Audit log untuk tracking perubahan data

### Optimasi:
1. Lazy loading untuk foto
2. Caching database query
3. Compress foto dengan quality setting
4. CDN untuk static assets
5. AJAX untuk operasi CRUD tanpa reload

## Browser Compatibility

Aplikasi telah ditest dan kompatibel dengan:
- Google Chrome (versi terbaru)
- Mozilla Firefox (versi terbaru)
- Microsoft Edge (versi terbaru)
- Safari (versi terbaru)

## Lisensi

Project ini dibuat untuk keperluan pembelajaran dan latihan pemrograman web PHP.

## Catatan

- Pastikan PHP GD Library terinstall untuk fitur resize foto
- Backup database secara berkala
- Pastikan folder images/ memiliki permission yang tepat
- Gunakan HTTPS di production untuk keamanan upload file

## Kontak

Untuk pertanyaan atau bantuan, silakan hubungi pengembang.

---

Versi: 1.0.0
Tanggal: 7 Desember 2025
