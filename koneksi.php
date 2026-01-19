<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "perpustakaan_digital";

$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek Koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>