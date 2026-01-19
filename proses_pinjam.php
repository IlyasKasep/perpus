<?php
include 'koneksi.php';
session_start();

if (isset($_POST['pinjam'])) {
    $kode_buku = $_POST['kode_buku'];
    $nama_user = $_SESSION['nama'];
    
    // 1. Ambil ID User
    $u_res = mysqli_query($koneksi, "SELECT id_user FROM users WHERE nama_lengkap = '$nama_user'");
    $u = mysqli_fetch_assoc($u_res);
    $id_user = $u['id_user'];
    
    // 2. LOGIKA BARU: Cek apakah user sedang meminjam buku yang sama dan belum dikembalikan
    $cek_pinjam = mysqli_query($koneksi, "SELECT * FROM peminjaman 
                                         WHERE id_user = '$id_user' 
                                         AND kode_buku = '$kode_buku' 
                                         AND status = 'Dipinjam'");

    if (mysqli_num_rows($cek_pinjam) > 0) {
        // Jika ditemukan transaksi aktif untuk buku yang sama
        echo "<script>
                alert('Gagal! Anda masih meminjam buku ini. Selesaikan pengembalian terlebih dahulu.'); 
                window.location='dashboard_user.php';
              </script>";
        exit();
    }

    $tgl_pinjam = date('Y-m-d');
    $tgl_kembali = date('Y-m-d', strtotime('+7 days')); // Estimasi kembali 7 hari

    // 3. Kurangi stok buku jika stok > 0
    $update_stok = mysqli_query($koneksi, "UPDATE buku SET stok = stok - 1 WHERE kode_buku = '$kode_buku' AND stok > 0");

    if ($update_stok) {
        // 4. Masukkan data ke tabel peminjaman dengan status 'Dipinjam'
        mysqli_query($koneksi, "INSERT INTO peminjaman (id_user, kode_buku, tgl_pinjam, tgl_kembali_seharusnya, status) 
                                VALUES ('$id_user', '$kode_buku', '$tgl_pinjam', '$tgl_kembali', 'Dipinjam')");
        
        echo "<script>alert('Berhasil meminjam buku!'); window.location='dashboard_user.php';</script>";
    } else {
        echo "<script>alert('Gagal! Stok habis.'); window.location='dashboard_user.php';</script>";
    }
}
?>