<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $dataTransaksi = json_decode(file_get_contents('php://input'), true);

  // Sambungkan ke database (ganti dengan informasi koneksi Anda)
  $host = 'localhost';
  $username = 'root';
  $password = '';
  $database = 'toserba';

  $koneksi = new mysqli($host, $username, $password, $database);

  // Error handling untuk koneksi database
  if ($koneksi->connect_error) {
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Gagal terhubung ke database.'));
    exit;
  }

  // Assuming the id_member is available in the session after login
  $idMember = $_SESSION['id_member'];

  $totalHarga = 0;

  // Query untuk menyimpan data nota
  $queryNota = "INSERT INTO nota (id_member, id_barang, jumlah, total, tanggal_input, periode) VALUES ";

  foreach ($dataTransaksi as $transaksi) {
    $idBarang = $transaksi['id_barang'];
    $jumlah = $transaksi['jumlah'];

    // Query untuk mendapatkan harga jual barang dari tabel barang
    $queryBarang = "SELECT harga_jual FROM barang WHERE id_barang = ?";
    $stmt = $koneksi->prepare($queryBarang);
    $stmt->bind_param('s', $idBarang);
    $stmt->execute();
    $resultBarang = $stmt->get_result();

    if ($resultBarang->num_rows > 0) {
      $hargaJual = $resultBarang->fetch_assoc()['harga_jual'];
      $subtotal = $hargaJual * $jumlah;
      $totalHarga += $subtotal;

      // Tambahkan data transaksi ke queryNota
      $queryNota .= "('$idMember', '$idBarang', '$jumlah', '$subtotal', NOW(), YEAR(NOW())),";
    }
  }

  // Hapus koma terakhir pada queryNota
  $queryNota = rtrim($queryNota, ',');

  // Error handling untuk eksekusi queryNota
  if ($koneksi->query($queryNota)) {
    $idNota = $koneksi->insert_id; // ID nota yang baru saja di-generate

    // Kirim ID nota sebagai respons ke klien
    header('Content-Type: application/json');
    echo json_encode(array('id_nota' => $idNota));
  } else {
    // Kirim pesan error jika penyimpanan gagal
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Gagal menyimpan transaksi.'));
  }

  // Tutup koneksi database
  $koneksi->close();
}
?>
