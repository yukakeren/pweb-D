# Arsitektur Aplikasi Web: Manajemen Data Siswa (CRUD + Upload Foto)

**Pertemuan 12**

Dokumen ini menjelaskan arsitektur aplikasi web sederhana untuk manajemen data siswa yang mencakup fitur *Create, Read, Update, Delete* (CRUD) serta pengelolaan upload foto. Aplikasi ini dirancang menggunakan konsep **3-Layer Architecture** untuk memisahkan antarmuka (UI), logika bisnis/pemrosesan, dan penyimpanan data.

## 🖼️ Diagram Arsitektur

Berikut adalah visualisasi alur data dan interaksi antar file dalam sistem:

-----

## 🏗️ Penjelasan 3-Layer Architecture

### 1. Presentation Layer (Front-End)

Layer ini bertanggung jawab untuk antarmuka pengguna (User Interface). Berisi file PHP yang menghasilkan tampilan HTML (tanpa CSS khusus) untuk interaksi pengguna.

  * **`index.php`**
      * **Fungsi:** Entry point aplikasi.
      * **Detail:** Menampilkan tabel seluruh data siswa beserta fotonya (diambil dari folder `images/`). Menyediakan navigasi tombol untuk **Tambah**, **Ubah**, dan **Hapus**.
  * **`form_simpan.php`**
      * **Fungsi:** Halaman input data baru.
      * **Detail:** Form HTML yang mengirim data via method `POST` ke `proses_simpan.php`. Form menggunakan atribut `enctype="multipart/form-data"` untuk mendukung upload file foto.
  * **`form_ubah.php`**
      * **Fungsi:** Halaman edit data yang sudah ada.
      * **Detail:** Form terisi otomatis berdasarkan `id` siswa yang dipilih. Mengirim data perubahan ke `proses_ubah.php`. Memungkinkan pengguna mengganti foto lama dengan yang baru.

### 2. Application Layer (Back-End)

Layer ini mengandung logika pemrosesan request, validasi, interaksi database (via PDO), dan manajemen file (upload/hapus).

  * **`koneksi.php`**
      * **Fungsi:** Konfigurasi database.
      * **Detail:** Menginisialisasi koneksi ke MySQL menggunakan **PDO ($pdo)** dan mengatur *error mode* untuk debugging.
  * **`proses_simpan.php`**
      * **Fungsi:** Logika *Create*.
      * **Detail:** Menerima input dari form, memproses upload gambar (menggunakan `move_uploaded_file` ke folder `images/`), dan menamai file secara unik (`date('dmYHis')` + nama asli). Data disimpan ke database menggunakan `INSERT` (dengan `bindParam` + `execute`). Jika sukses, *redirect* ke `index.php`.
  * **`proses_ubah.php`**
      * **Fungsi:** Logika *Update*.
      * **Detail:** Cek kondisi upload:
          * *Jika tidak ada file baru:* Jalankan `UPDATE` data teks saja.
          * *Jika ada file baru:* Upload foto baru, hapus foto lama (menggunakan fungsi `unlink`), lalu `UPDATE` data termasuk nama file baru di database.
  * **`proses_hapus.php`**
      * **Fungsi:** Logika *Delete*.
      * **Detail:** Mengambil nama file foto berdasarkan ID, menghapus fisik file dari folder `images/` (jika ada), kemudian melakukan query `DELETE` pada baris data di database.

> **Alur Ringkas Aplikasi:**
> Front-End (Form) → Back-End (Proses POST/GET) → Database/Storage → Front-End (Redirect).

### 3. Data Layer (Database & Storage)

Layer ini menangani persistensi data (database) dan penyimpanan fisik file (storage).

#### Database MySQL

  * **Nama Database:** `fotocrud`
  * **File Skema:** `db.sql`

**Struktur Tabel `siswa`:**

```sql
CREATE TABLE `siswa` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nis` VARCHAR(11) NOT NULL,
  `nama` VARCHAR(50) NOT NULL,
  `jenis_kelamin` VARCHAR(10) NOT NULL,
  `telp` VARCHAR(15) NOT NULL,
  `alamat` TEXT NOT NULL,
  `foto` VARCHAR(200) NOT NULL
);
```

#### File Storage

  * **`images/`**: Folder *runtime* untuk menyimpan foto siswa yang di-upload oleh user. Penamaan file dibuat unik (Timestamp + Nama Asli).
  * **`imgs/`**: Folder *asset* statis (misalnya untuk gambar dokumentasi/logo aplikasi), tidak digunakan untuk upload user.

-----

## 📂 Pemetaan File (File Mapping)

Ringkasan lokasi file berdasarkan layer arsitektur:

| Layer | File / Folder |
| :--- | :--- |
| **Presentation** | `index.php`, `form_simpan.php`, `form_ubah.php` |
| **Application** | `koneksi.php`, `proses_simpan.php`, `proses_ubah.php`, `proses_hapus.php` |
| **Data** | `db.sql`, folder `images/`, folder `imgs/` |

