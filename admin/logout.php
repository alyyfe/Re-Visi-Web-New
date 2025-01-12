<?php
session_start();  // Memulai sesi
session_unset();  // Menghapus semua variabel sesi
session_destroy();  // Menghancurkan sesi

// Mengarahkan pengguna kembali ke halaman login atau homepage
header("Location: login.php");
exit();
?>
