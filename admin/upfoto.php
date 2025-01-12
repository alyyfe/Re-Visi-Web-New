<?php
session_start(); // Pastikan session dimulai
include('../database/koneksi.php'); // Pastikan file koneksi database ada dan benar


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $file = $_FILES['profile_picture'];

    // Validasi file (jika diperlukan)
    if ($file['error'] == 0) {
        $uploadDir = 'uploads/adminprofile/';
        $uploadFile = $uploadDir . basename($file['name']);

        // Pindahkan file ke folder upload
        if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
            // Redirect ke dashboard admin (index.php)
            header("Location: index.php");
            exit; // Keluar dari skrip setelah redirect
        } else {
            echo "Terjadi kesalahan saat mengupload file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Pages / Register - NiceAdmin Bootstrap Template</title>
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

<body style="background-color: #f0f0f0; background-image: url('https://img.freepik.com/premium-photo/blurred-abstract-gradient-background-with-noise-textur_391867-3.jpg'); background-repeat: no-repeat; background-size: cover; background-position: center;">

  <main>
    <div class="container">

    <section class="section upload-profile min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

        <div class="d-flex justify-content-center py-4">
          <a href="index.html" class="logo d-flex align-items-center w-auto">
            <span class="fw-normal ms-2" style="font-family: 'Pacifico', cursive; font-size: 30px; color: #6202FE;">Re-visi</span>
          </a>
        </div><!-- End Logo -->

        <div class="card mb-3">

          <div class="card-body">

            <div class="pt-4 pb-2">
              <h5 class="card-title text-center pb-0 fs-4">Upload Profile Picture</h5>
              <p class="text-center small">Upload your profile picture below</p>
            </div>

            <!-- Pesan Error -->
            <?php if (!empty($error_message)): ?>
              <div class="alert alert-danger text-center" role="alert">
                <?= htmlspecialchars($error_message) ?>
              </div>
            <?php endif; ?>

            <!-- Form Upload -->
            <form method="POST" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
              <div class="col-12 text-center">
                <label for="profilePicture" class="form-label">Profile Picture</label>
                <input type="file" name="profile_picture" class="form-control" id="profilePicture" accept="image/*" required onchange="previewImage(event)">
                <div class="invalid-feedback">Please upload your profile picture!</div>
              </div>

              <!-- Preview Gambar -->
              <div class="col-12 text-center">
                <img id="imagePreview" src="#" alt="Image Preview" style="display: none; width: 150px; height: 150px; object-fit: cover; border-radius: 50%; margin: 15px auto;">
              </div>

              <div class="col-12">
                <button class="btn btn-primary w-100" type="submit">Upload Picture</button>
              </div>
            </form>

          </div>
        </div>

      </div>
    </div>
  </div>
</section>

    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <script>
  function previewImage(event) {
    const image = document.getElementById('imagePreview');
    const file = event.target.files[0];
    if (file) {
      image.src = URL.createObjectURL(file);
      image.style.display = 'block';
    } else {
      image.src = "#";
      image.style.display = 'none';
    }
  }
</script>


  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
