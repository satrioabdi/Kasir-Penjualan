<?php
require 'db_config.php';

if (isset($_POST['nama_kategori'])) {
    $nama_kategori = $_POST['nama_kategori'];
    $tgl_input = date('Y-m-d'); // Mendapatkan tanggal input saat ini

    $query = "INSERT INTO kategori (nama_kategori, tgl_input) VALUES (:nama_kategori, :tgl_input)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':nama_kategori', $nama_kategori);
    $stmt->bindParam(':tgl_input', $tgl_input);

    if ($stmt->execute()) {
        // Kategori berhasil ditambahkan, redirect ke halaman kategori
        header('Location: kategori.php');
        exit();
    } else {
        echo "Error: Gagal menambahkan kategori.";
    }
}

$conn = null;
?>
