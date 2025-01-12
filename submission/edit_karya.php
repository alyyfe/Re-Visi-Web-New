<?php
// Pastikan koneksi ke database sudah dibuat
include('../../database/koneksi.php');

// Mengecek apakah ada parameter `id_karya`
if (isset($_GET['id_karya'])) {
    $id_karya = $_GET['id_karya'];

    // Query untuk mengambil data karya berdasarkan `id_karya`
    $sql = "SELECT * FROM karya WHERE id_karya = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_karya);
    $stmt->execute();
    $result = $stmt->get_result();

    // Memastikan data karya ditemukan
    if ($result->num_rows > 0) {
        $karya = $result->fetch_assoc();
    } else {
        echo "Karya tidak ditemukan!";
        exit();
    }
} else {
    echo "ID karya tidak diberikan!";
    exit();
}

// Mengecek apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul_karya = $_POST['judul_karya'];
    $id_kategori = $_POST['id_kategori'];
    $thumbnail = $_POST['thumbnail']; // Ambil URL thumbnail langsung dari input teks

    // Query untuk mengupdate data karya
    $sql = "UPDATE karya SET judul_karya = ?, id_kategori = ?, thumbnail = ? WHERE id_karya = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisi", $judul_karya, $id_kategori, $thumbnail, $id_karya);

    if ($stmt->execute()) {
        echo "Karya berhasil diperbarui!";
        header("Location: karyaku.php"); // Redirect ke halaman karyaku
        exit();
    } else {
        echo "Gagal memperbarui karya!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Karya</title>
    <link rel="stylesheet" href="../assets/vendor/css/core.css">
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css">
</head>
<body>
    <div class="container">
        <h2>Edit Karya</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="judul_karya" class="form-label">Judul Karya</label>
                <input type="text" class="form-control" id="judul_karya" name="judul_karya" 
                       value="<?= htmlspecialchars($karya['judul_karya']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="id_kategori" class="form-label">Kategori</label>
                <select id="id_kategori" name="id_kategori" class="form-control">
                    <?php
                    // Ambil data kategori dari tabel kategori
                    $sql = "SELECT * FROM kategori";
                    $result = $conn->query($sql);
                    while ($kategori = $result->fetch_assoc()) {
                        $selected = $kategori['id_kategori'] == $karya['id_kategori'] ? 'selected' : '';
                        echo "<option value='{$kategori['id_kategori']}' $selected>{$kategori['nama_kategori']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="thumbnail" class="form-label">Thumbnail (URL)</label>
                <input type="text" class="form-control" id="thumbnail" name="thumbnail" 
                       value="<?= htmlspecialchars($karya['thumbnail']); ?>" required>
                <p>Pratinjau:</p>
                <img src="<?= htmlspecialchars($karya['thumbnail']); ?>" alt="Thumbnail" width="150">
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>
