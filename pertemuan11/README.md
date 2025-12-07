# LAPORAN IMPLEMENTASI LAUNDRYCRAFTY

## Informasi Proyek
- **Nama Aplikasi**: LaundryCrafty
- **Deskripsi**: Sistem manajemen usaha laundry berbasis web

---

## 1. RINGKASAN EKSEKUTIF

LaundryCrafty adalah aplikasi web-based yang dirancang untuk membantu pengelolaan usaha laundry dalam mengelola data pelanggan, layanan, transaksi, dan laporan keuangan. Aplikasi ini dibangun dengan teknologi modern menggunakan HTML5, CSS3 (Bootstrap 5), JavaScript, dan PHP dengan database MySQL/MariaDB.

---

## 2. TEKNOLOGI YANG DIGUNAKAN

### Front-End
- **HTML5**: Struktur halaman web
- **CSS3 & Bootstrap 5**: Styling dan responsive design
- **JavaScript (Vanilla JS)**: Interaktivitas client-side
- **Chart.js**: Visualisasi data grafik
- **Bootstrap Icons**: Icon library

### Back-End
- **PHP**: Server-side scripting
- **MySQL/MariaDB**: Database management system
- **PDO**: Database connection dan query

### Tools & Environment
- **VS Code**: Code editor
- **XAMPP/Laragon**: Local development server
- **phpMyAdmin**: Database management

---

## 3. STRUKTUR DATABASE

### Tabel yang Diimplementasikan:

#### 1. `id_user` (Admin/User Authentication)
```sql
- id_user (INT, PRIMARY KEY, AUTO_INCREMENT)
- username (VARCHAR(50), UNIQUE)
- password (VARCHAR(255), hashed)
- role (ENUM: 'admin', 'kasir', 'operator')
```

#### 2. `id_pelanggan` (Customer Data)
```sql
- id_pelanggan (INT, PRIMARY KEY, AUTO_INCREMENT)
- nama (VARCHAR(100))
- alamat (TEXT)
- no_hp (VARCHAR(15))
```

#### 3. `id_layanan` (Service Types)
```sql
- id_layanan (INT, PRIMARY KEY, AUTO_INCREMENT)
- nama_layanan (VARCHAR(100))
- harga_per_kg (DECIMAL(10,2))
```

#### 4. `id_transaksi` (Transaction Records)
```sql
- id_transaksi (INT, PRIMARY KEY, AUTO_INCREMENT)
- id_pelanggan (INT, FOREIGN KEY)
- id_layanan (INT, FOREIGN KEY)
- tanggal_masuk (DATE)
- tanggal_selesai (DATE)
- berat (DECIMAL(10,2))
- total_harga (DECIMAL(10,2))
- status (ENUM: 'Proses', 'Selesai', 'Sudah Diambil')
```

---

## 4. FITUR-FITUR YANG DIIMPLEMENTASIKAN

### 4.1 Autentikasi & Keamanan
✅ **Login Admin**
- Form login dengan validasi username dan password
- Password hashing menggunakan `password_verify()`
- Session management untuk keamanan
- Role-based authentication

### 4.2 Dashboard
✅ **Statistik Real-time**
- Total pelanggan
- Transaksi hari ini
- Cucian sedang proses
- Pendapatan hari ini

✅ **Visualisasi Data**
- Grafik pendapatan 7 hari terakhir menggunakan Chart.js
- Daftar transaksi terbaru

### 4.3 Manajemen Pelanggan
✅ **CRUD Operations**
- Tambah pelanggan baru
- Edit data pelanggan
- Hapus pelanggan
- Pencarian pelanggan (real-time search)
- View list pelanggan dalam tabel responsif

### 4.4 Manajemen Layanan
✅ **Service Management**
- Tambah layanan laundry (Cuci Setrika, Cuci Kering, Express, dll)
- Edit harga per kilogram
- Hapus layanan
- Display layanan dalam format tabel

### 4.5 Transaksi Laundry
✅ **Transaction Processing**
- Input transaksi baru dengan perhitungan otomatis
- Pemilihan pelanggan dari dropdown
- Pemilihan layanan dengan harga otomatis
- Perhitungan total harga (berat × harga per kg)
- Update status transaksi (Proses → Selesai → Sudah Diambil)
- Filter transaksi berdasarkan status dan tanggal
- Hapus transaksi

### 4.6 Laporan Pendapatan
✅ **Reporting System**
- Filter laporan berdasarkan periode tanggal
- Summary statistics (total transaksi, total pendapatan, rata-rata)
- Grafik pendapatan harian
- Tabel detail transaksi
- Export laporan ke CSV/Excel

### 4.7 API Endpoints (Optional)
✅ **RESTful API**
- `GET /api/pelanggan` - Mendapatkan semua data pelanggan
- `GET /api/layanan` - Mendapatkan semua layanan
- `GET /api/transaksi` - Mendapatkan semua transaksi
- `GET /api/laporan?periode=bulan` - Laporan keuangan bulanan
- `GET /api/laporan/nota?id={id}` - Cetak nota transaksi

---

## 5. STRUKTUR FILE PROYEK

```
laundrycrafty/
├── config/
│   └── database.php          # Konfigurasi koneksi database
├── api/
│   ├── login.php            # API autentikasi
│   ├── dashboard.php        # API data dashboard
│   ├── pelanggan.php        # API CRUD pelanggan
│   ├── layanan.php          # API CRUD layanan
│   ├── transaksi.php        # API CRUD transaksi
│   ├── laporan.php          # API laporan pendapatan
│   ├── export_laporan.php   # Export laporan ke CSV
│   └── api_endpoints.php    # Optional API endpoints
├── css/
│   └── style.css            # Custom styling
├── js/
│   ├── login.js             # Login functionality
│   ├── dashboard.js         # Dashboard functionality
│   ├── pelanggan.js         # Customer management
│   ├── layanan.js           # Service management
│   ├── transaksi.js         # Transaction management
│   └── laporan.js           # Report functionality
├── login.html               # Halaman login
├── dashboard.html           # Halaman dashboard
├── pelanggan.html           # Halaman manajemen pelanggan
├── layanan.html             # Halaman manajemen layanan
├── transaksi.html           # Halaman transaksi
├── laporan.html             # Halaman laporan
└── database.sql             # SQL schema dan sample data
```

---

## 6. CARA INSTALASI & PENGGUNAAN

### 6.1 Persiapan Environment
1. Install XAMPP atau Laragon
2. Pastikan Apache dan MySQL berjalan
3. Clone/copy folder `laundrycrafty` ke `htdocs` (XAMPP) atau `www` (Laragon)

### 6.2 Setup Database
1. Buka phpMyAdmin (http://localhost/phpmyadmin)
2. Import file `database.sql`
3. Database `laundrycrafty` akan otomatis terbuat dengan sample data

### 6.3 Konfigurasi
1. Edit file `config/database.php` jika perlu mengubah kredensial database:
   ```php
   $host = 'localhost';
   $dbname = 'laundrycrafty';
   $username = 'root';
   $password = '';
   ```

### 6.4 Akses Aplikasi
1. Buka browser dan akses: `http://localhost/pweb-D/pertemuan11/laundrycrafty/login.html`
2. Login dengan kredensial:
   - **Username**: `admin`
   - **Password**: `password`

---

## 7. FITUR KEAMANAN

### Implementasi Keamanan:
✅ **Password Hashing**: Menggunakan `password_hash()` dan `password_verify()`
✅ **Session Management**: Validasi session di setiap halaman
✅ **SQL Injection Prevention**: Menggunakan Prepared Statements (PDO)
✅ **XSS Prevention**: Client-side validation dan sanitization
✅ **Authentication Check**: Redirect ke login jika tidak terautentikasi

---

## 8. VALIDASI FITUR SESUAI REQUIREMENT

### ✅ Fitur Utama (Sesuai Spesifikasi):
- [x] Manajemen Pelanggan: Tambah, ubah, hapus, cari pelanggan
- [x] Manajemen Layanan: Input jenis layanan & harga
- [x] Transaksi Laundry: Input transaksi, jenis layanan, berat, harga, tanggal
- [x] Status Cucian: Update status (Proses, Selesai, Sudah Diambil)
- [x] Laporan Keuangan: Filter tanggal, grafik pendapatan
- [x] Dashboard Statistik: Total pelanggan, transaksi, grafik
- [x] Autentikasi Admin: Login dengan role-based access

### ✅ Spesifikasi Teknis:
- [x] Front-End: HTML5, CSS3, Bootstrap, JavaScript
- [x] Back-End: PHP
- [x] Database: MySQL/MariaDB
- [x] API: RESTful endpoints (optional - sudah diimplementasi)

### ✅ Alur Proses Sistem:
- [x] Admin login
- [x] Kasir menambahkan data pelanggan baru (jika belum terdaftar)
- [x] Kasir mencatat transaksi laundry baru
- [x] Operator mengupdate status cucian
- [x] Kasir melihat status cucian
- [x] Admin melihat laporan pendapatan

---

## 9. TESTING & VALIDASI

### Manual Testing:
✅ **Login Page**: Validasi username/password, error handling
✅ **Dashboard**: Load statistik, grafik Chart.js, transaksi terbaru
✅ **Pelanggan**: CRUD operations, pencarian real-time
✅ **Layanan**: CRUD operations, format currency
✅ **Transaksi**: Input transaksi, perhitungan otomatis, update status, filter
✅ **Laporan**: Filter periode, grafik, export CSV

### Browser Compatibility:
✅ Google Chrome
✅ Mozilla Firefox
✅ Microsoft Edge

### Responsive Design:
✅ Desktop (1920x1080)
✅ Tablet (768px)
✅ Mobile (480px)

---

## 10. SCREENSHOT APLIKASI

### 10.1 Halaman Login
- Form login dengan validasi
- Error message handling
- Clean & modern UI

### 10.2 Dashboard
- 4 statistik cards (pelanggan, transaksi, proses, pendapatan)
- Grafik pendapatan 7 hari
- Transaksi terbaru

### 10.3 Manajemen Pelanggan
- Tabel data pelanggan
- Tombol tambah, edit, hapus
- Search functionality

### 10.4 Manajemen Layanan
- Tabel layanan dengan harga
- CRUD operations
- Format Rupiah

### 10.5 Transaksi Laundry
- Form input transaksi
- Dropdown pelanggan & layanan
- Perhitungan otomatis
- Filter status & tanggal
- Update status modal

### 10.6 Laporan Pendapatan
- Filter periode custom
- Summary cards
- Grafik bar chart
- Export to CSV

---

## 11. KELEBIHAN APLIKASI

1. **User-Friendly Interface**: Design modern dengan Bootstrap 5
2. **Real-time Calculation**: Perhitungan total harga otomatis
3. **Data Visualization**: Grafik interaktif dengan Chart.js
4. **Responsive Design**: Dapat diakses dari desktop, tablet, mobile
5. **Secure Authentication**: Password hashing dan session management
6. **Export Feature**: Export laporan ke CSV untuk analisis lebih lanjut
7. **API Ready**: Tersedia RESTful API untuk integrasi future development
8. **Clean Code**: Struktur kode yang rapi dan mudah di-maintain

---

## 12. SARAN PENGEMBANGAN LANJUTAN (Future Enhancement)

1. **WhatsApp Integration**: Notifikasi otomatis ke pelanggan via WhatsApp API
2. **QR Code**: QR code untuk nota pelanggan
3. **Dashboard Analytics**: Grafik lebih advance dengan Chart.js atau Google Charts
4. **Multi-Branch**: Support untuk multiple cabang laundry
5. **Payment Gateway**: Integrasi dengan Midtrans/DOKU untuk pembayaran online
6. **Mobile App**: PWA (Progressive Web App) atau React Native
7. **Cloud Storage**: Upload foto kondisi pakaian sebelum & sesudah
8. **Email Notification**: Reminder otomatis ke email pelanggan

---

## 13. KESIMPULAN

Aplikasi **LaundryCrafty** telah berhasil diimplementasikan sesuai dengan spesifikasi yang tertera dalam modul studi kasus. Semua fitur utama telah berfungsi dengan baik:

✅ Manajemen Pelanggan  
✅ Manajemen Layanan  
✅ Transaksi Laundry  
✅ Update Status Cucian  
✅ Laporan Keuangan  
✅ Dashboard Statistik  
✅ Autentikasi & Keamanan  
✅ API Endpoints (Bonus)  

Aplikasi ini siap digunakan untuk membantu pengelolaan usaha laundry dengan fitur yang lengkap, aman, dan user-friendly.

---

## 14. REFERENSI

- Bootstrap 5 Documentation: https://getbootstrap.com/
- Chart.js Documentation: https://www.chartjs.org/
- PHP PDO Documentation: https://www.php.net/manual/en/book.pdo.php
- MySQL Documentation: https://dev.mysql.com/doc/

---

**Dibuat oleh**: Tim Development LaundryCrafty  
**Tanggal**: 7 Desember 2025  
**Versi**: 1.0.0
