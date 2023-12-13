<?php
// cetak_bukti_pembayaran.php

require 'vendor/autoload.php'; // Jika Anda menggunakan Composer
// Atau gunakan require_once 'path/to/tcpdf/tcpdf.php' jika Anda mengunduh TCPDF secara manual

// Fungsi untuk mencetak bukti pembayaran sebagai file PDF
function printBuktiPembayaran($dataTransaksi) {
  // Membuat objek TCPDF
  $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

  // Atur judul dokumen
  $pdf->SetTitle('Bukti Pembayaran');

  // Menambahkan halaman baru
  $pdf->AddPage();

  // Mengatur font untuk konten
  $pdf->SetFont('times', '', 12);

  // Menambahkan konten ke PDF
  $content = '<h1>Bukti Pembayaran</h1>';
  $content .= '<p>Detail Transaksi:</p>';
  $content .= '<table border="1">';
  $content .= '<tr><th>No</th><th>Barang</th><th>Jumlah</th><th>Total Harga</th></tr>';
  foreach ($dataTransaksi as $index => $transaksi) {
    $content .= '<tr>';
    $content .= '<td>' . ($index + 1) . '</td>';
    $content .= '<td>' . $transaksi['id_barang'] . '</td>';
    $content .= '<td>' . $transaksi['jumlah'] . '</td>';
    $content .= '<td>' . $transaksi['total'] . '</td>';
    $content .= '</tr>';
  }
  $content .= '</table>';

  // Tambahkan konten ke halaman PDF
  $pdf->writeHTML($content, true, false, true, false, '');

  // Simpan file PDF ke server atau tampilkan di browser
  $filename = 'bukti_pembayaran.pdf';
  $pdf->Output($filename, 'I'); // Tampilkan di browser, untuk menyimpan ke server gunakan 'F' dengan path file

  // Selesai mencetak, kembalikan respons ke JavaScript
  echo json_encode(array('status' => 'success'));
}

// Ambil data dari form transaksi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dataTransaksi'])) {
  // Ambil data transaksi dari POST data dan konversi menjadi array
  $dataTransaksi = json_decode($_POST['dataTransaksi'], true);

  // Panggil fungsi untuk mencetak bukti pembayaran dalam format PDF
  printBuktiPembayaran($dataTransaksi);
}
?>
