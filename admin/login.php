<?php
// Mulai sesi
session_start();

// Koneksi ke database
include('../database/koneksi.php');

// Proses login jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string(trim($_POST['username']));
    $password = trim($_POST['password']);

    // Validasi input
    if (empty($username) || empty($password)) {
        $error_message = "Username atau password tidak boleh kosong.";
    } else {
        // Cek username di database
        $sql = "SELECT * FROM admin WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows === 1) {
            $admin = $result->fetch_assoc();

            // Verifikasi password
            if (password_verify($password, $admin['password'])) {
                // Simpan data ke sesi
                $_SESSION['admin_id'] = $admin['id_admin'];
                $_SESSION['admin_username'] = $admin['username']; // Sesuaikan dengan nama session yang digunakan
                $_SESSION['admin_nama'] = $admin['nama_lengkap'];

                // Redirect ke dashboard
                header("Location: index.php");
                exit;
            } else {
                $error_message = "Password salah.";
            }
        } else {
            $error_message = "Username tidak ditemukan.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Re-Visi Admin</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
  
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
</head>

<body style="background-color: #f0f0f0; background-image: url('https://img.freepik.com/free-vector/gradient-blur-pink-blue-abstract-background_53876-117324.jpg'); background-size: cover;">
  <main>
    <div class="container">
      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="index.php" class="logo d-flex align-items-center w-auto">
                  <span class="fw-normal ms-2" style="font-family: 'Pacifico', cursive; font-size: 30px; color: #6202FE;">Re-visi</span>
                </a>
              </div>

              <div class="card mb-3">
                <div class="card-body">
                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Masuk ke akunmu min</h5>
                    <p class="text-center small">Masukkan username sm password buat login</p>
                  </div>

                  <!-- Pesan Error -->
                  <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger text-center" role="alert">
                      <?= htmlspecialchars($error_message) ?>
                    </div>
                  <?php endif; ?>

                  <!-- Form Login -->
                  <form method="POST" class="row g-3 needs-validation" novalidate>
                    <div class="col-12">
                      <label for="yourUsername" class="form-label">Username</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input type="text" name="username" class="form-control" id="yourUsername" required>
                        <div class="invalid-feedback">Please enter your username.</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Password</label>
                      <input type="password" name="password" class="form-control" id="yourPassword" required>
                      <div class="invalid-feedback">Please enter your password!</div>
                    </div>

                    <div class="col-12">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Remember me</label>
                      </div>
                    </div>

                    <div class="col-12">
                    <button class="btn w-100" type="submit" style="background-color: #6202FE; color: white;">Login</button>

                    </div>
                    <!-- <div class="col-12">
                      <p class="small mb-0">Don't have account? <a href="register.php">Create an account</a></p>
                    </div> -->
                  </form>

                </div>
              </div>

            </div>
          </div>
        </div>
      </section>
    </div>
  </main>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>
</body>

</html>
