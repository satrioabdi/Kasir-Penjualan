<?php
// Memuat file db_config.php untuk mendapatkan koneksi PDO
require 'db_config.php';

// Fungsi untuk mencetak bukti pembayaran sebagai file PDF
function printBuktiPembayaran($dataTransaksi, $pembayaran, $kembalian) {
  // ... Kode untuk mencetak bukti pembayaran menggunakan TCPDF ...
}

// Ambil data dari form transaksi
if (isset($_POST['dataTransaksi']) && isset($_POST['pembayaran'])) {
  $dataTransaksi = $_POST['dataTransaksi'];
  $pembayaran = floatval($_POST['pembayaran']);

  // Hitung total harga dari data transaksi
  $totalHarga = 0;
  foreach ($dataTransaksi as $transaksi) {
    $totalHarga += floatval($transaksi['total']);
  }

  // Hitung kembalian
  $kembalian = $pembayaran - $totalHarga;

  // Panggil fungsi untuk mencetak bukti pembayaran dalam format PDF
  printBuktiPembayaran($dataTransaksi, $pembayaran, $kembalian);

  // Simpan data transaksi ke database
  $tanggalInput = date('Y-m-d H:i:s');
  foreach ($dataTransaksi as $transaksi) {
    $idBarang = $transaksi['id_barang'];
    $jumlah = $transaksi['jumlah'];
    $total = $transaksi['total'];

    $sql = "INSERT INTO penjualan (id_barang, jumlah, total, tanggal_input) VALUES (:idBarang, :jumlah, :total, :tanggalInput)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':idBarang', $idBarang);
    $stmt->bindParam(':jumlah', $jumlah);
    $stmt->bindParam(':total', $total);
    $stmt->bindParam(':tanggalInput', $tanggalInput);
    $stmt->execute();
  }

  // Selesai mencetak dan menyimpan data, kembalikan respons ke JavaScript
  echo json_encode(array('status' => 'success'));
  exit;
}
?>
