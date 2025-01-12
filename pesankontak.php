<?php
include 'database/koneksi.php'; // Pastikan koneksi ke database sudah benar

// Variabel untuk menampilkan pesan keberhasilan
$pesan_berhasil = false;

// Periksa apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pastikan semua key tersedia
    $nama = isset($_POST['name']) ? mysqli_real_escape_string($conn, $_POST['name']) : '';
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
    $subjek = isset($_POST['subject']) ? mysqli_real_escape_string($conn, $_POST['subject']) : '';
    $pesan = isset($_POST['message']) ? mysqli_real_escape_string($conn, $_POST['message']) : '';

    // Validasi apakah data kosong
    if (empty($nama) || empty($email) || empty($subjek) || empty($pesan)) {
        echo "Semua data wajib diisi!";
    } else {
        // Query untuk menyimpan data ke tabel
        $sql = "INSERT INTO kontak (nama, email, subjek, pesan, tanggal) 
                VALUES ('$nama', '$email', '$subjek', '$pesan', NOW())";

        if (mysqli_query($conn, $sql)) {
            // Set pesan berhasil untuk ditampilkan di SweetAlert
            $pesan_berhasil = true;
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Tutup koneksi
mysqli_close($conn);

// Redirect kembali ke halaman contact.php setelah form disubmit
header("Location: contact.php");
exit();
?>
