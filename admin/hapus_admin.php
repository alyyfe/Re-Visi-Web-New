<?php
include '../database/koneksi.php';

if (isset($_GET['id'])) {
    $id_admin = $_GET['id'];
    $query = "DELETE FROM admin WHERE id_admin='$id_admin'";
    if (mysqli_query($conn, $query)) {
        header('Location: kelolaadmin.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
