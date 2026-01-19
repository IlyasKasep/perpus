<?php 
include 'koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Buku</title>
    <link rel="stylesheet" href="buku.css">
</head>
<body>
    <div class="container">
        <h2>Daftar Buku</h2>
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th>Penerbit</th>
                    <th>Tahun</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = mysqli_query($koneksi, "SELECT * FROM buku");
                while($data = mysqli_fetch_array($query)) {
                ?>
                <tr>
                    <td><?php echo $data['kode_buku']; ?></td>
                    <td><?php echo $data['judul']; ?></td>
                    <td><?php echo $data['pengarang']; ?></td>
                    <td><?php echo $data['penerbit']; ?></td>
                    <td class="text-center"><?php echo $data['tahun_terbit']; ?></td>
                    <td><?php echo $data['kategori']; ?></td>
                    <td class="text-center"><?php echo $data['stok']; ?></td>
                    <td class="text-center">
                        <a href="hapus.php?id=<?php echo $data['kode_buku']; ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>