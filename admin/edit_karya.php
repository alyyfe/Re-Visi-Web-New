<?php
session_start();
require_once '../database/koneksi.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['admin_nama'])) {
    header("Location: login.php");
    exit();
}

// Ambil data pengguna yang sedang login dari tabel admin
$nama_lengkap = $_SESSION['admin_nama']; // Ambil nama lengkap dari session
$username = $_SESSION['admin_username']; // Ambil username jika perlu

// Query untuk tabel karya
$query = "SELECT karya.id_karya, users.nama AS nama_pengupload, karya.thumbnail, karya.judul_karya, karya.deskripsi, kategori.nama_kategori 
          FROM karya 
          JOIN users ON karya.id_user = users.id_user 
          JOIN kategori ON karya.id_kategori = kategori.id_kategori";
$result = $conn->query($query);

// Periksa apakah query berhasil
if (!$result) {
    die("Query error (karya): " . $conn->error);
}

// Query untuk daftar pengguna
$user_query = "SELECT id_user, nama FROM users";
$user_result = $conn->query($user_query);

// Periksa apakah query berhasil
if (!$user_result) {
    die("Query error (users): " . $conn->error);
}

// Query untuk daftar kategori
$kategori_query = "SELECT id_kategori, nama_kategori FROM kategori";
$kategori_result = $conn->query($kategori_query);

// Periksa apakah query berhasil
if (!$kategori_result) {
    die("Query error (kategori): " . $conn->error);
}

// Query untuk mengambil 3 pesan terbaru
$sql_messages = "SELECT nama, subjek, pesan, tanggal FROM kontak ORDER BY tanggal DESC LIMIT 3";
$result_messages = $conn->query($sql_messages);

// Periksa apakah query berhasil
if (!$result_messages) {
    die("Query error (messages): " . $conn->error);
}

// Menghitung jumlah pesan yang diterima
$message_count = $result_messages->num_rows;

// Cek jika ID karya ada di URL
if (isset($_GET['id'])) {
    $id_karya = $_GET['id'];

    // Ambil data karya berdasarkan ID
    $query_karya = "SELECT karya.id_karya, users.nama AS nama_pengupload, karya.thumbnail, karya.judul_karya, karya.deskripsi, kategori.nama_kategori, karya.id_kategori
                    FROM karya 
                    JOIN users ON karya.id_user = users.id_user
                    JOIN kategori ON karya.id_kategori = kategori.id_kategori
                    WHERE karya.id_karya = '$id_karya'";
    $result_karya = $conn->query($query_karya);

    if ($result_karya->num_rows > 0) {
        $row = $result_karya->fetch_assoc();
    } else {
        die("Karya tidak ditemukan.");
    }

    // Proses jika form disubmit untuk menyimpan perubahan
    if (isset($_POST['submit'])) {
        $judul_karya = $_POST['judul_karya']; // Ambil judul karya yang baru
        $nama_pengupload = $_POST['nama_pengupload']; // Nama Pengupload (meskipun tidak digunakan di query)
        $kategori = $_POST['kategori'];
        $deskripsi = $_POST['deskripsi']; // Deskripsi karya yang baru
        $thumbnail = $_FILES['thumbnail']['name'];

        // Proses upload thumbnail jika ada
        if ($thumbnail) {
            $target_dir = "../submission/html/uploads/thumbnails/";
            $target_file = $target_dir . basename($thumbnail);

            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $target_file)) {
                // Jika upload berhasil, update thumbnail, deskripsi, id_kategori, dan judul_karya
                $query_update = "UPDATE karya SET judul_karya = '$judul_karya', thumbnail = '$thumbnail', deskripsi = '$deskripsi', id_kategori = '$kategori' WHERE id_karya = '$id_karya'";
            } else {
                die("Upload thumbnail gagal.");
            }
        } else {
            // Jika thumbnail tidak diubah, tetap menggunakan thumbnail lama
            $query_update = "UPDATE karya SET judul_karya = '$judul_karya', deskripsi = '$deskripsi', id_kategori = '$kategori' WHERE id_karya = '$id_karya'";
        }

        if ($conn->query($query_update)) {
            echo "Karya berhasil diperbarui!";
            header("Location: kelolakarya.php"); // Redirect setelah update
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

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
          <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
            <i class="bi bi-person"></i>
            <span>My Profile</span>
          </a>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

        <li>
          <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
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
      <a class="nav-link " href="kelolakarya.php">
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
      <a class="nav-link collapsed" href="account_settings.php">
        <i class="bi bi-gear "></i>
        <span>Pengaturan Akun</span>
      </a>
    </li>

  </ul>

</aside>
<!-- End Sidebar -->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Edit Karya</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Edit_Karya</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Edit Karyamuuuuuu</h5>
          <p>Silakan ubah data pada form berikut dan simpan perubahan:</p>
<!-- Tabel Edit Karya -->
<form action="edit_karya.php?id=<?php echo $id_karya; ?>" method="POST" enctype="multipart/form-data" autocomplete="on">
    <div class="mb-3">
        <label for="judul_karya" class="form-label">Judul Karya:</label>
        <input type="text" id="judul_karya" name="judul_karya" class="form-control" value="<?php echo isset($row['judul_karya']) ? $row['judul_karya'] : ''; ?>" required>
    </div>

    <div class="mb-3">
        <label for="nama_pengupload" class="form-label">Nama Pengupload:</label>
        <input type="text" id="nama_pengupload" name="nama_pengupload" class="form-control" value="<?php echo isset($row['nama_pengupload']) ? $row['nama_pengupload'] : ''; ?>" required>
    </div>

    <div class="mb-3">
        <label for="thumbnail" class="form-label">Thumbnail:</label>
        <input type="file" id="thumbnail" name="thumbnail" class="form-control">
        <p class="mt-2">Thumbnail saat ini:</p>
        <img src="../submission/html/uploads/thumbnails/<?php echo isset($row['thumbnail']) ? $row['thumbnail'] : ''; ?>" alt="Thumbnail" style="max-width: 100px;">
    </div>

    <div class="mb-3">
        <label for="kategori" class="form-label">Kategori:</label>
        <select id="kategori" name="kategori" class="form-select" required autocomplete="on">
            <?php
            // Menampilkan kategori dalam dropdown
            while ($kategori_row = $kategori_result->fetch_assoc()) {
                $selected = isset($row['id_kategori']) && $row['id_kategori'] == $kategori_row['id_kategori'] ? 'selected' : '';
                echo "<option value='{$kategori_row['id_kategori']}' $selected>{$kategori_row['nama_kategori']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi:</label>
        <textarea id="deskripsi" name="deskripsi" class="form-control" rows="4" required autocomplete="on"><?php echo isset($row['deskripsi']) ? $row['deskripsi'] : ''; ?></textarea>
    </div>

    <div class="mb-3">
        <button type="submit" name="submit" class="btn btn-primary">Update Karya</button>
    </div>
</form>

        </div>
      </div>
    </div>
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

<script>
  // Function to filter table based on search input
  function searchTable() {
    let input = document.getElementById('searchInput').value.toLowerCase();
    let rows = document.querySelectorAll('#karyaTable tbody tr');
    rows.forEach(row => {
      let namaPengupload = row.querySelector('.nama-pengupload').innerText.toLowerCase();
      row.style.display = namaPengupload.includes(input) ? '' : 'none';
    });
  }

  // Function to filter table based on selected category
  function filterTable() {
    let selectedKategori = document.getElementById('filterKategori').value.toLowerCase();
    let rows = document.querySelectorAll('#karyaTable tbody tr');
    rows.forEach(row => {
      let kategori = row.querySelector('.kategori').innerText.toLowerCase();
      row.style.display = selectedKategori === '' || kategori === selectedKategori ? '' : 'none';
    });
  }
</script>


  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>