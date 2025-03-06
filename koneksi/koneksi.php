<?php
// Konfigurasi database
$host = "localhost"; // Ganti dengan host database Anda (default: localhost)
$user = "root";      // Ganti dengan username database Anda
$pass = "";          // Ganti dengan password database Anda
$db   = "galleryy";   // Nama database Anda (sesuai file .sql)

$conn = mysqli_connect($host, $user, $pass, $db);

// Periksa koneksi
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>