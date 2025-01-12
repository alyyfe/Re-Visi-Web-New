<?php
session_start();
require_once '../../database/koneksi.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit();
}


$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);


$nama = $user['nama'] ?? '';
$email = $user['email'] ?? '';
$bio = $user['bio'] ?? '';
$foto_profil = $user['foto_profil'] ?? '../assets/img/avatars/dragon.gif'; 


$success = '';
$error = '';


?>
<!doctype html>

<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
  data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>REVISI DASHBOARD</title>

  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

  <!-- sweetalert -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.18/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.18/dist/sweetalert2.all.min.js"></script>



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
          <li class="menu-item active">
            <a href="uploadkarya.php" class="menu-link ">
              <i class="menu-icon tf-icons bx bxs-hot "></i>
              <div class="text-truncate " data-i18n="upload">Upload Karyamu</div>
            </a>
          </li>
          </li>

          <li class="menu-item">
            <a href="karyaku.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-happy-heart-eyes"></i>
              <div class="text-truncate" data-i18n="upload">Karyaku</div>
            </a>
       
            <li class="menu-item ">
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
                <input type="text" class="form-control border-0 shadow-none ps-1 ps-sm-2" placeholder="Search..."
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
            <div class="row g-6">

              <div class="col-xl-6">

                <!-- HTML5 Inputs -->
              <!-- HTML5 Inputs -->
<form action="submit.php" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
  <div class="card mb-6">
    <h5 class="card-header">Upload Karyamu Jagoan</h5>
    <div class="card-body">

      <div class="mb-4 row">
        <label for="judul_karya" class="col-md-2 col-form-label">Judul Karya:</label>
        <div class="col-md-10">
          <input type="text" class="form-control" id="judul_karya" name="judul_karya" required>
        </div>
      </div>

      <div class="mb-4 row">
        <label for="deskripsi" class="col-md-2 col-form-label">Deskripsi</label>
        <div class="col-md-10">
          <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
        </div>
      </div>

      <div class="mb-4 row">
        <label for="link_figma" class="col-md-2 col-form-label">Link Figma</label>
        <div class="col-md-10">
          <input type="url" class="form-control" id="link_figma" name="link_figma" placeholder="https://figma.com/..." required>
        </div>
      </div>

      <div class="mb-4 row">
        <label for="kategori" class="col-md-2 col-form-label">Kategori</label>
        <div class="col-md-10">
          <select class="form-control" id="kategori" name="kategori" required>
            <?php
            // Mengambil data kategori dari database
            include('../../database/koneksi.php');
            $result = $conn->query("SELECT id_kategori, nama_kategori FROM kategori");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['id_kategori']}'>{$row['nama_kategori']}</option>";
            }
            ?>
          </select>
        </div>
      </div>

      <div class="mb-4 row">
        <label for="thumbnail" class="col-md-2 col-form-label">Thumbnail</label>
        <div class="col-md-10">
          <input type="file" class="form-control" id="thumbnail" name="thumbnail" required>
        </div>
      </div>

      <div class="mb-4 row">
        <div class="col-md-10 offset-md-2">
          <button class="btn btn-primary" id="submit-button">Kirim</button>
        </div>
      </div>

    </div>
  </div>
</form>

              </div>
            </div>
          </div>
          <!-- / Content -->

          <!-- Footer -->
          <footer class="content-footer footer bg-footer-theme">
            <div class="container-xxl">

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

  <script src="../assets/js/form-basic-inputs.js"></script>

  <!-- Place this tag before closing body tag for github widget button. -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>



</body>

</html>