<?php
require 'db_config.php';

// Cek apakah ada parameter 'id' yang dikirimkan melalui URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Ambil nilai 'id' dari URL
    $id = $_GET['id'];

    // Lakukan proses penghapusan barang berdasarkan 'id'
    $stmt = $conn->prepare("DELETE FROM barang WHERE id_barang = :id");
    $stmt->bindParam(':id', $id);
    
    if ($stmt->execute()) {
        // Jika proses penghapusan berhasil, alihkan kembali ke halaman barang.php
        header('Location: barang.php');
        exit();
    } else {
        // Jika terjadi masalah dalam proses penghapusan, tampilkan pesan error
        echo "Gagal menghapus barang.";
    }
} else {
    // Jika tidak ada 'id' yang dikirimkan, kembalikan ke halaman barang.php
    header('Location: barang.php');
    exit();
}
?>
