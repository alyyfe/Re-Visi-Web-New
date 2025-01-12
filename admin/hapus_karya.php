<?php
session_start();
require_once '../database/koneksi.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['admin_nama'])) {  
    header("Location: login.php");
    exit();
}

// Cek jika ID karya ada di URL
if (isset($_GET['id'])) {
    $id_karya = $_GET['id'];

    // Query untuk menghapus karya
    $query = "DELETE FROM karya WHERE id_karya = '$id_karya'";

    if ($conn->query($query)) {
        echo "Karya berhasil dihapus!";
        header("Location: kelolakarya.php"); // Redirect setelah hapus
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    die("ID Karya tidak ditemukan.");
}
?>
