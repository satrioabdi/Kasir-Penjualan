<?php
require 'db_config.php';

// Cek apakah ada parameter 'id' yang dikirimkan melalui URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Ambil nilai 'id' dari URL
    $id = $_GET['id'];

    // Lakukan proses penghapusan kategori berdasarkan 'id'
    $stmt = $conn->prepare("DELETE FROM kategori WHERE id_kategori = :id");
    $stmt->bindParam(':id', $id);
    
    if ($stmt->execute()) {
        // Jika proses penghapusan berhasil, tampilkan alert dan alihkan kembali ke halaman kategori.php
        echo '<script>alert("Kategori berhasil dihapus!");</script>';
        echo '<script>window.location.replace("kategori.php");</script>';
        exit();
    } else {
        // Jika terjadi masalah dalam proses penghapusan, tampilkan pesan error
        echo "Gagal menghapus kategori.";
    }
} else {
    // Jika tidak ada 'id' yang dikirimkan, kembalikan ke halaman kategori.php
    header('Location: kategori.php');
    exit();
}
?>
