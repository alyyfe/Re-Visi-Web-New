<?php
session_start();
require_once '../../database/koneksi.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit();
}

// Ambil data pengguna yang sedang login
$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Set nilai default variabel untuk menghindari error
$nama = $user['nama'] ?? '';
$email = $user['email'] ?? '';
$bio = $user['bio'] ?? '';
$foto_profil = $user['foto_profil'] ?? '../assets/img/avatars/dragon.gif'; // Default foto profil

// Variabel untuk pesan sukses atau error
$success = '';
$error = '';

// Menangani permintaan POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Direktori tempat menyimpan foto profil
  $upload_dir = '../../uploads/foto_profil/';

  // Cek apakah direktori ada, jika tidak buat
  if (!is_dir($upload_dir)) {
      mkdir($upload_dir, 0777, true);
  }

  // Periksa apakah file diunggah
  if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] === UPLOAD_ERR_OK) {
      // Nama file unik
      $file_name = uniqid() . '-' . basename($_FILES['foto_profil']['name']);
      $target_file = $upload_dir . $file_name;

      // Validasi file (contoh: hanya gambar)
      $file_type = mime_content_type($_FILES['foto_profil']['tmp_name']);
      if (in_array($file_type, ['image/jpeg', 'image/png', 'image/gif'])) {
          if (move_uploaded_file($_FILES['foto_profil']['tmp_name'], $target_file)) {
              $foto_profil = $target_file; // Simpan path file ke database
          } else {
              $error = "Gagal mengunggah foto.";
          }
      } else {
          $error = "Format file tidak valid. Hanya diperbolehkan JPG, PNG, atau GIF.";
      }
  }

  // Periksa apakah reset foto profil dilakukan
  if (isset($_POST['reset_foto_profil'])) {
      $foto_profil = '../assets/img/avatars/dragon.gif'; // Gambar default
  }

  // Perbarui data lainnya
  $nama = $_POST['nama'] ?? $nama;
  $bio = $_POST['bio'] ?? $bio;
  $email = $_POST['email'] ?? $email;
  $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;

  // Update database
  $sql_update = "UPDATE users SET nama = ?, bio = ?, email = ?, foto_profil = ?" . ($password ? ", password = ?" : "") . " WHERE username = ?";
  $stmt_update = mysqli_prepare($conn, $sql_update);

  if ($password) {
      mysqli_stmt_bind_param($stmt_update, "ssssss", $nama, $bio, $email, $foto_profil, $password, $username);
  } else {
      mysqli_stmt_bind_param($stmt_update, "sssss", $nama, $bio, $email, $foto_profil, $username);
  }

  if (mysqli_stmt_execute($stmt_update)) {
      $success = "Profil berhasil diperbarui.";
  } else {
      $error = "Terjadi kesalahan saat memperbarui profil.";
  }
}

?>



<!doctype html>

<html
  lang="en"
  class="light-style layout-menu-fixed layout-compact"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
  data-style="light">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>REVISI - SETTING AKUN</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet" />

    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
           <!-- Menu -->

           <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo">
        <a href="index.php" class="app-brand-link" style="display: flex; justify-content: center; align-items: center; text-decoration: none; height: 100vh;">

<span class="fw-normal ms-2" style="font-family: 'Pacifico', cursive; font-size: 30px; color: #6202FE;">Re-visi</span>

</a>


          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
          </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
          <!-- Dashboards -->
          <li class="menu-item ">
            <a href="index.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-home-smile"></i>
              <div class="text-truncate" data-i18n="Dashboard">Dashboards</div>
            </a>
          </li>

          <!-- Forms & Tables -->
          <li class="menu-header small text-uppercase"><span class="menu-header-text">Submit</span></li>


          <!-- FORMS -->
          <li class="menu-item">
            <a href="uploadkarya.php" class="menu-link">
              <i class="menu-icon tf-icons bx bxs-hot"></i>
              <div class="text-truncate" data-i18n="upload">Upload Karyamu</div>
            </a>
          </li>
          </li>

          <li class="menu-item">
            <a href="karyaku.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-happy-heart-eyes"></i>
              <div class="text-truncate" data-i18n="upload">Karyaku</div>
            </a>
       
            <li class="menu-item active">
            <a href="pengaturanakun.php" class="menu-link">
            <i class="menu-icon tf-icons bx bxs-cog"></i>
              <div class="text-truncate" data-i18n="upload">Pengaturan Akun</div>
            </a>

          </li>
          </li>
          </li>

        </ul>
      </aside>
      <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar">
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                <i class="bx bx-menu bx-md"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <!-- Search -->
              <div class="navbar-nav align-items-center">
                <div class="nav-item d-flex align-items-center">
                  <i class="bx bx-search bx-md"></i>
                  <input
                    type="text"
                    class="form-control border-0 shadow-none ps-1 ps-sm-2"
                    placeholder="Search..."
                    aria-label="Search..." />
                </div>
              </div>
              <!-- /Search -->

              <ul class="navbar-nav flex-row align-items-center ms-auto">
      

              <!-- User -->
              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
        
                  <div class="avatar avatar-online">
                    <img src="<?= htmlspecialchars($user['foto_profil'] ?: '../assets/img/avatars/dragon.gif') ?>" alt class="w-px-40 h-px-40 rounded-circle" />
                  </div>
                  
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                          <div class="avatar avatar-online">
                            <img src="<?= htmlspecialchars($user['foto_profil'] ?: '../assets/img/avatars/dragon.gif') ?>" alt class="w-px-40 h-px-40 rounded-circle" />
                          </div>
                        </div>
                        <div class="flex-grow-1">
                          <h6 class="mb-0"><?php echo htmlspecialchars($username); ?></h6>
                          <small class="text-muted">USER</small>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <div class="dropdown-divider my-1"></div>
                  </li>
                  <li>
  
                  </li>
                  <li>
                    <a class="dropdown-item" href="pengaturanakun.php"> <i class="bx bx-user bx-md me-3"></i><span>Profile Settings</span> </a>
                  </li>
                
                  <li>
                    <div class="dropdown-divider my-1"></div>
                  </li>
                  <li>
                  <a class="dropdown-item" href="logout.php">
                     <i class="bx bx-power-off bx-md me-3"></i><span>Log Out</span>
                  </a>

                  </li>
                </ul>
              </li>
              <!--/ User -->
              </ul>
            </div>
          </nav>

          <!-- / Navbar -->

      <!-- Content wrapper -->
      <div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-align-top">
                    <ul class="nav nav-pills flex-column flex-md-row mb-6">
                        <li class="nav-item">
                            <a class="nav-link active" href="javascript:void(0);">
                                <i class="bx bx-sm bx-user me-1_5"></i> Account
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card mb-6">
                    <div class="card-body">
                        <!-- Success/Error Messages -->
                        <?php if ($success): ?>
                            <div class="alert alert-success mt-2"><?= htmlspecialchars($success) ?></div>
                        <?php elseif ($error): ?>
                            <div class="alert alert-danger mt-2"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>

                        <form id="formAccountSettings" method="POST" enctype="multipart/form-data">
                          <!-- User Avatar -->
                          <div class="d-flex align-items-start align-items-sm-center gap-6 pb-4 border-bottom">
                              <img
                                  src="<?= htmlspecialchars($user['foto_profil'] ?: '../assets/img/avatars/dragon.gif') ?>"
                                  alt="user-avatar"
                                  class="d-block w-px-100 h-px-100 rounded"
                                  id="uploadedAvatar" />
                              <div class="button-wrapper">
                                  <label for="upload" class="btn btn-primary me-3 mb-4" tabindex="0">
                                      <span class="d-none d-sm-block">Upload new photo</span>
                                      <i class="bx bx-upload d-block d-sm-none"></i>
                                      <input type="file" name="foto_profil" id="upload" class="account-file-input" hidden accept="image/png, image/jpeg" />
                                  </label>
                                  <button type="submit" name="reset_foto_profil" class="btn btn-outline-secondary account-image-reset mb-4">
                                      <i class="bx bx-reset d-block d-sm-none"></i>
                                      <span class="d-none d-sm-block">Reset</span>
                                  </button>
                                  <div>Allowed JPG, GIF or PNG. Max size of 800K</div>
                              </div>
                          </div>

                          <!-- Account Information Form -->
                          <div class="row g-3 mt-4">
                              <div class="col-md-6">
                                  <label for="username" class="form-label">Username</label>
                                  <input class="form-control" type="text" id="username" name="username" value="<?= htmlspecialchars($username); ?>" readonly />
                              </div>
                              <div class="col-md-6">
                                  <label for="nama" class="form-label">Full Name</label>
                                  <input class="form-control" type="text" name="nama" id="nama" value="<?= htmlspecialchars($nama); ?>" required />
                              </div>
                              <div class="col-md-6">
                                  <label for="bio" class="form-label">Input Bio Mu</label>
                                  <input class="form-control" type="text" name="bio" id="bio" value="<?= htmlspecialchars($bio); ?>" required />
                              </div>
                              <div class="col-md-6">
                                  <label for="email" class="form-label">E-mail</label>
                                  <input class="form-control" type="email" id="email" name="email" value="<?= htmlspecialchars($email); ?>" required />
                              </div>
                              <div class="col-md-6">
                                  <label for="password" class="form-label">Password</label>
                                  <input class="form-control" type="password" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah" />
                              </div>
                          </div>

                          <!-- Display Notification -->
                          <?php if (isset($success) && !empty($success)): ?>
                              <div class="alert alert-success mt-3"><?= htmlspecialchars($success); ?></div>
                          <?php endif; ?>
                          <?php if (isset($error) && !empty($error)): ?>
                              <div class="alert alert-danger mt-3"><?= htmlspecialchars($error); ?></div>
                          <?php endif; ?>

                          <!-- Save Changes Button -->
                          <div class="mt-4 text-end">
                              <button type="submit" class="btn btn-primary me-3">Save changes</button>
                          </div>
                      </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


                  
            <!-- / Content -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl">
                <div
                  class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                
                 
                </div>
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../assets/js/pages-account-settings-account.js"></script>

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
