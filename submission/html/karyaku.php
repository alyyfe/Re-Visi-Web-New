<?php
// Memulai sesi
session_start();

// Memeriksa apakah user sudah login
if (!isset($_SESSION['user_id'])) {
  // Jika belum login, redirect ke halaman login
  header('Location: login.php');
  exit();
}

// Menyimpan ID user yang sedang login
$user_id = $_SESSION['user_id'];

// Koneksi ke database
include('../../database/koneksi.php');

// Memeriksa apakah username ada di session
if (!isset($_SESSION['username'])) {
    echo "Username tidak tersedia.";
    exit();
}

$username = $_SESSION['username'];

// Query untuk mengambil data pengguna yang sedang login
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Menyimpan foto profil, dengan fallback jika tidak ada
$foto_profil = $user['foto_profil'] ?? '../assets/img/avatars/dragon.gif'; // Default foto profil


// Query untuk mengambil data karya
$sql = "SELECT 
            karya.id_karya, 
            karya.judul_karya, 
            karya.thumbnail, 
            users.foto_profil,
            users.username, 
            kategori.nama_kategori 
        FROM 
            karya 
        JOIN 
            users ON karya.id_user = users.id_user 
        LEFT JOIN 
            kategori ON karya.id_kategori = kategori.id_kategori
        WHERE 
            karya.id_user = ?"; // Filter berdasarkan ID user

// Menyiapkan statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // Mengikat ID user ke parameter
$stmt->execute();
$result = $stmt->get_result();

// Mengecek apakah ada karya yang ditemukan
if (mysqli_num_rows($result) === 0) {
    echo "Tidak ada karya yang ditemukan.";
}

// Menutup statement
$stmt->close();
?>

<!doctype html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
  data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title>REVISI - DASHBORD </title>
  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

  <!-- Core CSS -->
  <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="../assets/css/demo.css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

  <!-- Helpers -->
  <script src="../assets/vendor/js/helpers.js"></script>
  <script src="../assets/js/config.js"></script>

  <!-- jQuery (Required for AJAX) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <script>
    // Fungsi AJAX untuk menghapus karya
    function deleteKarya(karya_id) {
      if (confirm('Apakah Anda yakin ingin menghapus karya ini?')) {
        $.ajax({
          url: 'delete_karya.php', // Ganti dengan path ke file penghapusan karya
          type: 'POST',
          data: { delete: karya_id }, // Mengirim ID karya
          success: function(response) {
            alert(response); // Menampilkan pesan sukses
            $('#karya-' + karya_id).remove(); // Menghapus baris karya dari tampilan
          },
          error: function() {
            alert('Gagal menghapus karya.');
          }
        });
      }
    }
  </script>

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

          <li class="menu-item active">
            <a href="karyaku.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-happy-heart-eyes"></i>
              <div class="text-truncate" data-i18n="upload">Karyaku</div>
            </a>

            <li class="menu-item ">
            <a href="pengaturanakun.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx bxs-cog"></i>
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
                <input type="text" class="form-control border-0 shadow-none ps-1 ps-sm-2" placeholder="Search..."
                  aria-label="Search..." />
              </div>
            </div>
            <!-- /Search -->

            <ul class="navbar-nav flex-row align-items-center ms-auto">
              <!-- Place this tag where you want the button to render. -->


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


        <div class="content-wrapper">
          <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4">Daftar Karya Punya q</h4>
            <div class="row">
              <div class="col-md-12 col-lg-12 col-12">
                <div class="card">
                  <div class="card-header">
                    <h5 class="mb-0">Edit Karya</h5>
                    
                  </div>
                  
                  <div class="table-responsive text-nowrap">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Judul Karya</th>
                          <th>Thumbnail</th>
                          <th>Kategori</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
  <?php
  $no = 1;
  while ($row = $result->fetch_assoc()) {
  ?>
    <tr id="karya-<?php echo $row['id_karya']; ?>">
      <td><?php echo $no++; ?></td>
      <td><?php echo $row['judul_karya']; ?></td>
      <td><img src="uploads/thumbnails/<?php echo htmlspecialchars($row['thumbnail']); ?>" alt="Thumbnail" width="100" height="90" style="border-radius: 10px;"></td>
      <td><?php echo $row['nama_kategori']; ?></td>
      <td>
        <a href="editkarya.php?id_karya=<?php echo $row['id_karya']; ?>" class="btn btn-primary">Edit</a>
        <button class="btn btn-danger" onclick="deleteKarya(<?php echo $row['id_karya']; ?>)">Hapus</button>
      </td>
    </tr>
  <?php } ?>
</tbody>

                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->

  <script src="../assets/vendor/libs/jquery/jquery.js"></script>
  <script src="../assets/vendor/libs/popper/popper.js"></script>
  <script src="../assets/vendor/js/bootstrap.js"></script>
  <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="../assets/vendor/js/menu.js"></script>

  <!-- endbuild -->

  <!-- Vendors JS -->
  <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>

  <!-- Main JS -->
  <script src="../assets/js/main.js"></script>

  <!-- Page JS -->
  <script src="../assets/js/dashboards-analytics.js"></script>

  <!-- Place this tag before closing body tag for github widget button. -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>