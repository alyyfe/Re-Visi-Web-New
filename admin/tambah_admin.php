<?php
include '../database/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama_lengkap = $_POST['nama_lengkap'];

    $query = "INSERT INTO admin (username, email, password, nama_lengkap, created_at, updated_at)
              VALUES ('$username', '$email', '$password', '$nama_lengkap', NOW(), NOW())";
    if (mysqli_query($conn, $query)) {
        header('Location: index.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
