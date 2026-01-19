<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Mengambil jumlah data untuk statistik
$jml_buku = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM buku"));
$jml_pinjam = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE status='Dipinjam'"));
$jml_user = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM users WHERE role='user'"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard_admin.css">
</head>
<body>

    <header class="header-admin">
        <h1>🚀 Panel Admin Perpustakaan</h1>
        <div class="admin-profile">
            <span>Halo, <strong><?php echo $_SESSION['nama']; ?></strong></span>
            <a href="logout.php" class="btn-logout">Keluar</a>
        </div>
    </header>

    <div class="stats-container">
        <div class="stat-card">
            <div class="icon">📚</div>
            <div class="stat-info">
                <h3><?php echo $jml_buku; ?></h3>
                <p>Total Koleksi Buku</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="icon">📖</div>
            <div class="stat-info">
                <h3><?php echo $jml_pinjam; ?></h3>
                <p>Buku Sedang Dipinjam</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="icon">👥</div>
            <div class="stat-info">
                <h3><?php echo $jml_user; ?></h3>
                <p>Anggota Aktif</p>
            </div>
        </div>
    </div>

    <div class="menu-title">Navigasi Manajemen</div>

    <div class="menu-grid">
        <a href="kelola_buku.php" class="menu-item">
            <div class="icon-box">📚</div>
            <h2>Kelola Buku</h2>
            <p>Tambah, edit, dan hapus koleksi buku perpustakaan.</p>
        </a>
        <a href="peminjaman.php" class="menu-item">
            <div class="icon-box">📋</div>
            <h2>Peminjaman</h2>
            <p>Pantau status pinjaman dan proses pengembalian buku.</p>
        </a>
        <a href="tambah_user.php" class="menu-item">
            <div class="icon-box">👤</div>
            <h2>Manajemen User</h2>
            <p>Daftarkan petugas admin atau anggota baru.</p>
        </a>
    </div>

    <div class="footer">
        &copy; 2026 Perpustakaan Digital - Dashboard Admin
    </div>

</body>
</html>