<?php
include '../database/koneksi.php'; // File koneksi ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password untuk keamanan
    $bio = $_POST['bio'];
    $foto_profil = '';

    // Proses upload foto profil
    if ($_FILES['foto_profil']['name']) {
        $targetDir = "../uploads/foto_profil/"; // Folder untuk menyimpan file
        $fileName = basename($_FILES['foto_profil']['name']);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Validasi file yang diupload
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES['foto_profil']['tmp_name'], $targetFilePath)) {
                $foto_profil = $fileName;
            } else {
                echo "Gagal mengupload foto profil.";
                exit;
            }
        } else {
            echo "Format file tidak valid.";
            exit;
        }
    }

    // Insert data ke database
    $sql = "INSERT INTO users (username, nama, email, password, bio, foto_profil) 
            VALUES ('$username', '$nama', '$email', '$password', '$bio', '$foto_profil')";

    if (mysqli_query($conn, $sql)) {
        header("Location: kelolausers.php?status=success");
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>
