<?php
// cari_barang.php

require 'db_config.php';

// Ambil kata kunci pencarian dari halaman utama (transaksi_jual.php)
if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];

    // Query untuk mencari barang berdasarkan nama barang yang mengandung kata kunci
    $stmt = $conn->prepare("SELECT * FROM barang WHERE nama_barang LIKE :keyword");
    $stmt->bindValue(':keyword', '%' . $keyword . '%');
    $stmt->execute();

    // Ambil hasil pencarian dalam bentuk array associative
    $data_barang = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Mengirimkan hasil pencarian dalam format JSON
    echo json_encode($data_barang);
}
?>
