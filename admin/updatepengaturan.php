<?php
// Mulai sesi
session_start();
require_once '../database/koneksi.php'; // Pastikan koneksi ke database

// Cek apakah pengguna sudah login
if (!isset($_SESSION['admin_nama'])) {
    header("Location: login.php");
    exit();
}

// Ambil username dari session
$username = $_SESSION['admin_username'];

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $email = $_POST['email'];
    $password = $_POST['password']; // Password bisa kosong jika tidak ingin diubah
    $nama_lengkap = $_POST['nama_lengkap'];

    // Query untuk memperbarui data admin
    if (!empty($password)) {
        // Enkripsi password baru
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE admin SET email = ?, password = ?, nama_lengkap = ? WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $email, $password_hash, $nama_lengkap, $username);
    } else {
        // Update tanpa mengubah password
        $query = "UPDATE admin SET email = ?, nama_lengkap = ? WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $email, $nama_lengkap, $username);
    }

    // Eksekusi query
    if ($stmt->execute()) {
        // Jika berhasil, kembali ke halaman pengaturan akun
        header("Location: pengaturanakun.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Query untuk mengambil data admin berdasarkan username
$query_admin = "SELECT username, email, nama_lengkap FROM admin WHERE username = ?";
$stmt = $conn->prepare($query_admin);
$stmt->bind_param("s", $username);
$stmt->execute();
$result_admin = $stmt->get_result();

// Periksa apakah data admin ditemukan
if ($result_admin->num_rows > 0) {
    // Ambil data admin dari hasil query
    $admin = $result_admin->fetch_assoc();
} else {
    // Jika admin tidak ditemukan
    die("Data admin tidak ditemukan.");
}

?>