<?php
session_start();
require_once '../database/koneksi.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['admin_nama'])) {  
    header("Location: login.php");
    exit();
}

// Cek jika ID karya ada di URL
if (isset($_GET['id'])) {
    $id_karya = $_GET['id'];

    // Ambil data karya berdasarkan ID
    $query = "SELECT karya.id_karya, users.nama AS nama_pengupload, karya.thumbnail, kategori.nama_kategori, karya.id_kategori
              FROM karya 
              JOIN users ON karya.id_user = users.id_user
              JOIN kategori ON karya.id_kategori = kategori.id_kategori
              WHERE karya.id_karya = '$id_karya'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        die("Karya tidak ditemukan.");
    }
} else {
    die("ID Karya tidak ditemukan.");
}

// Proses jika form disubmit untuk menyimpan perubahan
if (isset($_POST['submit'])) {
    $nama_pengupload = $_POST['nama_pengupload'];
    $kategori = $_POST['kategori'];
    $thumbnail = $_FILES['thumbnail']['name'];

    // Proses upload thumbnail jika ada
    if ($thumbnail) {
        $target_dir = "../submission/html/uploads/thumbnails/";
        $target_file = $target_dir . basename($thumbnail);

        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $target_file)) {
            // Jika upload berhasil, update thumbnail
            $query = "UPDATE karya SET nama_pengupload = '$nama_pengupload', thumbnail = '$thumbnail', id_kategori = '$kategori' WHERE id_karya = '$id_karya'";
        } else {
            die("Upload thumbnail gagal.");
        }
    } else {
        // Jika thumbnail tidak diubah, tetap menggunakan thumbnail lama
        $query = "UPDATE karya SET nama_pengupload = '$nama_pengupload', id_kategori = '$kategori' WHERE id_karya = '$id_karya'";
    }

    if ($conn->query($query)) {
        echo "Karya berhasil diperbarui!";
        header("Location: index.php"); // Redirect setelah update
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!-- Form untuk mengedit karya -->
<form action="edit_karya.php?id=<?php echo $id_karya; ?>" method="POST" enctype="multipart/form-data">
    <label for="nama_pengupload">Nama Pengupload:</label>
    <input type="text" id="nama_pengupload" name="nama_pengupload" value="<?php echo $row['nama_pengupload']; ?>" required>

    <label for="thumbnail">Thumbnail:</label>
    <input type="file" id="thumbnail" name="thumbnail">
    <p>Thumbnail saat ini:</p>
    <img src="../submission/html/uploads/thumbnails/<?php echo $row['thumbnail']; ?>" alt="Thumbnail" style="max-width: 100px;">

    <label for="kategori">Kategori:</label>
    <select id="kategori" name="kategori" required>
        <?php
        // Ambil daftar kategori untuk dropdown
        $kategori_query = "SELECT id_kategori, nama_kategori FROM kategori";
        $kategori_result = $conn->query($kategori_query);

        while ($kategori_row = $kategori_result->fetch_assoc()) {
            $selected = $row['id_kategori'] == $kategori_row['id_kategori'] ? 'selected' : '';
            echo "<option value='{$kategori_row['id_kategori']}' $selected>{$kategori_row['nama_kategori']}</option>";
        }
        ?>
    </select>

    <button type="submit" name="submit">Update Karya</button>
</form>
