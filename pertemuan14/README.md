# Sistem User Management – Pertemuan 14

Dokumen ini menjelaskan implementasi sederhana sistem manajemen pengguna berbasis PHP dan MySQL. Aplikasi mendukung login, validasi hak akses (admin, pegawai, pengurus), halaman dashboard per-peran, serta proses logout yang aman. Berikut ringkasan alur dan berkas yang terlibat.

## Alur Kerja Aplikasi

1) Persiapan database, tabel, dan koneksi

- `declare.sql`: skrip untuk membuat database `multi_user` beserta tabel `user`. Tabel memiliki kolom `level` yang menyimpan tipe hak akses seperti admin, pegawai, atau pengurus.
- `koneksi.php`: modul koneksi yang menghubungkan aplikasi ke database `multi_user` menggunakan `mysqli_connect()` agar semua operasi query dapat berjalan.

2) Tampilan login dan gaya dasar

- `index.php`: halaman login berisi dua input (username dan password) yang dikirim ke `cek_login.php` via metode POST. Jika URL memuat parameter `?pesan=gagal`, halaman menampilkan pemberitahuan bahwa kredensial tidak valid.
- `style.css`: stylesheet yang merapikan tata letak form login dan memposisikannya di pusat layar agar mudah diakses.

3) Validasi kredensial dan penetapan hak akses

- `cek_login.php`: menerima data dari form login, membuka sesi dengan `session_start()`, lalu melakukan pengecekan via query `SELECT * FROM user WHERE username='...' AND password='...'`. Jika ada kecocokan:
	- data pengguna diambil dengan `mysqli_fetch_assoc()`;
	- nilai `level` dievaluasi menggunakan percabangan `if/else if`;
	- `username` dan `level` disimpan ke dalam session;
	- pengguna diarahkan ke halaman dashboard sesuai perannya.
	Jika tidak cocok, pengguna dibawa kembali ke `index.php?pesan=gagal`.

4) Dashboard per peran pengguna

- `halaman_admin.php`, `halaman_pegawai.php`, `halaman_pengurus.php`: masing-masing berperan sebagai dashboard untuk level terkait. Pada awal file dilakukan pemeriksaan `$_SESSION['level']`. Jika belum ada, pengguna dikembalikan ke halaman login. Dashboard menampilkan informasi singkat pengguna yang sedang login dan menyediakan tombol logout.

5) Logout dan pembersihan session

- `logout.php`: menjalankan `session_destroy()` untuk menghapus seluruh data session dan mengarahkan pengguna kembali ke halaman login (`index.php`).

## Cuplikan Tampilan

Halaman-halaman antarmuka yang disiapkan meliputi form login dan tiga dashboard peran. Referensi visual tersedia pada berkas gambar berikut:

- Login: `(/Pertemuan14/imgs/index.png)`
- Admin: `(/Pertemuan14/imgs/admin.png)`
- Pengurus: `(/Pertemuan14/imgs/pengurus.png)`
- Pegawai: `(/Pertemuan14/imgs/pegawai.png)`

## Ringkasan Perilaku

- Pengguna memasukkan kredensial pada `index.php`. Sistem memvalidasi di `cek_login.php`.
- Jika valid, session berisi `username` dan `level` dibuat, dan pengguna diarahkan ke dashboard perannya.
- Jika tidak valid, sistem menampilkan pesan gagal dan tetap di halaman login.
- Pada dashboard, sistem memastikan session `level` aktif sebelum menampilkan konten.
- Logout menghapus session aktif dan mengembalikan pengguna ke form login.

## Catatan Implementasi

- Pastikan database `multi_user` dan tabel `user` telah dibuat menggunakan `declare.sql`.
- Sesuaikan konfigurasi koneksi dalam `koneksi.php` dengan kredensial database lokal Anda.
- Untuk keamanan produksi, hindari penyimpanan kata sandi dalam bentuk teks biasa. Gunakan hashing seperti `password_hash()`/`password_verify()`.

