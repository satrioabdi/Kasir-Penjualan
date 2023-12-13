<?php
// Sambungkan ke database (ganti dengan informasi koneksi Anda)
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'toserba';

$koneksi = new mysqli($host, $username, $password, $database);

// Tangkap input dari halaman transaksi_penjualan.php
$keyword = $_GET['keyword'];

// Query untuk mencari barang berdasarkan keyword
$query = "SELECT * FROM barang WHERE nama_barang LIKE '%$keyword%'";
$result = $koneksi->query($query);

// Hasil pencarian dikembalikan dalam bentuk JSON
$data_barang = array();
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $data_barang[] = $row;
  }
}

header('Content-Type: application/json');
echo json_encode($data_barang);
