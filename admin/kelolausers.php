<?php
session_start();
require_once '../database/koneksi.php';

// Periksa apakah session admin sudah diatur
if (!isset($_SESSION['admin_nama'])) {
    header("Location: login.php");
    exit();
}

// Ambil data admin yang sedang login
$nama_lengkap = $_SESSION['admin_nama'];
$username = $_SESSION['admin_username'];

// Query untuk mengambil semua data dari tabel users
$query_users = "SELECT id_user, username, nama, email, bio, foto_profil FROM users";
$result_users = $conn->query($query_users);

// Periksa apakah query berhasil
if (!$result_users) {
    die("Error: Gagal mengambil data pengguna. " . $conn->error);
}

// Query untuk mengambil 3 pesan terbaru dari tabel kontak
$sql_messages = "SELECT nama, subjek, pesan, tanggal FROM kontak ORDER BY tanggal DESC LIMIT 3";
$result_messages = $conn->query($sql_messages);

// Periksa apakah query berhasil
if (!$result_messages) {
    die("Error: Gagal mengambil data pesan. " . $conn->error);
}

// Menghitung jumlah pesan yang diterima
$message_count = $result_messages->num_rows;
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

  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">


  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
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

      <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
        <i class="bi bi-bell"></i>
        <span class="badge bg-primary badge-number">4</span>
      </a><!-- End Notification Icon -->

      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
        <li class="dropdown-header">
          You have 4 new notifications
          <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
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
<i class="bi bi-chat-left-text"></i>
<span class="badge bg-success badge-number"><?php echo $message_count; ?></span> <!-- Menampilkan jumlah pesan -->
</a>

<ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
<li class="dropdown-header">
  You have <?php echo $message_count; ?> new messages
  <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
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
      <a class="nav-link collapsed" href="index.php">
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
        <h1>Kelola Pengguna</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Kelola Users</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar Pengguna</h5>
                        <p>Berikut adalah daftar pengguna yang ada di database:</p>

                        <!-- Tabel Users -->
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Username</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Bio</th>
                                    <th>Foto Profil</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                <?php
                $no = 1;

                // Gunakan prepared statements untuk keamanan tambahan
                $stmt = $conn->prepare("SELECT id_user, username, nama, email, bio, foto_profil FROM users");
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$no}</td>
                                <td>" . htmlspecialchars($row['username']) . "</td>
                                <td>" . htmlspecialchars($row['nama']) . "</td>
                                <td>" . htmlspecialchars($row['email']) . "</td>
                                <td>" . htmlspecialchars($row['bio']) . "</td>
                                <td><img src='../uploads/foto_profil/" . htmlspecialchars($row['foto_profil']) . "' alt='Foto Profil' width='60' height='50'></td>
                                <td>
                                    <button class='btn btn-warning' data-bs-toggle='modal' data-bs-target='#editUserModal' 
                                        data-id='" . $row['id_user'] . "'
                                        data-username='" . htmlspecialchars($row['username']) . "'
                                        data-nama='" . htmlspecialchars($row['nama']) . "'
                                        data-email='" . htmlspecialchars($row['email']) . "'
                                        data-bio='" . htmlspecialchars($row['bio']) . "'>
                                        Edit
                                    </button>
                                    <a href='hapus_users.php?id=" . $row['id_user'] . "' class='btn btn-danger'>Hapus</a>
                                </td>
                              </tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='7'>Tidak ada data pengguna.</td></tr>";
                }
                $stmt->close();
                ?>
                </tbody>

                        </table>

                        <!-- Tombol Tambah User -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahUserModal">
                            Tambah User
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Tambah User -->
        <div class="modal fade" id="tambahUserModal" tabindex="-1" aria-labelledby="tambahUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahUserModalLabel">Tambah User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="tambah_user.php" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea class="form-control" id="bio" name="bio" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="foto_profil" class="form-label">Foto Profil</label>
                                <input type="file" class="form-control" id="foto_profil" name="foto_profil" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit User -->
        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="edit_users.php" method="POST">
                        <div class="modal-body">
                            <input type="hidden" id="edit_id_user" name="id_user">
                            <div class="mb-3">
                                <label for="edit_username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="edit_username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="edit_nama" name="nama" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="edit_email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_bio" class="form-label">Bio</label>
                                <textarea class="form-control" id="edit_bio" name="bio" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>



  
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
    document.addEventListener("DOMContentLoaded", () => {
        const editUserModal = document.getElementById('editUserModal');
        editUserModal.addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget; // Tombol yang membuka modal
            const id = button.getAttribute('data-id');
            const username = button.getAttribute('data-username');
            const nama = button.getAttribute('data-nama');
            const email = button.getAttribute('data-email');
            const bio = button.getAttribute('data-bio');

            // Isi form modal dengan data pengguna
            document.getElementById('edit_id_user').value = id;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_bio').value = bio;
        });
    });
</script>


  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>