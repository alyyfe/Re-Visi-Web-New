<?php
// Menghubungkan dengan file koneksi
include('database/koneksi.php');
session_start(); // Pastikan sesi dimulai

// Query untuk mengambil data desain dari tabel dan mengurutkan berdasarkan tanggal upload terbaru
$sql = "SELECT 
            karya.id_karya, 
            karya.judul_karya, 
            karya.thumbnail, 
            karya.tanggal_upload,   /* Pastikan kolom tanggal_upload ada di tabel karya */
            users.username,
             users.foto_profil,  
            kategori.nama_kategori 
            
        FROM 
            karya 
        JOIN 
            users ON karya.id_user = users.id_user 
        LEFT JOIN 
            kategori ON karya.id_kategori = kategori.id_kategori
        ORDER BY 
            karya.tanggal_upload DESC";  // Mengurutkan berdasarkan tanggal upload terbaru
$result = $conn->query($sql);

// Menyiapkan array untuk mengelompokkan desain berdasarkan kategori
$designs = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $category = $row['nama_kategori'];
        $designs[$category][] = $row;
    }
}
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
        <a href="index.php" class="active">Beranda</a>
        <a href="explore.php">Explore</a>
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

    <!-- Bagian Hero -->
    <section id="hero" >
        <div class="hero-left" >
            <div class="hero-column wrapper">
                <div class="rectangle top-left"></div>
                <div class="rectangle top-right"></div>
                <div class="rectangle bottom-left"></div>
                <div class="rectangle bottom-right"></div>

                <h1 class="main-title">Platform untuk berbagi dan mengeksplorasi desain <span
                        class="gradient-text">Figma</span> terbaik!</h1>
                <p>Re-Visi adalah tempat di mana desainer dapat menemukan inspirasi, berbagi karya, dan berkolaborasi
                    dengan komunitas kreatif.</p>
                <div class="floating-element bagus-badge">
                    <img src="assets/image/keren.png" alt="" width="250px" />
                </div>
            </div>

            <div class="button">
                <a href="login.php" class="btn-reg">Submission <i class="ri-attachment-line"></i></a>
                <a href="" class="btn-explore">Explore Desain</a>
            </div>
        </div>


        <div class="hero-right">
            <img src="assets/image/logo ganteng.png" alt="">
        </div>
    </section>

    <section class="lineuhuy">
        <div class="marquee">
            <p class="title">
                ✨ Contribute your creativity as the key to innovation! 🚀 Unleash your potential! 🌈 Explore new
                horizons and push the boundaries of your imagination! 🌟 Embrace every challenge as an opportunity to
                learn and grow! 💡 Transform your ideas into reality and let your passion shine! 🌍 Together, we can
                create a future filled with possibilities and endless adventures! 🎨 This exciting journey of discovery
                and creativity awaits! ✨ Contribute your creativity as the key to innovation! 🚀 Unleash your potential!
                🌈 Explore new
                horizons and push the boundaries of your imagination! 🌟 Embrace every challenge as an opportunity to
                learn and grow! 💡 Transform your ideas into reality and let your passion shine! 🌍 Together, we can
                create a future filled with possibilities and endless adventures! 🎨 This exciting journey of discovery
                and creativity awaits! ✨
            </p>
        </div>
        <div class="moving-line"></div>
    </section>


  <!-- Bagian Kartu -->
<section id="kartu-section">
    <!-- Konten berdasarkan kategori -->
    <?php foreach ($designs as $category => $items) { ?>
    <div id="<?php echo $category; ?>" class="tabcontent">
        <h2><?php echo $category; ?> Content</h2>
        
        <!-- Menambahkan keterangan untuk kategori -->
        <?php if ($category == 'Design Content'): ?>
    <p class="kategori-description" style="font-size: 18px; color: #555; margin-top: 10px; margin-bottom: 15px;">
        Desain adalah elemen yang menggambarkan konsep visual, pengalaman pengguna, dan antarmuka aplikasi atau situs web yang dapat mempermudah pengguna dalam berinteraksi dengan produk atau layanan.
    </p>
<?php elseif ($category == 'Wireframe Content'): ?>
    <p class="kategori-description" style="font-size: 18px; color: #555; margin-top: 10px; margin-bottom: 15px;">
        Wireframe adalah representasi skematik dari tampilan dan struktur desain, memberikan gambaran tentang penempatan elemen-elemen utama seperti tombol, menu, dan konten tanpa memperhatikan estetika visual.
    </p>
<?php elseif ($category == 'Prototype Content'): ?>
    <p class="kategori-description" style="font-size: 18px; color: #555; margin-top: 10px; margin-bottom: 15px;">
        Prototipe adalah model kerja dari produk atau aplikasi yang mencakup interaksi antar elemen untuk menunjukkan bagaimana desain akan berfungsi dan berinteraksi dengan pengguna.
    </p>
<?php else: ?>
    <p class="kategori-description" style="font-size: 18px; color: #555; margin-top: 10px; margin-bottom: 15px;">
        Konten ini termasuk dalam kategori <?php echo $category; ?> yang menawarkan berbagai karya desain yang berbeda.
    </p>
<?php endif; ?>


        <div class="kartu-container">
            <?php 
            // Batasi hanya 4 konten per kategori
            $count = 0;
            foreach ($items as $row) {
                if ($count >= 4) break; // Batasi 4 konten
                $count++;
            ?>
                <div class="kartu">
                    <!-- Menampilkan gambar thumbnail -->
                    <img src="submission/html/uploads/thumbnails/<?php echo $row['thumbnail']; ?>" alt="Thumbnail" class="thumbnail">
                    <div class="card-content">
                        <h1><?php echo $row["judul_karya"]; ?></h1>
                        <p><span class="icon-text"><i class="ri-user-smile-fill"></i> <?php echo $row["username"]; ?></span>
                        <i class="ri-pages-fill"></i> <span class="kategori"><?php echo $row["nama_kategori"]; ?></span></p>
                        <button class="preview" onclick="window.location.href='preview.php?id_karya=<?php echo $row["id_karya"]; ?>'">Preview</button>
                    </div>
                </div>
            <?php } ?>
        </div>

        <!-- Tombol "Lihat Selengkapnya" -->
        <div class="button-selengkapnya">
            <a href="explore.php" class="btn-selengkapnya">Lihat Selengkapnya <i class="ri-arrow-right-circle-line"></i></a>
        </div>
    </div>
    <?php } ?>

</section>

    <!-- macos -->
    <div class="carousel" id="carousel">
    <div class="judul-kartu" style="text-align: center;">
            <h1>Contributors <i class="ri-group-2-line"></i></h1>
            <p>Shoutout untuk semua kontributor! Kalian kren pwoll rekk 😎🤙</p>
        </div>

        <div class="kontainer-kartu" id="kontainer-kartu">
        <?php
// Query untuk mendapatkan data contributors
$query = "SELECT nama, bio, foto_profil FROM users";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Periksa apakah 'foto_profil' kosong atau NULL
        $fotoProfil = !empty($row['foto_profil']) 
        ? 'uploads/foto_profil/' . $row['foto_profil'] 
        : 'assets/img/avatars/dragon.gif';
    
        
        echo '
        <div class="kartuwoi">
            <div class="header-kartu">
                <div class="tombol tutup"></div>
                <div class="tombol minimalkan"></div>
                <div class="tombol maksimalkan"></div>
            </div>
            <div class="gambar-kartu">
                <img src="' . htmlspecialchars($fotoProfil) . '" alt="Foto Profil">
            </div>
            <div class="konten-kartu">
                <h2 class="username">' . htmlspecialchars($row['nama']) . '</h2>
                <p class="bio">' . htmlspecialchars($row['bio']) . '</p>
            </div>
        </div>';
    }
} else {
    echo '<p>Belum ada kontributor.</p>';
}
?>

</div>

    </div>

  
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

<!-- <script src="https://unpkg.com/scrollreveal"></script> -->

<script src="js/script.js"></script>

</html>