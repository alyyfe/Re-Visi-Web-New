<?php
// Menghubungkan ke database
include('database/koneksi.php');
session_start(); // Pastikan sesi dimulai

// Query untuk mengambil data leaderboard (Top 3 berdasarkan jumlah karya)
$sql = "SELECT users.nama, users.foto_profil, COUNT(karya.id_karya) AS total_karya
        FROM users
        LEFT JOIN karya ON users.id_user = karya.id_user
        GROUP BY users.nama, users.foto_profil
        ORDER BY total_karya DESC
        LIMIT 3"; // Mengambil hanya 3 data teratas
$resultTop3 = $conn->query($sql);
$topUsers = [];
while ($row = $resultTop3->fetch_assoc()) {
    $topUsers[] = $row;
}

// Query untuk mengambil data lengkap leaderboard (semua pengguna)
$sqlFullLeaderboard = "SELECT users.nama, users.foto_profil, COUNT(karya.id_karya) AS total_karya
                       FROM users
                       LEFT JOIN karya ON users.id_user = karya.id_user
                       GROUP BY users.nama, users.foto_profil
                       ORDER BY total_karya DESC";
$resultLeaderboard = $conn->query($sqlFullLeaderboard);
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
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
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
        <a href="leaderboard.php" class="active">Leaderboard</a>
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
            <h1>Hall Of Fame 👑</h1>
            <p>Sebuah penghormatan bagi mereka yang telah memberikan yang terbaik. Terus berkarya!</p>
        </div>
    </section>

    <section id="leaderboard">
        <div class="leaderboard-container">
            <div class="top-3-cards">
                <?php foreach ($topUsers as $index => $user): ?>
                    <div class="card">
                        <div class="img-container">
                            <img src="<?= htmlspecialchars($user['foto_profil'] ? 'uploads/foto_profil/' . $user['foto_profil'] : 'assets/img/avatars/dragon.gif') ?>" alt="<?= htmlspecialchars($user['nama']) ?>" class="card-img">
                            <div class="trophy">
                                <?php 
                                    if ($index == 0) echo "🥇"; // Piala Juara 1
                                    elseif ($index == 1) echo "🥈"; // Piala Juara 2
                                    else echo "🥉"; // Piala Juara 3
                                ?>
                            </div>
                        </div>
                        <div class="card-info">
                            <h3><?= htmlspecialchars($user['nama']) ?></h3>
                            <p>Total Karya: <?= htmlspecialchars($user['total_karya']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Tabel Leaderboard -->
            <div class="tabel">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Nama Orang Keren Sedunia Raya</th>
                            <th>Total Karya</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($resultLeaderboard->num_rows > 0) {
                            $rank = 1;
                            while ($row = $resultLeaderboard->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $rank . "</td>";
                                echo "<td>";
                                // Foto profil
                                $fotoProfil = $row['foto_profil'] ? 'uploads/foto_profil/' . htmlspecialchars($row['foto_profil']) : 'assets/img/avatars/dragon.gif';
                                echo "<img src=\"" . $fotoProfil . "\" alt=\"" . htmlspecialchars($row['nama']) . "\" class=\"contributor-photo\">";
                                echo htmlspecialchars($row['nama']);
                                echo "</td>";
                                echo "<td>" . $row['total_karya'] . "</td>";
                                echo "</tr>";
                                $rank++;
                            }
                        } else {
                            echo "<tr><td colspan='3'>Belum ada data untuk ditampilkan.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section id="spotify" class="spotify-container">
        <iframe src="https://open.spotify.com/embed/track/52v1IcYW8l17Q72wWMeklf?autoplay=1" width="300" height="80" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
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
                <a href="leaderboard.php" class="active">Leaderboard</a>
                <a href="contact.php">Contact Us</a>
            </ul>
            <div class="copyright">
                <p>Crafted with ❤️ by Alip, Rizal, Arsyah</p>
            </div>
        </div>
    </section>

</body>

<script>
    feather.replace();
</script>

<script src="js/script.js"></script>

</html>
