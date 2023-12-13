<?php
// Jalankan koneksi ke database
require 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id_barang = $_POST['id_barang'];
    $nama_barang = $_POST['nama_barang'];
    $merk = $_POST['merk'];
    $kategori = $_POST['kategori']; // Ambil data kategori yang diinputkan
    $harga_beli = $_POST['harga_beli'];
    $harga_jual = $_POST['harga_jual'];
    $stok = $_POST['stok'];

    // Query untuk menyimpan data barang ke database
    $query = "INSERT INTO barang (id_barang,nama_barang, merk, id_kategori, harga_beli, harga_jual, stok) 
              VALUES (:id_barang, :nama_barang, :merk, :kategori, :harga_beli, :harga_jual, :stok)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_barang', $id_barang);
    $stmt->bindParam(':nama_barang', $nama_barang);
    $stmt->bindParam(':merk', $merk);
    $stmt->bindParam(':kategori', $kategori); // Bind data kategori
    $stmt->bindParam(':harga_beli', $harga_beli);
    $stmt->bindParam(':harga_jual', $harga_jual);
    $stmt->bindParam(':stok', $stok);

    // Eksekusi query
    if ($stmt->execute()) {
        // Redirect ke halaman index.php jika data berhasil disimpan
        header('Location: index.php');
        exit();
    } else {
        // Tampilkan pesan error jika terjadi masalah saat menyimpan data
        echo "Error: Data barang gagal disimpan.";
    }
}
$conn = null;
?>
