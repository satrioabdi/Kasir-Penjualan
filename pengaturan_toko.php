<!DOCTYPE html>
<html>
<head>
    <title>Daftar Barang</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Memasukkan stylesheet dari Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="custem.css">
</head>

<body>
        <?php include 'sidebar.php'; ?>
        <?php include 'headers.php'; ?>
        <div class="content">
  <h4>Pengaturan Toko</h4>
  <br>
  <?php
  // Fungsi untuk menghubungkan ke database dan mengupdate data toko
  function updateToko($nama_toko, $alamat_toko, $tlp, $nama_pemilik)
  {
    // Koneksi ke database (ganti dengan informasi kredensial Anda)
    $host = '127.0.0.1';
    $username = 'root';
    $password = '';
    $database = 'toserba';

    $koneksi = mysqli_connect($host, $username, $password, $database);

    // Periksa koneksi
    if (mysqli_connect_errno()) {
      die("Koneksi database gagal: " . mysqli_connect_error());
    }

    // Update data toko ke dalam tabel toko
    $query = "UPDATE toko SET nama_toko='$nama_toko', alamat_toko='$alamat_toko', tlp='$tlp', nama_pemilik='$nama_pemilik' LIMIT 1";
    $result = mysqli_query($koneksi, $query);

    // Tutup koneksi
    mysqli_close($koneksi);

    return $result;
  }

  // Koneksi ke database (ganti dengan informasi kredensial Anda)
  $host = '127.0.0.1';
  $username = 'root';
  $password = '';
  $database = 'toserba';

  $koneksi = mysqli_connect($host, $username, $password, $database);

  // Periksa koneksi
  if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
  }

  // Cek apakah form sudah disubmit untuk melakukan update data
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_toko = $_POST['namatoko'];
    $alamat_toko = $_POST['alamat'];
    $tlp = $_POST['kontak'];
    $nama_pemilik = $_POST['pemilik'];

    // Panggil fungsi updateToko untuk mengupdate data
    $update_result = updateToko($nama_toko, $alamat_toko, $tlp, $nama_pemilik);

    // Ambil data toko terbaru dari database setelah proses update
    $query = "SELECT * FROM toko LIMIT 1";
    $result = mysqli_query($koneksi, $query);
    if (mysqli_num_rows($result) > 0) {
      $toko = mysqli_fetch_assoc($result);
    }
  } else {
    // Ambil data toko dari tabel toko (asumsikan data toko hanya ada satu baris)
    $query = "SELECT * FROM toko LIMIT 1";
    $result = mysqli_query($koneksi, $query);
    if (mysqli_num_rows($result) > 0) {
      $toko = mysqli_fetch_assoc($result);
    } else {
      $toko = array('nama_toko' => '', 'alamat_toko' => '', 'tlp' => '', 'nama_pemilik' => '');
    }
  }

  // Tutup koneksi
  mysqli_close($koneksi);
  ?>
  <?php if (isset($update_result) && $update_result) { ?>
    <div class="alert alert-success">
      <p>Ubah Data Berhasil!</p>
    </div>
  <?php } ?>
  <div class="card">
    <div class="card-body">
      <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Nama Toko</label>
              <input class="form-control" name="namatoko" value="<?php echo $toko['nama_toko']; ?>" placeholder="Nama Toko">
            </div>
            <div class="form-group">
              <label for="">Alamat Toko</label>
              <input class="form-control" name="alamat" value="<?php echo $toko['alamat_toko']; ?>" placeholder="Alamat Toko">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Kontak (Hp)</label>
              <input class="form-control" name="kontak" value="<?php echo $toko['tlp']; ?>" placeholder="Kontak (Hp)">
            </div>
            <div class="form-group">
              <label for="">Nama Pemilik Toko</label>
              <input class="form-control" name="pemilik" value="<?php echo $toko['nama_pemilik']; ?>" placeholder="Nama Pemilik Toko">
            </div>
          </div>
        </div>
        <button type="submit" name="update" class="btn btn-primary"><i class="fas fa-edit"></i> Update Data</button>
      </form>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
