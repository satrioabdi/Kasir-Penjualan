<?php
// Path ke file tcpdf.php
require('TCPDF/tcpdf.php');

// Tes apakah TCPDF dapat di-load dengan benar
if (!class_exists('TCPDF')) {
    die('TCPDF tidak dapat di-load. Periksa path ke file tcpdf.php.');
}

// Ambil data transaksi dari request
$dataTransaksi = json_decode(file_get_contents('php://input'), true);

// Fungsi untuk membuat nota PDF menggunakan TCPDF
function buatNotaPDF($dataTransaksi) {
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Author');
    $pdf->SetTitle('Bukti Pembayaran');
    $pdf->SetSubject('Bukti Pembayaran');

    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    $pdf->SetFont('helvetica', '', 12);

    // Buat konten nota
    $content = '<h1 align="center">Bukti Pembayaran</h1><br>';
    $content .= '<table border="1" cellpadding="5">';
    $content .= '<thead><tr><th>No</th><th>Barang</th><th>Jumlah</th><th>Total Harga</th><th>Tanggal Input</th></tr></thead>';
    $content .= '<tbody>';
    foreach ($dataTransaksi as $index => $transaksi) {
        $content .= '<tr>';
        $content .= '<td>'. ($index + 1) .'</td>';
        $content .= '<td>'. $transaksi['id_barang'] .'</td>';
        $content .= '<td>'. $transaksi['jumlah'] .'</td>';
        $content .= '<td>'. $transaksi['total'] .'</td>';
        $content .= '<td>'. $transaksi['tanggal_input'] .'</td>';
        $content .= '</tr>';
    }
    $content .= '</tbody></table>';

    $pdf->AddPage();
    $pdf->writeHTML($content, true, false, true, false, '');

    $pdf->Output('bukti_pembayaran.pdf', 'I');
}

// Panggil fungsi untuk membuat nota PDF
buatNotaPDF($dataTransaksi);
?>
