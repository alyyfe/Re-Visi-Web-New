<?php
include('../../database/koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul_karya = $_POST['judul_karya'];
    $id_user = $_POST['id_user'];
    $id_kategori = $_POST['id_kategori'];
    $thumbnail = $_POST['thumbnail'];

    $sql = "INSERT INTO karya (judul_karya, id_user, id_kategori, thumbnail) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('siis', $judul_karya, $id_user, $id_kategori, $thumbnail);

    if ($stmt->execute()) {
        header('Location: karya.php');
    } else {
        echo "Gagal menambahkan karya.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Karya</title>
</head>
<body>
    <h1>Tambah Karya</h1>
    <form method="POST">
        <label>Judul Karya:</label>
        <input type="text" name="judul_karya" required><br>
        <label>ID User:</label>
        <input type="number" name="id_user" required><br>
        <label>ID Kategori:</label>
        <input type="number" name="id_kategori" required><br>
        <label>Thumbnail (URL):</label>
        <input type="text" name="thumbnail" required><br>
        <button type="submit">Tambah</button>
    </form>
</body>
</html>
