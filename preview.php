<?php
// Koneksi ke database
include 'database/koneksi.php'; 

// Pastikan parameter id_karya ada di URL
if (isset($_GET['id_karya'])) {
    $id_karya = $_GET['id_karya'];

    // Query untuk mengambil data dari tabel karya dan users
    $query = "
        SELECT 
            karya.judul_karya, 
            karya.deskripsi, 
            karya.thumbnail, 
            karya.tanggal_upload,  
            karya.link_figma, 
            users.username, 
            users.email,
            users.foto_profil
        FROM 
            karya 
        JOIN 
            users 
        ON 
            karya.id_user = users.id_user 
        WHERE 
            karya.id_karya = ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_karya);
    $stmt->execute();
    $result = $stmt->get_result();

    // Periksa apakah data ditemukan
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $judul_karya = $row['judul_karya'];
        $deskripsi = $row['deskripsi'];
        $thumbnail = $row['thumbnail'];
        $tanggal_upload = $row['tanggal_upload']; 
        $link_figma = $row['link_figma'];
        $username = $row['username'];
        $email = $row['email'];
        $foto_profil = !empty($row['foto_profil']) && file_exists('uploads/foto_profil/' . $row['foto_profil']) 
        ? 'uploads/foto_profil/' . $row['foto_profil'] 
        : '../assets/img/avatars/dragon.gif';
    
    } else {
        echo "Data tidak ditemukan.";
        exit;
    }
} else {
    echo "Parameter id_karya tidak ditemukan di URL.";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReVisi: Tempat Berbagi Desain Kreatif</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Roboto+Slab:wght@100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link rel="icon" href="assets/image/uhuy.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css">
    <script src="https://unpkg.com/feather-icons"></script>

    <!-- SweetAlert2 CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

</head>

<body>
    <header>
        <a href="index.php" class="logo">ReVisi</a>
        <ul class="menu">
            <a href="index.php" class="active">Beranda</a>
            <a href="explore.php">Explore</a>
            <a href="leaderboard.php">Leaderboard</a>
            <a href="contact.php">Contact Us</a>
        </ul>

        <div class="menu-navbar">
            <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
        </div>
        <a href="login.php" class="btn-submission">Submission <i class="ri-attachment-line"></i></a>
    </header>

    <section id="preview">
    <div class="preview-container">
        <div class="contributor-actions">
            <div class="contributor-info">
                <img src="<?php echo $foto_profil; ?>" alt="Contributor Profile" class="contributor-photo">
                <h3><?php echo htmlspecialchars($username); ?></h3>
            </div>
            <div class="action-buttons">
                <button class="share-btn" id="share-btn"><i class="ri-share-line"></i></button>
            </div>
        </div>

        <!-- Embed Figma Preview -->
        <div class="figma-preview">
            <iframe class="figma-embed" src="<?php echo $link_figma; ?>" allowfullscreen></iframe>
        </div>

        <!-- Preview Details -->
        <div class="bawah-embed">
            <div class="judulbawahembed-preview">
                <h1><?php echo htmlspecialchars($judul_karya); ?></h1>

                <!-- Tanggal Upload dan Keterangan -->
                <p style="font-size: 0.85em; color: #777; margin-top: 10px; font-weight: normal; line-height: 1.4;">
                    Diupload pada: <?php echo date("Y-m-d H:i", strtotime($tanggal_upload)); ?>
                </p>
                
                <h3>Deskripsi</h3>
                <p><?php echo nl2br(htmlspecialchars($deskripsi)); ?></p>
            </div>
        </div>
    </div>
</section>





    <section id="footer">
        <div class="footercontainer">
            <div class="socialicon">
                <a href=""><i class="ri-instagram-fill"></i></a>
                <a href=""><i class="ri-twitter-x-fill"></i></a>
                <a href=""><i class="ri-tiktok-fill"></i></a>
                <a href=""><i class="ri-mail-fill"></i></a>
            </div>
            <ul class="menu2">
                <a href="index.php">Beranda</a>
                <a href="#explore">Explore</a>
                <a href="leaderboard.php">Leaderboard</a>
                <a href="contact.php">Contact Us</a>
            </ul>
            <div class="copyright">
                <p>Crafted with ❤️ by Alip, Rizal, Arsyah</p>
            </div>
        </div>
    </section>
</body>
<script>
    // Ambil tombol share berdasarkan ID
    document.getElementById("share-btn").addEventListener("click", function() {
        // Ambil link Figma dari elemen iframe
        var figmaLink = "<?php echo $link_figma; ?>";

        // Buat elemen input untuk menyalin URL Figma
        var input = document.createElement("input");
        input.value = figmaLink;
        document.body.appendChild(input);

        // Pilih dan salin teks dari elemen input
        input.select();
        document.execCommand("copy");

        // Hapus elemen input dari DOM setelah disalin
        document.body.removeChild(input);

        // Tampilkan notifikasi menggunakan SweetAlert2
        Swal.fire({
            icon: 'success',
            title: 'Link Figma Disalin!',
            text: 'Link Figma telah berhasil disalin ke clipboard.',
            confirmButtonText: 'Tutup'
        });
    });
</script>



<script>
    feather.replace();
</script>
<script src="js/script.js"></script>
</html>
