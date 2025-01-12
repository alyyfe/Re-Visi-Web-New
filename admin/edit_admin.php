<?php
include '../database/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_admin = $_POST['id_admin'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $nama_lengkap = $_POST['nama_lengkap'];

    $query = "UPDATE admin SET username='$username', email='$email', nama_lengkap='$nama_lengkap', updated_at=NOW() WHERE id_admin='$id_admin'";
    if (mysqli_query($conn, $query)) {
        header('Location: index.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
