<?php
include('../database/koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_POST['id_user'] ?? null;
    $username = $_POST['username'] ?? null;
    $nama = $_POST['nama'] ?? null;
    $email = $_POST['email'] ?? null;
    $bio = $_POST['bio'] ?? null;
    $existing_foto = $_POST['existing_foto'] ?? null;

    // Cek apakah ada file foto baru yang diunggah
    $foto_profil = $_FILES['foto_profil'] ?? null;
    if ($foto_profil && $foto_profil['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../uploads/foto_profil/";
        $target_file = $target_dir . basename($foto_profil['name']);
        move_uploaded_file($foto_profil['tmp_name'], $target_file);
    } else {
        $target_file = $existing_foto;
    }

    // Update data pengguna
    if ($id_user && $username && $nama && $email && $bio) {
        $query = "UPDATE users SET 
                  username = ?, 
                  nama = ?, 
                  email = ?, 
                  bio = ?, 
                  foto_profil = ? 
                  WHERE id_user = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssssi', $username, $nama, $email, $bio, $target_file, $id_user);
        if ($stmt->execute()) {
            header("Location: kelolausers.php?status=success");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Error: Data tidak lengkap.";
    }
}
?>
