<?php
include 'koneksi.php';
session_start();

// Proteksi Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_POST['simpan'])) {
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $role     = $_POST['role'];

    // Cek duplikasi username
    $cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
    
    if (mysqli_num_rows($cek_user) > 0) {
        echo "<script>alert('Gagal! Username sudah terdaftar.'); window.location='tambah_user.php';</script>";
    } else {
        $query = "INSERT INTO users (nama_lengkap, username, password, role) 
                  VALUES ('$nama', '$username', '$password', '$role')";
        
        if (mysqli_query($koneksi, $query)) {
            echo "<script>alert('User berhasil ditambahkan!'); window.location='dashboard_admin.php';</script>";
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah User - Admin</title>
    <style>
        /* CSS Dasar sesuai tema Ungu Admin */
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }

        /* Header Ungu sesuai referensi */
        .header-purple {
            background-color: #3b1e7b;
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-purple h2 { margin: 0; font-size: 24px; }

        .btn-kembali {
            background-color: rgba(255,255,255,0.2);
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            transition: 0.3s;
        }

        .btn-kembali:hover { background-color: rgba(255,255,255,0.4); }

        /* Container Kartu */
        .card-container {
            background: white;
            margin: 30px auto;
            padding: 30px;
            max-width: 800px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .card-container h3 {
            margin-top: 0;
            color: #333;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        /* Grid Form */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: bold;
            margin-bottom: 8px;
            color: #555;
        }

        .form-group input, .form-group select {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }

        /* Tombol Simpan dengan Gradasi */
        .btn-tambah {
            grid-column: span 2;
            margin-top: 20px;
            padding: 15px;
            background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }

        .btn-tambah:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 242, 254, 0.4);
        }

        /* Responsive */
        @media (max-width: 600px) {
            .form-grid { grid-template-columns: 1fr; }
            .btn-tambah { grid-column: span 1; }
        }
    </style>
</head>
<body>
    <div class="header-purple">
        <h2>👤 Tambah User Baru</h2>
        <a href="dashboard_admin.php" class="btn-kembali">Kembali</a>
    </div>

    <div class="card-container">
        <h3>Formulir Pendaftaran Akun</h3>
        <form method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" required placeholder="Contoh: Sultan F">
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required placeholder="Contoh: sultan">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="Masukkan password kuat">
                </div>
                <div class="form-group">
                    <label>Role / Jabatan</label>
                    <select name="role">
                        <option value="user">User / Anggota</option>
                        <option value="admin">Admin / Petugas</option>
                    </select>
                </div>
                <button type="submit" name="simpan" class="btn-tambah">Simpan User Sekarang</button>
            </div>
        </form>
    </div>
</body>
</html>