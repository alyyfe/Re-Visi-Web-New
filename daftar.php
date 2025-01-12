<?php
// Mulai session
session_start();

// Include koneksi database
include 'database/koneksi.php';

// Proses form registrasi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form dan validasi
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Format email tidak valid!');</script>";
    } elseif ($password === $confirm_password) {
        // Hash password sebelum disimpan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Gunakan prepared statement untuk menghindari SQL injection
        $stmt = $conn->prepare("INSERT INTO users (username, nama, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $nama, $email, $hashed_password);

        if ($stmt->execute()) {
            // Jika berhasil
            echo "<script>alert('Registrasi berhasil!'); window.location.href='login.php';</script>";
            exit();  // Pastikan script berhenti setelah redirect
        } else {
            // Jika gagal
            echo "<script>alert('Terjadi kesalahan saat registrasi!');</script>";
        }

        // Menutup prepared statement
        $stmt->close();
    } else {
        // Jika password tidak cocok
        echo "<script>alert('Password dan konfirmasi password tidak cocok!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReVisi: Tempat Berbagi Desain Kreatif</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link rel="icon" href="assets/image/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css">
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
    <section id="signup-section" style="background-image: url('https://i.ibb.co.com/j8qN98h/bgsignup.png'); background-size: cover; background-position: center; height: 100vh;">
        <div class="signup-container">
            <div class="window-buttons">
                <span class="window-button close"></span>
                <span class="window-button minimize"></span>
                <span class="window-button maximize"></span>
            </div>
            <div class="signup-header">
                <a href="index.php" class="logo" id="logologin">ReVisi</a>
                <h2>Sign Up</h2>
                <p>Daftar dulu lek</p>
            </div>

            <form method="POST">
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="@username" required>
                </div>

                <div class="input-group">
                    <label for="username">Nama</label>
                    <input type="text" id="nama" name="nama" placeholder="Nama Lengkap" required>
                </div>

                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="halo@gmail.com" required>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" maxlength="10" required>
                </div>

                <div class="input-group">
                    <label for="confirm-password">Konfirmasi Password</label>
                    <input type="password" id="confirm-password" name="confirm-password" maxlength="10" required>
                </div>

                <button type="submit" class="signup-button">Daftar</button>
            </form>

            <div class="signup-footer">
                <p>Sudah punya akun? <a href="login.php">Login sekarang</a></p>
            </div>
        </div>
    </section>

    <script>
        feather.replace();
    </script>
    <script src="js/script.js"></script>
</body>
</html>
