<?php
session_start();
require_once '../database/koneksi.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['admin_nama'])) {  // Pastikan menggunakan session yang benar
  header("Location: login.php");
  exit();
}

// Ambil data pengguna yang sedang login dari tabel admin
$nama_lengkap = $_SESSION['admin_nama']; // Ambil nama lengkap dari session
// Jika ingin menggunakan username untuk validasi lainnya
$username = $_SESSION['admin_username']; // Ambil username jika perlu

// Query untuk menghitung jumlah admin
$sql_admin = "SELECT COUNT(*) AS total_admin FROM admin";
$result_admin = $conn->query($sql_admin);
$row_admin = $result_admin->fetch_assoc();
$total_admin = $row_admin['total_admin'];

// Query untuk menghitung jumlah users
$sql_users = "SELECT COUNT(*) AS total_users FROM users";
$result_users = $conn->query($sql_users);
$row_users = $result_users->fetch_assoc();
$total_users = $row_users['total_users'];

// Query untuk menghitung jumlah karya
$sql_karya = "SELECT COUNT(*) AS total_karya FROM karya";
$result_karya = $conn->query($sql_karya);
$row_karya = $result_karya->fetch_assoc();
$total_karya = $row_karya['total_karya'];

// Query untuk mengambil data kategori dan menghitung jumlah karya di setiap kategori
$sql = "SELECT k.nama_kategori, COUNT(w.id_karya) AS jumlah_karya 
        FROM kategori k
        LEFT JOIN karya w ON k.id_kategori = w.id_kategori
        GROUP BY k.id_kategori";

$result = $conn->query($sql);

// Menyimpan nama kategori dan jumlah karya dalam array
$kategori = [];
$jumlah_karya = [];
while ($row = $result->fetch_assoc()) {
    $kategori[] = $row['nama_kategori'];
    $jumlah_karya[] = $row['jumlah_karya'];
}



// Query untuk mengambil 3 pesan terbaru
$sql_messages = "SELECT nama, subjek, pesan, tanggal FROM kontak ORDER BY tanggal DESC LIMIT 3";
$result_messages = $conn->query($sql_messages);

// Periksa apakah query berhasil
if (!$result_messages) {
    die("Error executing query: " . $conn->error);
}

// Menghitung jumlah pesan yang diterima
$message_count = $result_messages->num_rows;
// Tutup koneksi
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Re-Visi | Admin Dashboard</title>
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

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">


</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
    <a href="index.php" class="logo d-flex align-items-center">

<span class="fw-normal ms-2" style="font-family: 'Pacifico', cursive; font-size: 30px; color: #6202FE;">Re-visi</span>

</a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        <li class="nav-item dropdown">


          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
            <li class="dropdown-header">
              You have 4 new notifications
              <a href="pesan.php"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-exclamation-circle text-warning"></i>
              <div>
                <h4>Lorem Ipsum</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>30 min. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-x-circle text-danger"></i>
              <div>
                <h4>Atque rerum nesciunt</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>1 hr. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-check-circle text-success"></i>
              <div>
                <h4>Sit rerum fuga</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>2 hrs. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-info-circle text-primary"></i>
              <div>
                <h4>Dicta reprehenderit</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>4 hrs. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>
            <li class="dropdown-footer">
              <a href="#">Show all notifications</a>
            </li>

          </ul><!-- End Notification Dropdown Items -->

        </li><!-- End Notification Nav -->

        <li class="nav-item dropdown">
  <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
    <i class="bi bi-bell"></i>
    <span class="badge bg-success badge-number"><?php echo $message_count; ?></span> <!-- Menampilkan jumlah pesan -->
  </a>

  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
    <li class="dropdown-header">
      You have <?php echo $message_count; ?> new messages
      <a href="pesan.php"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
    </li>
    <li><hr class="dropdown-divider"></li>

    <?php
    // Loop untuk menampilkan pesan
    while ($row_message = $result_messages->fetch_assoc()) {
        // Format timestamp untuk menampilkan waktu dalam jam
        $time_ago = time() - strtotime($row_message['tanggal']);
        $hours_ago = round($time_ago / 3600);

        // Menampilkan pesan
        echo '<li class="message-item">
                <a href="#">
                  <img src="assets/img/default.jpg" alt="" class="rounded-circle">
                  <div>
                    <h4>' . htmlspecialchars($row_message['nama']) . '</h4>
                    <p><strong>' . htmlspecialchars($row_message['subjek']) . '</strong></p>
                    <p>' . htmlspecialchars($row_message['pesan']) . '</p>
                    <p>' . $hours_ago . ' hrs ago</p>
                  </div>
                </a>
              </li>';
    }
    ?>

    <li><hr class="dropdown-divider"></li>
    <li class="dropdown-footer">
      <a href="pesan.php">Show all messages</a>
    </li>
  </ul>
</li>
        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="assets/img/default2.jpg" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo htmlspecialchars($nama_lengkap); ?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
          <h6><?php echo htmlspecialchars($nama_lengkap); ?></h6>
            <span>Administrator</span>
          </li>
            <li>
              <hr class="dropdown-divider">
            </li>


            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="pengaturanakun.php">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>


            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
            <a class="dropdown-item d-flex align-items-center" href="logout.php">
              <i class="bi bi-box-arrow-right"></i>
              <span>Sign Out</span>
            </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->
<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link " href="index.php">
        <i class="bi bi-house-door "></i> 
        <span>Dashboard</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="kelolakarya.php">
        <i class="bi bi-clipboard-check "></i>
        <span>Kelola Karya</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="pesan.php">
        <i class="bi bi-chat-dots "></i>
        <span>Pesan Masuk</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#admin-user-dropdown" data-bs-toggle="collapse" href="#">
        <i class="bi bi-people "></i> <!-- Ukuran ikon untuk Kelola Akun -->
        <span>Kelola Akun</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="admin-user-dropdown" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
          <a href="kelolaadmin.php">
            <i class="bi bi-person-badge fs-6"></i> <!-- Ukuran ikon lebih kecil untuk submenu -->
            <span>Kelola Admin</span>
          </a>
        </li>
        <li>
          <a href="kelolausers.php">
            <i class="bi bi-person fs-6"></i> <!-- Ukuran ikon lebih kecil untuk submenu -->
            <span>Kelola Pengguna</span>
          </a>
        </li>
      </ul>
    </li>


    <li class="nav-item">
      <a class="nav-link collapsed" href="pengaturanakun.php">
        <i class="bi bi-gear "></i>
        <span>Pengaturan Akun</span>
      </a>
    </li>

  </ul>

</aside>
<!-- End Sidebar -->
  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-8">
          <div class="row">

    <!-- Admin Card -->
<div class="col-xxl-4 col-md-6">
  <div class="card info-card sales-card">
    <div class="filter">
      <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
        <li class="dropdown-header text-start">
          <h6>Filter</h6>
        </li>
        <li><a class="dropdown-item" href="#">Today</a></li>
        <li><a class="dropdown-item" href="#">This Month</a></li>
        <li><a class="dropdown-item" href="#">This Year</a></li>
      </ul>
    </div>

    <div class="card-body">
      <h5 class="card-title">Admin👑 <span>| Total</span></h5>

      <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
          <i class="bi bi-person-lock"></i>
        </div>
        <div class="ps-3">
          <h6><?php echo $total_admin; ?></h6> <!-- Menampilkan jumlah admin -->
        </div>
      </div>
    </div>
  </div>
</div><!-- End Admin Card -->

<!-- Users Card -->
<div class="col-xxl-4 col-md-6">
  <div class="card info-card revenue-card">
    <div class="filter">
      <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
        <li class="dropdown-header text-start">
          <h6>Filter</h6>
        </li>
        <li><a class="dropdown-item" href="#">Today</a></li>
        <li><a class="dropdown-item" href="#">This Month</a></li>
        <li><a class="dropdown-item" href="#">This Year</a></li>
      </ul>
    </div>

    <div class="card-body">
      <h5 class="card-title">Users🧑‍🤝‍🧑 <span>| Total</span></h5>

      <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
          <i class="bi bi-person"></i>
        </div>
        <div class="ps-3">
          <h6><?php echo $total_users; ?></h6> <!-- Menampilkan jumlah users -->
        </div>
      </div>
    </div>
  </div>
</div><!-- End Users Card -->

<!-- Karya Card -->
<div class="col-xxl-4 col-xl-12">
  <div class="card info-card customers-card">
    <div class="filter">
      <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
        <li class="dropdown-header text-start">
          <h6>Filter</h6>
        </li>
        <li><a class="dropdown-item" href="#">Today</a></li>
        <li><a class="dropdown-item" href="#">This Month</a></li>
        <li><a class="dropdown-item" href="#">This Year</a></li>
      </ul>
    </div>

    <div class="card-body">
      <h5 class="card-title">Karya✨ <span>| Total</span></h5>

      <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
          <i class="bi bi-file-earmark"></i>
        </div>
        <div class="ps-3">
          <h6><?php echo $total_karya; ?></h6> <!-- Menampilkan jumlah karya -->
        </div>
      </div>
    </div>
  </div>
</div><!-- End Karya Card -->


           
            

          </div>
        </div><!-- End Left side columns -->

        <!-- Right side columns -->
        <div class="col-lg-4">

          <!-- Recent Activity -->
          <div class="card">

    <div class="card-body">
      <h5 class="card-title">Jumlah Karya per Kategori 🎨</h5>

      <!-- Doughnut Chart -->
      <canvas id="doughnutChart" style="max-height: 400px;"></canvas>
      <script>
        document.addEventListener("DOMContentLoaded", () => {
          // Data kategori dan jumlah karya dari PHP
          const kategori = <?php echo json_encode($kategori); ?>;
          const jumlahKarya = <?php echo json_encode($jumlah_karya); ?>;

          new Chart(document.querySelector('#doughnutChart'), {
            type: 'doughnut',
            data: {
              labels: kategori, // Nama kategori dari database
              datasets: [{
                label: 'Jumlah Karya',
                data: jumlahKarya, // Jumlah karya per kategori
                backgroundColor: [
                  'rgb(255, 99, 132)',
                  'rgb(54, 162, 235)',
                  'rgb(255, 205, 86)',
                  'rgb(75, 192, 192)',
                  'rgb(153, 102, 255)',
                  'rgb(255, 159, 64)'
                ],
                hoverOffset: 4
              }]
            }
          });
        });
      </script>
      <!-- End Doughnut Chart -->

    </div>
  </div>

            </div>
          </div><!-- End Recent Activity -->

         

          

        </div><!-- End Right side columns -->

      </div>
    </section>

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

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>



  <script>
  // Menunggu hingga dokumen selesai dimuat
  document.addEventListener('DOMContentLoaded', function () {
    // Menangani event saat tombol Edit diklik
    $('#editUserModal').on('show.bs.modal', function (event) {
      // Mendapatkan data dari atribut data-* tombol yang diklik
      var button = $(event.relatedTarget);
      var id = button.data('id');
      var username = button.data('username');
      var nama = button.data('nama');
      var email = button.data('email');
      var bio = button.data('bio');

      // Mengisi form modal dengan data yang diambil
      var modal = $(this);
      modal.find('#edit_id_user').val(id);
      modal.find('#edit_username').val(username);
      modal.find('#edit_nama').val(nama);
      modal.find('#edit_email').val(email);
      modal.find('#edit_bio').val(bio);
    });
  });
</script>




  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>