<?php
// Mulai session dan koneksi ke database
session_start();
require_once '../database/koneksi.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['admin_nama'])) {  // Pastikan menggunakan session yang benar
    header("Location: login.php");
    exit();
}

// Menangani pengiriman form ketika tombol "Simpan" diklik
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $judul_karya = $_POST['judul_karya'];
    $id_user = $_POST['id_user'];
    $id_kategori = $_POST['id_kategori'];
    $link_figma = isset($_POST['link_figma']) ? $_POST['link_figma'] : null;
    $deskripsi = $_POST['deskripsi'];  // Menangani data deskripsi

    // Cek apakah ada file thumbnail yang diupload
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        // Menentukan lokasi upload file thumbnail
        $target_dir = "../submission/html/uploads/thumbnails/";
        $target_file = $target_dir . basename($_FILES["thumbnail"]["name"]);

        // Memindahkan file dari temporary directory ke target directory
        if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_file)) {
            $thumbnail = basename($_FILES["thumbnail"]["name"]);
        } else {
            echo "Gagal mengupload thumbnail!";
            exit();
        }
    } else {
        echo "Thumbnail tidak dipilih!";
        exit();
    }

    // Query untuk menambahkan karya baru (termasuk deskripsi)
    $sql = "INSERT INTO karya (judul_karya, id_user, id_kategori, thumbnail, link_figma, deskripsi) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    // Menyiapkan statement SQL
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siisss", $judul_karya, $id_user, $id_kategori, $thumbnail, $link_figma, $deskripsi);  // Menambahkan deskripsi pada bind_param

    // Mengeksekusi query
    if ($stmt->execute()) {
        echo "Karya berhasil ditambahkan!";
        header("Location: kelolakarya.php"); // Redirect ke halaman kelola karya
        exit();
    } else {
        echo "Gagal menambahkan karya!";
    }
}

// Tutup koneksi setelah operasi selesai
$conn->close();
?>
