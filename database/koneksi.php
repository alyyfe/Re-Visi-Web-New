<?php
$servername = "localhost";
$username = "root";  // Default username untuk XAMPP
$password = "";      // Default password untuk XAMPP (kosong)
$dbname = "bismillah"; // Nama database yang sudah dibuat

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

?>
