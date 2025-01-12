<?php
// Mulai session untuk menyimpan data pengguna setelah login
session_start();

// Include koneksi database
include 'database/koneksi.php';

// Proses form login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form login
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mencari user berdasarkan username
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Ambil data user dari database
        $row = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            // Password cocok, login berhasil
            // Menyimpan ID pengguna dan data lainnya dalam session
            $_SESSION['user_id'] = $row['id_user'];      // ID pengguna
            $_SESSION['username'] = $row['username'];    // Username pengguna
            $_SESSION['email'] = $row['email'];          // Email pengguna (misalnya)
            $_SESSION['nama_lengkap'] = $row['nama'];    // Nama lengkap pengguna (misalnya)

            // Redirect ke halaman yang sesuai setelah login berhasil
            header("Location: submission/html/"); // Ganti dengan halaman tujuan setelah login
            exit();
        } else {
            echo "<script>alert('Password salah!');</script>";
        }
    } else {
        echo "<script>alert('Username tidak ditemukan!');</script>";
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
    <section id="login-section" style="background-image: url('https://i.ibb.co.com/mbxJFvw/uhuy.png'); background-size: cover; background-position: center; height: 100vh;">
        <div class="login-container">
            <div class="window-buttons">
                <span class="window-button close"></span>
                <span class="window-button minimize"></span>
                <span class="window-button maximize"></span>
            </div>
            <div class="login-header">
                <a href="index.php" class="logo" id="logologin">ReVisi</a>
                <h2>Login</h2>
                <p>Loginkan dulu lek</p>
            </div>

            <form method="POST">
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required maxlength="10">
                </div>

                <div class="forgot-password">
                    <a href="#">Lupa Password</a>
                </div>

                <button type="submit" class="login-button">Login</button>
            </form>

            <div class="login-footer">
                <p>Belum punya akun? <a href="daftar.php">Daftar sekarang</a></p>
            </div>
        </div>
    </section>

    <script>
        feather.replace();
    </script>

    <script src="js/script.js"></script>
</body>

</html>
