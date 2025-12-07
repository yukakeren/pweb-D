<?php
require_once 'config/database.php';

// Tambah Data
if (isset($_POST['simpan'])) {
    $nis = $_POST['nis'];
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];
    
    // Upload foto
    $foto = 'default.jpg';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['foto']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($filetype), $allowed)) {
            // Resize image to 3x4 ratio
            $new_filename = time() . '_' . $filename;
            $upload_path = 'images/' . $new_filename;
            
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_path)) {
                // Resize image
                list($width, $height) = getimagesize($upload_path);
                $new_width = 300;
                $new_height = 400;
                
                $image_p = imagecreatetruecolor($new_width, $new_height);
                
                if ($filetype == 'jpg' || $filetype == 'jpeg') {
                    $image = imagecreatefromjpeg($upload_path);
                } elseif ($filetype == 'png') {
                    $image = imagecreatefrompng($upload_path);
                } else {
                    $image = imagecreatefromgif($upload_path);
                }
                
                imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                
                if ($filetype == 'jpg' || $filetype == 'jpeg') {
                    imagejpeg($image_p, $upload_path, 90);
                } elseif ($filetype == 'png') {
                    imagepng($image_p, $upload_path);
                } else {
                    imagegif($image_p, $upload_path);
                }
                
                imagedestroy($image);
                imagedestroy($image_p);
                
                $foto = $new_filename;
            }
        }
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO siswa (nis, nama, jenis_kelamin, telp, alamat, foto) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nis, $nama, $jenis_kelamin, $telp, $alamat, $foto]);
        header("Location: index.php?msg=success");
        exit;
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Edit Data
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nis = $_POST['nis'];
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];
    $foto_lama = $_POST['foto_lama'];
    
    $foto = $foto_lama;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['foto']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($filetype), $allowed)) {
            $new_filename = time() . '_' . $filename;
            $upload_path = 'images/' . $new_filename;
            
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_path)) {
                // Resize image
                list($width, $height) = getimagesize($upload_path);
                $new_width = 300;
                $new_height = 400;
                
                $image_p = imagecreatetruecolor($new_width, $new_height);
                
                if ($filetype == 'jpg' || $filetype == 'jpeg') {
                    $image = imagecreatefromjpeg($upload_path);
                } elseif ($filetype == 'png') {
                    $image = imagecreatefrompng($upload_path);
                } else {
                    $image = imagecreatefromgif($upload_path);
                }
                
                imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                
                if ($filetype == 'jpg' || $filetype == 'jpeg') {
                    imagejpeg($image_p, $upload_path, 90);
                } elseif ($filetype == 'png') {
                    imagepng($image_p, $upload_path);
                } else {
                    imagegif($image_p, $upload_path);
                }
                
                imagedestroy($image);
                imagedestroy($image_p);
                
                // Hapus foto lama
                if ($foto_lama != 'default.jpg' && file_exists('images/' . $foto_lama)) {
                    unlink('images/' . $foto_lama);
                }
                
                $foto = $new_filename;
            }
        }
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE siswa SET nis=?, nama=?, jenis_kelamin=?, telp=?, alamat=?, foto=? WHERE id=?");
        $stmt->execute([$nis, $nama, $jenis_kelamin, $telp, $alamat, $foto, $id]);
        header("Location: index.php?msg=updated");
        exit;
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Hapus Data
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    // Get foto untuk dihapus
    $stmt = $pdo->prepare("SELECT foto FROM siswa WHERE id = ?");
    $stmt->execute([$id]);
    $siswa = $stmt->fetch();
    
    if ($siswa && $siswa['foto'] != 'default.jpg' && file_exists('images/' . $siswa['foto'])) {
        unlink('images/' . $siswa['foto']);
    }
    
    $stmt = $pdo->prepare("DELETE FROM siswa WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: index.php?msg=deleted");
    exit;
}

// Ambil semua data siswa
$stmt = $pdo->query("SELECT * FROM siswa ORDER BY id DESC");
$data_siswa = $stmt->fetchAll();

// Ambil data untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM siswa WHERE id = ?");
    $stmt->execute([$id]);
    $edit_data = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Biodata Siswa</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Data Siswa</h1>
        
        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-<?php echo $_GET['msg'] == 'success' ? 'success' : ($_GET['msg'] == 'updated' ? 'info' : 'danger'); ?>">
                <?php 
                    if ($_GET['msg'] == 'success') echo 'Data berhasil ditambahkan!';
                    elseif ($_GET['msg'] == 'updated') echo 'Data berhasil diupdate!';
                    elseif ($_GET['msg'] == 'deleted') echo 'Data berhasil dihapus!';
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <!-- Form Tambah/Edit -->
        <div class="form-container">
            <h2><?php echo $edit_data ? 'Edit Data Siswa' : 'Tambah Data Siswa'; ?></h2>
            <form method="POST" enctype="multipart/form-data">
                <?php if ($edit_data): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
                    <input type="hidden" name="foto_lama" value="<?php echo $edit_data['foto']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="nis">NIS</label>
                    <input type="text" id="nis" name="nis" required value="<?php echo $edit_data ? $edit_data['nis'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" required value="<?php echo $edit_data ? $edit_data['nama'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <div class="radio-group">
                        <label>
                            <input type="radio" name="jenis_kelamin" value="Laki-laki" <?php echo ($edit_data && $edit_data['jenis_kelamin'] == 'Laki-laki') ? 'checked' : ''; ?> required>
                            Laki-laki
                        </label>
                        <label>
                            <input type="radio" name="jenis_kelamin" value="Perempuan" <?php echo ($edit_data && $edit_data['jenis_kelamin'] == 'Perempuan') ? 'checked' : ''; ?> required>
                            Perempuan
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="telp">Telepon</label>
                    <input type="text" id="telp" name="telp" value="<?php echo $edit_data ? $edit_data['telp'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="3"><?php echo $edit_data ? $edit_data['alamat'] : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="foto">Foto (ukuran 3 X 4)</label>
                    <?php if ($edit_data && $edit_data['foto'] != 'default.jpg'): ?>
                        <div class="current-photo">
                            <img src="images/<?php echo $edit_data['foto']; ?>" alt="Foto saat ini" width="150">
                        </div>
                    <?php endif; ?>
                    <input type="file" id="foto" name="foto" accept="image/*">
                    <small>Format: JPG, JPEG, PNG, GIF. Gambar akan otomatis diresize ke ukuran 3x4 (300x400px)</small>
                </div>
                
                <div class="form-actions">
                    <?php if ($edit_data): ?>
                        <button type="submit" name="update" class="btn btn-primary">Update</button>
                        <a href="index.php" class="btn btn-secondary">Batal</a>
                    <?php else: ?>
                        <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                        <button type="reset" class="btn btn-secondary">Batal</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        
        <!-- Tabel Data Siswa -->
        <div class="table-container">
            <h2>Daftar Siswa</h2>
            <table>
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($data_siswa) > 0): ?>
                        <?php foreach ($data_siswa as $siswa): ?>
                            <tr>
                                <td>
                                    <img src="images/<?php echo $siswa['foto']; ?>" alt="Foto" class="foto-siswa" width="60">
                                </td>
                                <td><?php echo $siswa['nis']; ?></td>
                                <td><?php echo $siswa['nama']; ?></td>
                                <td><?php echo $siswa['jenis_kelamin']; ?></td>
                                <td><?php echo $siswa['telp']; ?></td>
                                <td><?php echo $siswa['alamat']; ?></td>
                                <td>
                                    <a href="index.php?edit=<?php echo $siswa['id']; ?>" class="btn btn-edit">Edit</a>
                                    <a href="index.php?hapus=<?php echo $siswa['id']; ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data siswa</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
