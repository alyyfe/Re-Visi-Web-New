<?php
// Mulai session
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Jika tidak login, arahkan ke halaman login
    header('Location: /WEB-REVISI/login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission</title>
</head>
<body>
    <!-- Konten halaman jika sudah login -->
    <h1>Selamat datang di halaman Submission</h1>
</body>
</html>
