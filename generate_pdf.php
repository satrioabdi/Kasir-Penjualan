<?php
require 'tcpdf/tcpdf.php';
require 'database_functions.php';

// Function untuk mencetak bukti pembayaran sebagai file PDF
function cetakBuktiPembayaran($dataTransaksi, $totalHarga, $uangPembayaran, $uangKembalian) {
  $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

  // Buat halaman PDF
  $pdf->AddPage();

  // Tambahkan konten untuk bukti pembayaran
  $buktiPembayaran = "
    <h2>Bukti Pembayaran</h2>
    <p>Total Harga: Rp $totalHarga</p>
    <p>Uang Pembayaran: Rp $uangPembayaran</p>
    <p>Uang Kembalian: Rp $uangKembalian</p>
    <table border='1'>
      <tr>
        <th>ID Barang</th>
        <th>Nama Barang</th>
        <th>Jumlah</th>
        <th>Subtotal</th>
      </tr>
  ";

  foreach ($dataTransaksi as $barang) {
    $idBarang = $barang['id_barang'];
    $namaBarang = $barang['nama_barang'];
    $jumlah = $barang['jumlah'];
    $subtotal = $barang['total'];

    $buktiPembayaran .= "
      <tr>
        <td>$idBarang</td>
        <td>$namaBarang</td>
        <td>$jumlah</td>
        <td>$subtotal</td>
      </tr>
    ";
  }

  $buktiPembayaran .= '</table>';

  $pdf->writeHTML($buktiPembayaran);

  // Simpan PDF ke file dan tampilkan sebagai file download
  $pdf->Output('bukti_pembayaran.pdf', 'D');
}

// Periksa apakah ID nota disediakan dalam parameter URL
if (isset($_GET['id_nota'])) {
  // Ambil ID nota dari parameter URL
  $idNota = $_GET['id_nota'];

  // Get data transaksi, total harga, uang pembayaran, dan uang kembalian from database
  $dataTransaksi = getDataTransaksiDariDatabase($idNota);
  $totalHarga = getTotalHargaDariDatabase($idNota);
  $uangPembayaran = getUangPembayaranDariDatabase($idNota);
  $uangKembalian = getUangKembalianDariDatabase($idNota);

  // Jika Anda sudah memiliki data yang diperlukan dari database, panggil fungsi untuk mencetak bukti pembayaran dengan data yang diambil
  cetakBuktiPembayaran($dataTransaksi, $totalHarga, $uangPembayaran, $uangKembalian);
} else {
  // Jika ID nota tidak tersedia, berikan pesan kesalahan
  echo "ID nota tidak tersedia. Silakan kembali ke halaman sebelumnya dan lakukan pembayaran terlebih dahulu.";
}
?>
