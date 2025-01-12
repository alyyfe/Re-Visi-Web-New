<?php
// Pastikan koneksi ke database sudah dibuat
include('../../database/koneksi.php');

// Mengecek apakah ada parameter delete yang dikirim
if (isset($_POST['delete'])) {
    $karya_id = $_POST['delete'];

    // Query untuk menghapus karya berdasarkan id
    $sql = "DELETE FROM karya WHERE id_karya = ?";
    
    // Menyiapkan statement
    if ($stmt = $conn->prepare($sql)) {
        // Mengikat parameter
        $stmt->bind_param("i", $karya_id);

        // Mengeksekusi query
        if ($stmt->execute()) {
            echo "Karya berhasil dihapus";
        } else {
            echo "Gagal menghapus karya";
        }

        // Menutup statement
        $stmt->close();
    } else {
        echo "Gagal mempersiapkan query";
    }
} else {
    echo "ID karya tidak ditemukan";
}

// Menutup koneksi
$conn->close();
?>
