<?php
include('../database/koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id_user = $_GET['id'] ?? null;

    if ($id_user) {
        // Ambil nama file foto profil pengguna sebelum dihapus
        $query = "SELECT foto_profil FROM users WHERE id_user = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $id_user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $foto_profil = $row['foto_profil'];

            // Hapus file foto profil dari server jika ada
            if ($foto_profil && file_exists("../uploads/foto_profil/" . $foto_profil)) {
                unlink("../uploads/foto_profil/" . $foto_profil);
            }

            // Hapus data pengguna dari database
            $delete_query = "DELETE FROM users WHERE id_user = ?";
            $delete_stmt = $conn->prepare($delete_query);
            $delete_stmt->bind_param('i', $id_user);
            if ($delete_stmt->execute()) {
                header("Location: kelolausers.php?status=success");
                exit();
            } else {
                echo "Error: " . $delete_stmt->error;
            }
        } else {
            echo "Error: Pengguna tidak ditemukan.";
        }
    } else {
        echo "Error: ID pengguna tidak ditemukan.";
    }
}
?>
