<?php
// Menghubungkan dengan file koneksi
include('database/koneksi.php');
session_start(); // Pastikan sesi dimulai


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Re-Visi: Tempat Berbagi Desain Kreatif</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        <a href="explore.php">Explore</a>
        <a href="leaderboard.php">Leaderboard</a>
        <a href="contact.php" class="active">Contact Us</a>
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

    <!-- Bagian bawah header -->
    <section id="ngok" style="background-image: url('https://i.ibb.co.com/vQpvpf7/tabebuya2.png'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed; align-items: center; align-content: center; ">
        <div class="textleader">
            <h2>Contact Us 😎🤙</h2>
            <p>Disini kmuh bisa contact kitahh</p>
        </div>
    </section>

    <section id="contact">
        <div class="contact-container">
            <div class="tabel-ganteng">
                <form action="pesankontak.php" method="post">
                    <div>
                        <label for="name">Sopo Jenengmu:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div>
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div>
                        <label for="subjek">Subjek:</label>
                        <input type="text" id="subject" name="subject" required>
                    </div>
                    <div>
                        <label for="message">Pesan:</label>
                        <textarea id="message" name="message" required></textarea>
                    </div>
                    <button type="submit">Send <i class="ri-send-plane-2-fill"></i></button>
                </form>
            </div>
        </div>
    </section>

    <section id="footer">
        <div class="footercontainer">
            <div class="socialicon">
                <a href=""><i class="ri-instagram-fill"></i></a>
                <a href=""><i class="ri-twitter-x-fill"></i></a>
                <a href=""><i class="ri-tiktok-fill"></i></i></a>
                <a href=""><i class="ri-mail-fill"></i></i></a>
            </div>
            <ul class="menu2">
                <a href="index.php">Beranda</a>
                <a href="#explore">Explore</a>
                <a href="leaderboard.php">Leaderboard</a>
                <a href="contact.php" class="active">Contact Us</a>
            </ul>
            <div class="copyright">
                <p>Crafted with ❤️ by Alip, Rizal, Arsyah</p>
            </div>
        </div>
    </section>

    <script>
        feather.replace();

        // Jika ada pesan berhasil dikirim, tampilkan SweetAlert
        <?php if (isset($pesan_berhasil) && $pesan_berhasil): ?>
            Swal.fire({
                icon: 'success',
                title: 'Pesan Berhasil Dikirim!',
                text: 'Terima kasih telah menghubungi kami.'
            });
        <?php endif; ?>
    </script>

    <script src="js/script.js"></script>
</body>

</html>
