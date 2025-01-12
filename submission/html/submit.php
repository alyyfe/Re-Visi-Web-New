<?php
// Menghubungkan ke database
include('../../database/koneksi.php');

// Memulai sesi untuk mengambil ID pengguna yang login
session_start();  // Pastikan session_start() dipanggil di sini

// Validasi jika formulir telah dikirim
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Periksa apakah ID pengguna ada di sesi
    if (!isset($_SESSION['user_id'])) {
        echo "<script>
                alert('Anda harus login terlebih dahulu!');
                window.location.href = '../../login.php'; // Redirect ke halaman login
              </script>";
        exit;
    }

    // Ambil data pengguna dari sesi login
    $id_user = $_SESSION['user_id'];  // ID pengguna dari sesi
    $username = $_SESSION['username']; // Username pengguna dari sesi
    $email = $_SESSION['email'];       // Email pengguna dari sesi
    $nama_lengkap = $_SESSION['nama_lengkap']; // Nama lengkap pengguna dari sesi

    // Ambil data dari formulir
    $judul_karya = $conn->real_escape_string($_POST['judul_karya']);
    $deskripsi = $conn->real_escape_string($_POST['deskripsi']);
    $link_figma = $conn->real_escape_string($_POST['link_figma']);
    $kategori = $conn->real_escape_string($_POST['kategori']); // id_kategori yang dipilih

    // Ambil tanggal dan waktu saat ini
    $tanggal_upload = date('Y-m-d H:i:s');  // Format: YYYY-MM-DD HH:MM:SS
    
    // Mulai transaksi untuk memastikan integritas data
    $conn->begin_transaction();

    // Direktori tempat file thumbnail akan disimpan
    $thumbnail_dir = 'uploads/thumbnails/';
    
    // Cek apakah folder tempat menyimpan file sudah ada, jika belum buat
    if (!is_dir($thumbnail_dir)) {
        mkdir($thumbnail_dir, 0777, true); // Buat folder jika belum ada
    }

    // Validasi file thumbnail
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        // Nama file yang akan disimpan (untuk menghindari nama duplikat)
        $thumbnail_name = uniqid() . '-' . basename($_FILES['thumbnail']['name']);
        $thumbnail_path = $thumbnail_dir . $thumbnail_name;

        // Validasi file (contoh: hanya gambar)
        $file_type = mime_content_type($_FILES['thumbnail']['tmp_name']);
        if (in_array($file_type, ['image/jpeg', 'image/png', 'image/gif'])) {
            // Memindahkan file dari folder sementara ke folder yang ditentukan
            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnail_path)) {
                // Jika berhasil, simpan data ke database
                try {
                    // 1. Masukkan data ke tabel karya
                    $sql_karya = "INSERT INTO karya (judul_karya, deskripsi, link_figma, thumbnail, id_user, id_kategori, tanggal_upload) 
                                  VALUES ('$judul_karya', '$deskripsi', '$link_figma', '$thumbnail_name', '$id_user', '$kategori', '$tanggal_upload')";

                    if (!$conn->query($sql_karya)) {
                        throw new Exception("Gagal menyimpan data karya: " . $conn->error);
                    }

                    // Jika semuanya berhasil, commit transaksi
                    $conn->commit();

                    // Redirect atau pesan sukses
                    echo "<script>
                            alert('Karya berhasil diunggah!');
                            window.location.href = 'uploadkarya.php'; // Ganti dengan halaman tujuan
                          </script>";
                } catch (Exception $e) {
                    // Jika ada kesalahan, rollback transaksi dan tampilkan pesan error
                    $conn->rollback();
                    echo "<script>
                            alert('Error: " . $e->getMessage() . "');
                            window.history.back();
                          </script>";
                }
            } else {
                echo "<script>
                        alert('Gagal mengunggah file thumbnail.');
                        window.history.back();
                      </script>";
                exit;
            }
        } else {
            echo "<script>
                    alert('Format file tidak valid. Hanya diperbolehkan JPG, PNG, atau GIF.');
                    window.history.back();
                  </script>";
            exit;
        }
    } else {
        echo "<script>
                alert('Tidak ada file thumbnail yang diunggah.');
                window.history.back();
              </script>";
        exit;
    }

} else {
    // Jika form belum dikirim, arahkan kembali ke halaman upload karya
    header("Location: uploadkarya.php");
    exit;
}
?>
