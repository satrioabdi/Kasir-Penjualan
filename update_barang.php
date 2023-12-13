<?php
require 'db_config.php';

// Ambil data dari form edit_barang.php
$id_barang = $_POST['id_barang'];
$nama_barang = $_POST['nama_barang'];
$merk = $_POST['merk'];
$id_kategori = $_POST['kategori'];
$harga_beli = $_POST['harga_beli'];
$harga_jual = $_POST['harga_jual'];
$stok = $_POST['stok'];


// Query untuk update data barang
$query = "UPDATE barang SET nama_barang = :nama_barang, merk = :merk, id_kategori = :id_kategori, harga_beli = :harga_beli, harga_jual = :harga_jual, stok = :stok WHERE id_barang = :id_barang";
$stmt = $conn->prepare($query);
$stmt->bindParam(':nama_barang', $nama_barang);
$stmt->bindParam(':merk', $merk);
$stmt->bindParam(':id_kategori', $id_kategori);
$stmt->bindParam(':harga_beli', $harga_beli);
$stmt->bindParam(':harga_jual', $harga_jual);
$stmt->bindParam(':stok', $stok);
$stmt->bindParam(':id_barang', $id_barang);

if ($stmt->execute()) {
    // Redirect ke halaman index setelah berhasil menyimpan data
    header('Location: index.php');
} else {
    // Jika gagal, tampilkan pesan error
    echo "Gagal menyimpan data barang.";
}

$conn = null;
?>
