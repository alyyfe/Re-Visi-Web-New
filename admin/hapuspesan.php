<?php
include '../database/koneksi.php'; // Ensure the database connection is included

if (isset($_GET['id'])) {
    $message_id = $_GET['id'];

    // Prepare and execute the delete query
    $sql = "DELETE FROM kontak WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $message_id);
        if ($stmt->execute()) {
            // Redirect to the message page after successful deletion
            header("Location: pesan.php?message=deleted");
        } else {
            echo "Error deleting message: " . $conn->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing query: " . $conn->error;
    }
} else {
    echo "No message ID specified.";
}

$conn->close();
?>
