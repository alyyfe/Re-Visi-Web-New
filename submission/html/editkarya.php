<?php

// Pastikan koneksi ke database sudah dibuat
include('../../database/koneksi.php');

// Mengecek apakah ada parameter `id_karya`
if (isset($_GET['id_karya'])) {
    $id_karya = $_GET['id_karya'];

    // Query untuk mengambil data karya berdasarkan `id_karya`
    $sql = "SELECT * FROM karya WHERE id_karya = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_karya);
    $stmt->execute();
    $result = $stmt->get_result();

    // Memastikan data karya ditemukan
    if ($result->num_rows > 0) {
        $karya = $result->fetch_assoc();
    } else {
        echo "Karya tidak ditemukan!";
        exit();
    }
} else {
    echo "ID karya tidak diberikan!";
    exit();
}

// Mengecek apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul_karya = $_POST['judul_karya'];
    $id_kategori = $_POST['id_kategori'];
    $link_figma = $_POST['link_figma'];  // Menangkap link Figma dari form

    // Cek apakah ada file thumbnail yang diupload
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        // Menentukan lokasi upload file
        $target_dir = "uploads/thumbnails/";
        $target_file = $target_dir . basename($_FILES["thumbnail"]["name"]);
        
        // Memindahkan file dari temporary directory ke target directory
        if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_file)) {
            $thumbnail = basename($_FILES["thumbnail"]["name"]);
        } else {
            echo "Gagal mengupload file thumbnail!";
            exit();
        }
    } else {
        // Jika tidak ada file yang diupload, gunakan thumbnail lama
        $thumbnail = $_POST['thumbnail_lama'];
    }

    // Query untuk mengupdate data karya, termasuk link Figma
    $sql = "UPDATE karya SET judul_karya = ?, id_kategori = ?, thumbnail = ?, link_figma = ? WHERE id_karya = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $judul_karya, $id_kategori, $thumbnail, $link_figma, $id_karya);

    if ($stmt->execute()) {
        echo "Karya berhasil diperbarui!";
        header("Location: karyaku.php"); // Redirect ke halaman karyaku
        exit();
    } else {
        echo "Gagal memperbarui karya!";
    }
}
?>
<!doctype html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
  data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title>Demo : Cards basic - UI elements | Sneat - Bootstrap Dashboard PRO</title>
  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="../assets/css/demo.css" />

  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

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
          <li class="menu-item active">
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
                    <a class="dropdown-item" href="#">
                      <i class="bx bx-user bx-md me-3"></i><span>My Profile</span>
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="pengaturanakun.php"> <i class="bx bx-cog bx-md me-3"></i><span>Settings</span> </a>
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
        <div class="row">
            <div class="col-md-12 col-lg-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Daftar Karya</h5>
                    </div>
                    <div class="card mb-6">
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="judul_karya" class="form-label">Judul Karya</label>
                                    <input type="text" class="form-control" id="judul_karya" name="judul_karya" value="<?= htmlspecialchars($karya['judul_karya']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="id_kategori" class="form-label">Kategori</label>
                                    <select id="id_kategori" name="id_kategori" class="form-select" required>
                                        <?php
                                        // Ambil data kategori dari tabel kategori
                                        $sql = "SELECT * FROM kategori";
                                        $result = $conn->query($sql);
                                        while ($kategori = $result->fetch_assoc()) {
                                            $selected = $kategori['id_kategori'] == $karya['id_kategori'] ? 'selected' : '';
                                            echo "<option value='{$kategori['id_kategori']}' $selected>{$kategori['nama_kategori']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="link_figma" class="form-label">Link Figma</label>
                                    <input type="url" class="form-control" id="link_figma" name="link_figma" value="<?= htmlspecialchars($karya['link_figma']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="thumbnail" class="form-label">Thumbnail</label>
                                    <input type="file" class="form-control" id="thumbnail" name="thumbnail">
                                    <input type="hidden" name="thumbnail_lama" value="<?= htmlspecialchars($karya['thumbnail']); ?>">
                                    <div class="mt-2">
                                        <p>Pratinjau:</p>
                                        <img src="uploads/thumbnails/<?php echo htmlspecialchars($karya['thumbnail']); ?>" alt="Thumbnail" width="100" height="90" style="border-radius: 10px;">
                                    </div>
                                </div>

                       
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </form>
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