<?php
include 'koneksi.php';
session_start();

// Proteksi Admin: Pastikan hanya admin yang bisa akses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Logika Pengembalian Buku
if (isset($_GET['aksi']) && $_GET['aksi'] == 'kembali') {
    $id = $_GET['id'];
    
    // 1. Ambil kode buku untuk mengembalikan stok
    $q = mysqli_query($koneksi, "SELECT kode_buku FROM peminjaman WHERE id_pinjam = '$id'");
    $data = mysqli_fetch_assoc($q);
    $kode = $data['kode_buku'];

    // 2. Update status jadi Kembali
    mysqli_query($koneksi, "UPDATE peminjaman SET status = 'Kembali', tgl_kembali_asli = NOW() WHERE id_pinjam = '$id'");

    // 3. Tambah stok buku kembali (+1)
    mysqli_query($koneksi, "UPDATE buku SET stok = stok + 1 WHERE kode_buku = '$kode'");

    echo "<script>alert('Buku dikembalikan! Stok telah diperbarui.'); window.location='peminjaman.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Peminjaman - Admin</title>
    <link rel="stylesheet" href="kelola_buku.css"> 
</head>
<body>
    <div class="header-purple">
        <h2>📋 Kelola Peminjaman</h2>
        <a href="dashboard_admin.php" class="btn-kembali">Kembali</a>
    </div>

    <div class="card-container">
        <h3>Data Peminjaman Buku</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama User</th>
                    <th>Kode</th>
                    <th>Judul</th>
                    <th>Tgl Pinjam</th>
                    <th>Harus Kembali</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query JOIN untuk mengambil nama user dan judul buku
                $query = "SELECT p.*, u.nama_lengkap, b.judul 
                          FROM peminjaman p 
                          JOIN users u ON p.id_user = u.id_user 
                          JOIN buku b ON p.kode_buku = b.kode_buku 
                          ORDER BY p.id_pinjam DESC";
                
                $result = mysqli_query($koneksi, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($d = mysqli_fetch_assoc($result)) {
                        $is_kembali = ($d['status'] == 'Kembali');
                        // Tag warna: Oranye untuk Dipinjam, Hijau untuk Kembali
                        $color = $is_kembali ? '#2e7d32' : '#ef6c00';
                        $bg = $is_kembali ? '#e8f5e9' : '#fff3e0';
                ?>
                    <tr>
                        <td><?php echo $d['nama_lengkap']; ?></td>
                        <td><?php echo $d['kode_buku']; ?></td>
                        <td><?php echo $d['judul']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($d['tgl_pinjam'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($d['tgl_kembali_seharusnya'])); ?></td>
                        <td><?php echo ($d['tgl_kembali_asli'] != '0000-00-00') ? date('d/m/Y', strtotime($d['tgl_kembali_asli'])) : '-'; ?></td>
                        <td>
                            <span style="padding: 5px 10px; border-radius: 5px; font-weight: bold; font-size: 12px; background: <?php echo $bg; ?>; color: <?php echo $color; ?>;">
                                <?php echo $d['status']; ?>
                            </span>
                        </td>
                        <td>
                            <?php if (!$is_kembali): ?>
                                <a href="?aksi=kembali&id=<?php echo $d['id_pinjam']; ?>" 
                                   class="btn-tambah" style="background: #4caf50; padding: 5px 10px; text-decoration: none;"
                                   onclick="return confirm('Proses pengembalian buku ini?')">Kembalikan</a>
                            <?php else: ?>
                                <span style="color: gray;">Selesai</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php 
                    } 
                } else {
                    echo "<tr><td colspan='8' style='text-align:center;'>Belum ada data peminjaman.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>