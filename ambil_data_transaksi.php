<?php
// Panggil file db_config.php untuk mendapatkan koneksi ke database
require_once('db_config.php');

try {
    // Kueri SQL untuk mengambil data transaksi terbaru dari tabel "transaksijual"
    $sql = "SELECT tj.id_barang, tj.nama_barang, tj.jumlah, tj.harga_jual, n.id_nota 
            FROM transaksijual AS tj 
            JOIN nota AS n ON tj.id_nota = n.id_nota 
            WHERE n.status_pembayaran = 'sudah_dibayar'";
    $stmt = $conn->query($sql);

    // Proses data transaksi jika ada hasil dari kueri
    $dataTransaksi = array();
    $totalHarga = 0;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Proses data transaksi, misalnya menghitung total harga dan lainnya
        $subtotal = $row['harga_jual'] * $row['jumlah'];
        $totalHarga += $subtotal;

        // Tambahkan data transaksi ke array
        $dataTransaksi[] = $row;
    }

    // Buat array asosiatif yang berisi data transaksi dan informasi lainnya
    $response = array(
        'dataTransaksi' => $dataTransaksi,
        'totalHarga' => $totalHarga
    );

    // Mengembalikan data dalam format JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
