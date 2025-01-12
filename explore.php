<?php
// Menghubungkan dengan file koneksi
include('database/koneksi.php');
session_start(); // Pastikan sesi dimulai


// Query untuk mengambil data desain dari tabel
$sql = "SELECT 
            karya.id_karya, 
            karya.judul_karya, 
            karya.thumbnail, 
            users.username, 
            kategori.nama_kategori 
        FROM 
            karya 
        JOIN 
            users ON karya.id_user = users.id_user 
        LEFT JOIN 
            kategori ON karya.id_kategori = kategori.id_kategori";
$result = $conn->query($sql);

// Menyiapkan array untuk mengelompokkan desain berdasarkan kategori
$designs = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $category = $row['nama_kategori'];
        $designs[$category][] = $row;
    }
}

// Urutan kategori yang diinginkan
$desired_order = ['Design', 'Wireframe', 'Ilustrasi'];

// Menyortir array $designs sesuai urutan kategori yang diinginkan
uksort($designs, function ($a, $b) use ($desired_order) {
    $a_index = array_search($a, $desired_order);
    $b_index = array_search($b, $desired_order);
    return $a_index - $b_index;
});
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Re-Visi: Tempat Berbagi Desain Kreatif</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="js" href="">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Roboto+Slab:wght@100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <!-- icon -->
    <link rel="icon" href="assets/image/uhuy.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css">
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body>
  
<header>
    <a href="index.php" class="logo">Re-Visi</a>
    <ul class="menu">
        <a href="index.php" >Beranda</a>
        <a href="explore.php " class="active">Explore</a>
        <a href="leaderboard.php">Leaderboard</a>
        <a href="contact.php">Contact Us</a>
    </ul>

    <!-- icon -->
    <div class="menu-navbar">
        <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
    </div>

    <?php if (isset($_SESSION['username'])): ?>
        <!-- Dropdown saat pengguna sudah login -->
        <div class="dropdown" style="position: relative; display: inline-block;">
            <button class="btn-submission dropdown-toggle" style="background-color: #6202FE; color: white; border: none; padding: 10px 20px; font-size: 16px; cursor: pointer; border-radius: 5px;">
            <i class="ri-arrow-down-s-line"></i> Hi, <?php echo htmlspecialchars($_SESSION['username']); ?> <i class="ri-user-smile-line"></i>
            </button>
            <div class="dropdown-menu" style="display: none; position: absolute; top: 100%; left: 0; background-color: #f9f9f9; min-width: 160px; box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2); border-radius: 5px; z-index: 1;">
                <a href="submission/html/pengaturanakun.php" style="color: #333; padding: 12px 16px; text-decoration: none; display: block; font-size: 14px; border-bottom: 1px solid #ddd;">Profil</a>
                <a href="logout.php" style="color: #333; padding: 12px 16px; text-decoration: none; display: block; font-size: 14px;">Logout</a>
            </div>
        </div>
        <script>
            document.querySelector('.dropdown-toggle').addEventListener('click', function() {
                const menu = this.nextElementSibling;
                menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
            });
        </script>
    <?php else: ?>
        <!-- Jika belum login, tampilkan tombol Submission -->
        <a href="login.php" class="btn-submission" style="background-color: #6202FE; color: white; padding: 10px 20px; font-size: 16px; border-radius: 5px; text-decoration: none; display: inline-block;">Submission <i class="ri-attachment-line"></i></a>
    <?php endif; ?>
</header>

    <section id="ngok" style="background-image: url('https://i.ibb.co.com/vQpvpf7/tabebuya2.png'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed; align-items: center; align-content: center; ">
        <div class="textleader">
            <h1>Explore 🚀</h1>
            <p>Cari desainmu disini coyyyy</p>
        </div>
    </section>

    <!-- Bagian Explore -->
    <section id="explore">
        
        <div class="tabs">
            <?php foreach ($designs as $category => $items) { ?>
                <button class="tablinks" onclick="openTab(event, '<?php echo $category; ?>')"><?php echo $category; ?></button>
            <?php } ?>
        </div>

      <!-- Konten berdasarkan kategori -->
<?php foreach ($designs as $category => $items) { ?>
    <div id="<?php echo $category; ?>" class="tabcontent">
        <h2><?php echo $category; ?> Content</h2>
        <div class="kartu-container">
            <?php foreach ($items as $row) { ?>
                <div class="kartu">
                    <!-- Menampilkan gambar thumbnail -->
                    <img src="submission/html/uploads/thumbnails/<?= htmlspecialchars($row['thumbnail'] ?: 'assets/img/avatars/dragon.gif') ?>" alt="<?= htmlspecialchars($row['judul_karya']) ?>" class="card-img">
   
                    <div class="card-content">
                        <h1><?php echo $row["judul_karya"]; ?></h1>
                        <p>
                            <span class="icon-text"><i class="ri-user-smile-fill"></i> <?php echo $row["username"]; ?></span>
                            <span class="icon-separator"><i class="ri-pages-fill"></i></i></span> 
                            <span class="kategori"><?php echo $row["nama_kategori"]; ?></span>
                        </p>
                        <button class="preview" onclick="window.location.href='preview.php?id_karya=<?php echo $row["id_karya"]; ?>'">Preview</button>
                    </div>
                </div>

            <?php } ?>
        </div>
    </div>
<?php } ?>

                </div>
            </div>
    </section>

 
    <!-- Bagian Footer -->

    <section id="footer">
        <div class="footercontainer">
            <div class="socialicon">
                <a href=""><i class="ri-instagram-fill"></i></a>
                <a href=""><i class="ri-twitter-x-fill"></i></a>
                <a href=""><i class="ri-tiktok-fill"></i></i></a>
                <a href=""><i class="ri-mail-fill"></i></i></a>
            </div>
            <ul class="menu2">
                <a href="index.html" class="active">Beranda</a>
                <a href="#explore">Explore</a>
                <a href="leaderboard.html">Leaderboard</a>
                <a href="contact.html">Contact Us</a>
            </ul>
            <div class="copyright">
                <p>Crafted with ❤️ by Alip, Rizal, Arsyah</p>
            </div>
        </div>
    </section>



</body>
<!-- Footer Guanteng Pwol -->

<script>
    feather.replace();
</script>

<script src="https://unpkg.com/scrollreveal"></script>

<script src="js/script.js"></script>

</html>