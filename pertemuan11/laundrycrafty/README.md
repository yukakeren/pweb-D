# LaundryCrafty - Sistem Manajemen Laundry

## 🧺 Deskripsi
LaundryCrafty adalah aplikasi web-based untuk manajemen usaha laundry yang membantu pengelolaan data pelanggan, layanan, transaksi, dan laporan keuangan secara efisien dan terintegrasi.

## 🚀 Fitur Utama

- ✅ **Autentikasi Admin** - Login dengan keamanan password hashing
- 📊 **Dashboard Interaktif** - Statistik real-time dan grafik pendapatan
- 👥 **Manajemen Pelanggan** - CRUD pelanggan dengan pencarian
- 🧼 **Manajemen Layanan** - Kelola jenis layanan dan harga
- 💰 **Transaksi Laundry** - Input transaksi dengan perhitungan otomatis
- 📈 **Laporan Pendapatan** - Filter periode, grafik, dan export CSV
- 🔄 **Update Status** - Proses, Selesai, Sudah Diambil
- 📱 **Responsive Design** - Dapat diakses dari desktop, tablet, dan mobile

## 🛠️ Teknologi

**Front-End:**
- HTML5, CSS3
- Bootstrap 5
- JavaScript (Vanilla)
- Chart.js

**Back-End:**
- PHP
- MySQL/MariaDB
- PDO

## 📦 Instalasi

### Persyaratan
- XAMPP/Laragon
- PHP 7.4+
- MySQL/MariaDB

### Langkah Instalasi

1. **Clone atau copy folder** ke `htdocs` (XAMPP) atau `www` (Laragon)

2. **Import Database**
   - Buka phpMyAdmin (http://localhost/phpmyadmin)
   - Import file `database.sql`

3. **Konfigurasi Database** (opsional)
   - Edit `config/database.php` jika perlu mengubah kredensial

4. **Akses Aplikasi**
   - Buka: `http://localhost/pweb-D/pertemuan11/laundrycrafty/login.html`
   - Login dengan:
     - Username: `admin`
     - Password: `password`

## 📁 Struktur Proyek

```
laundrycrafty/
├── config/
│   └── database.php
├── api/
│   ├── login.php
│   ├── dashboard.php
│   ├── pelanggan.php
│   ├── layanan.php
│   ├── transaksi.php
│   ├── laporan.php
│   └── export_laporan.php
├── css/
│   └── style.css
├── js/
│   ├── login.js
│   ├── dashboard.js
│   ├── pelanggan.js
│   ├── layanan.js
│   ├── transaksi.js
│   └── laporan.js
├── login.html
├── dashboard.html
├── pelanggan.html
├── layanan.html
├── transaksi.html
├── laporan.html
└── database.sql
```

## 📊 Database Schema

### Tabel Utama:
- `id_user` - Data admin/kasir/operator
- `id_pelanggan` - Data pelanggan
- `id_layanan` - Jenis layanan & harga
- `id_transaksi` - Transaksi laundry

## 🔐 Keamanan

- Password hashing dengan `password_hash()`
- Prepared statements untuk SQL injection prevention
- Session management
- Client-side & server-side validation

## 📱 API Endpoints (Optional)

```
GET  /api/pelanggan           - List semua pelanggan
POST /api/pelanggan           - Tambah pelanggan
PUT  /api/pelanggan           - Update pelanggan
DELETE /api/pelanggan         - Hapus pelanggan

GET  /api/layanan             - List semua layanan
POST /api/layanan             - Tambah layanan

GET  /api/transaksi           - List transaksi
POST /api/transaksi           - Tambah transaksi
PUT  /api/transaksi           - Update status

GET  /api/laporan?dari=&sampai= - Laporan pendapatan
GET  /api/export_laporan.php    - Export CSV
```

## 🎯 Cara Penggunaan

1. **Login** sebagai admin
2. **Tambah Pelanggan** baru (jika belum terdaftar)
3. **Input Transaksi** dengan memilih pelanggan, layanan, dan berat
4. **Update Status** cucian (Proses → Selesai → Sudah Diambil)
5. **Lihat Laporan** pendapatan dengan filter periode
6. **Export Laporan** ke CSV untuk analisis lebih lanjut

## 📸 Screenshot

- **Dashboard**: Statistik real-time dengan grafik 7 hari
- **Pelanggan**: Tabel CRUD dengan search
- **Layanan**: Manajemen layanan & harga
- **Transaksi**: Form input dengan perhitungan otomatis
- **Laporan**: Filter periode, grafik, dan export

## 🚧 Future Enhancement

- WhatsApp notification integration
- QR Code untuk nota pelanggan
- Multi-branch support
- Payment gateway integration
- Mobile app (PWA)
- Email reminder otomatis

## 📝 Lisensi

Project ini dibuat untuk keperluan pembelajaran dan latihan web programming.

## 👨‍💻 Developer

Dibuat sebagai bagian dari modul studi kasus Pemrograman Web.

---

**Version**: 1.0.0  
**Last Updated**: 7 Desember 2025
