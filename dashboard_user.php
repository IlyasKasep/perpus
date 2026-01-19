<?php
// Pastikan file koneksi.php ada di folder yang sama
include 'koneksi.php'; 
session_start();

// Proteksi: Hanya user yang boleh masuk
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$nama_user = $_SESSION['nama'];

// Perbaikan Error: Pastikan koneksi tersedia sebelum query
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Ambil ID User
$user_query = mysqli_query($koneksi, "SELECT id_user FROM users WHERE nama_lengkap = '$nama_user'");
$user_data = mysqli_fetch_assoc($user_query);
$id_user = $user_data['id_user'];

// Logika Pencarian
$search_query = "";
if (isset($_POST['cari'])) {
    $keyword = mysqli_real_escape_string($koneksi, $_POST['keyword']);
    $kategori = $_POST['kategori'];
    $filter_kategori = ($kategori != 'Semua Kategori') ? "AND kategori = '$kategori'" : "";
    $search_query = "WHERE (judul LIKE '%$keyword%' OR pengarang LIKE '%$keyword%') $filter_kategori";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard - Perpustakaan Digital</title>
    <link rel="stylesheet" href="user_dashboard.css">
</head>
<body>

    <header class="header-user">
        <div class="logo">📚 <strong>Perpustakaan Digital</strong></div>
        <div class="user-info">
            <span>👤 <?php echo $nama_user; ?></span>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </header>

    <form method="POST" class="search-container">
        <input type="text" name="keyword" placeholder="Cari buku, pengarang, penerbit...">
        <select name="kategori">
            <option>Semua Kategori</option>
            <?php
            $kat_res = mysqli_query($koneksi, "SELECT DISTINCT kategori FROM buku");
            while($kat = mysqli_fetch_assoc($kat_res)) {
                echo "<option value='{$kat['kategori']}'>{$kat['kategori']}</option>";
            }
            ?>
        </select>
        <button type="submit" name="cari" class="btn-cari">🔍 Cari</button>
    </form>

    <div class="section-title">📚 Koleksi Buku Tersedia</div>
    <div class="book-grid">
        <?php
        $buku_res = mysqli_query($koneksi, "SELECT * FROM buku $search_query");
        while($buku = mysqli_fetch_assoc($buku_res)) {
            $stok = $buku['stok']; //
        ?>
            <div class="book-card">
                <div style="font-size: 40px;">📖</div>
                <h4><?php echo $buku['judul']; ?></h4>
                <p><?php echo $buku['pengarang']; ?></p>
                <p>📦 Stok: <?php echo $stok; ?> buku</p>
                
                <form action="proses_pinjam.php" method="POST">
                    <input type="hidden" name="kode_buku" value="<?php echo $buku['kode_buku']; ?>">
                    <button type="submit" name="pinjam" class="btn-pinjam" <?php echo ($stok <= 0) ? 'disabled style="background:gray"' : ''; ?>>
                        <?php echo ($stok <= 0) ? 'Stok Habis' : 'Pinjam Buku'; ?>
                    </button>
                </form>
            </div>
        <?php } ?>
    </div>

    <div class="section-title">📋 Status Buku Yang Dipinjam</div>
    <div class="status-container">
        <table>
            <thead>
                <tr>
                    <th>Judul Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Harus Kembali</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Menampilkan riwayat pinjam
                $pinjam_res = mysqli_query($koneksi, "SELECT p.*, b.judul FROM peminjaman p 
                                                     JOIN buku b ON p.kode_buku = b.kode_buku 
                                                     WHERE p.id_user = '$id_user' ORDER BY p.id_pinjam DESC");
                
                while($p = mysqli_fetch_assoc($pinjam_res)) {
                    $is_kembali = ($p['status'] == 'Kembali');
                ?>
                    <tr>
                        <td><?php echo $p['judul']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($p['tgl_pinjam'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($p['tgl_kembali_seharusnya'])); ?></td>
                        <td>
                            <span class="<?php echo $is_kembali ? 'status-tag-kembali' : 'status-tag'; ?>">
                                <?php echo $p['status']; ?>
                            </span>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>