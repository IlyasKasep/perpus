<?php
include 'koneksi.php';
session_start();

// Cek jika tombol tambah ditekan
if (isset($_POST['tambah'])) {
    $kode      = mysqli_real_escape_string($koneksi, $_POST['kode']);
    $judul     = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $pengarang = mysqli_real_escape_string($koneksi, $_POST['pengarang']);
    $penerbit  = mysqli_real_escape_string($koneksi, $_POST['penerbit']); // Pastikan name="penerbit" ada di HTML
    $tahun     = $_POST['tahun'];
    $kategori  = $_POST['kategori'];
    $stok      = $_POST['stok'];

    // Validasi: Cek apakah kode buku sudah ada agar tidak Fatal Error
    $cek_kode = mysqli_query($koneksi, "SELECT * FROM buku WHERE kode_buku = '$kode'");
    
    if (mysqli_num_rows($cek_kode) > 0) {
        echo "<script>alert('Gagal! Kode Buku $kode sudah ada di database.'); window.location='kelola_buku.php';</script>";
    } else {
        $query = "INSERT INTO buku (kode_buku, judul, pengarang, penerbit, tahun_terbit, kategori, stok) 
                  VALUES ('$kode', '$judul', '$pengarang', '$penerbit', '$tahun', '$kategori', '$stok')";
        
        if (mysqli_query($koneksi, $query)) {
            echo "<script>alert('Buku berhasil ditambahkan!'); window.location='kelola_buku.php';</script>";
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    }
}

// Logika Hapus
if (isset($_GET['hapus'])) {
    $kode = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM buku WHERE kode_buku='$kode'");
    header("Location: kelola_buku.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Buku</title>
    <link rel="stylesheet" href="kelola_buku.css">
</head>
<body>
    <div class="header-purple">
        <h2>📚 Kelola Buku</h2>
        <a href="dashboard_admin.php" class="btn-kembali">Kembali</a>
    </div>

    <div class="card-container">
        <h3>Tambah Buku Baru</h3>
        <form method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label>Kode Buku</label>
                    <input type="text" name="kode" placeholder="Contoh: BK006" required>
                </div>
                <div class="form-group">
                    <label>Judul Buku</label>
                    <input type="text" name="judul" required>
                </div>
                <div class="form-group">
                    <label>Pengarang</label>
                    <input type="text" name="pengarang" required>
                </div>
                <div class="form-group">
                    <label>Penerbit</label>
                    <input type="text" name="penerbit" required>
                </div>
                <div class="form-group">
                    <label>Tahun Terbit</label>
                    <input type="number" name="tahun" required>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori">
                        <option>Fiksi</option>
                        <option>Sejarah</option>
                        <option>Pengembangan Diri</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stok" required>
                </div>
            </div>
            <button type="submit" name="tambah" class="btn-tambah">Tambah Buku</button>
        </form>
    </div>

    <div class="card-container">
        <h3>Daftar Buku</h3>
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th>Penerbit</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $res = mysqli_query($koneksi, "SELECT * FROM buku");
                while ($b = mysqli_fetch_assoc($res)) {
                    echo "<tr>
                            <td>{$b['kode_buku']}</td>
                            <td>{$b['judul']}</td>
                            <td>{$b['pengarang']}</td>
                            <td>{$b['penerbit']}</td>
                            <td>{$b['stok']}</td>
                            <td><a href='?hapus={$b['kode_buku']}' class='btn-hapus' onclick=\"return confirm('Hapus buku ini?')\">Hapus</a></td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>