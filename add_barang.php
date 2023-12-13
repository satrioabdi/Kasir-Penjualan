<?php
require 'db_config.php';

// Query untuk mendapatkan daftar kategori
$query_kategori = "SELECT * FROM kategori";
$stmt_kategori = $conn->prepare($query_kategori);
$stmt_kategori->execute();
$kategoris = $stmt_kategori->fetchAll(PDO::FETCH_ASSOC);

// Inisialisasi variabel untuk menampilkan alert setelah penambahan barang
$alert = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id_barang = $_POST['id_barang'];
    $nama_barang = $_POST['nama_barang'];
    $merk = $_POST['merk'];
    $kategori = $_POST['kategori'];
    $harga_beli = $_POST['harga_beli'];
    $harga_jual = $_POST['harga_jual'];
    $stok = $_POST['stok'];
    $tgl_input = $_POST['tgl_input'];
    $tgl_update = $_POST['tgl_update'];

    // Query untuk menyimpan data barang ke database
    $query_insert = "INSERT INTO barang (id_barang, nama_barang, merk, id_kategori, harga_beli, harga_jual, stok, tgl_input, tgl_update)
                     VALUES (:id_barang, :nama_barang, :merk, :kategori, :harga_beli, :harga_jual, :stok, :tgl_input, :tgl_update)";
    $stmt_insert = $conn->prepare($query_insert);
    $stmt_insert->bindParam(':id_barang', $id_barang);
    $stmt_insert->bindParam(':nama_barang', $nama_barang);
    $stmt_insert->bindParam(':merk', $merk);
    $stmt_insert->bindParam(':kategori', $kategori);
    $stmt_insert->bindParam(':harga_beli', $harga_beli);
    $stmt_insert->bindParam(':harga_jual', $harga_jual);
    $stmt_insert->bindParam(':stok', $stok);
    $stmt_insert->bindParam(':tgl_input', $tgl_input);
    $stmt_insert->bindParam(':tgl_update', $tgl_update);

    // Cek apakah data berhasil disimpan ke database
    if ($stmt_insert->execute()) {
        // Jika berhasil, set variabel alert untuk menampilkan pesan sukses
        $alert = '<script>alert("Barang berhasil ditambahkan!");</script>';
        // Redirect ke halaman barang.php setelah menampilkan pesan sukses
        echo $alert;
        echo '<script>window.location.replace("barang.php");</script>';
        exit();
    } else {
        // Jika gagal, set variabel alert untuk menampilkan pesan gagal
        $alert = '<script>alert("Gagal menambahkan barang. Silakan coba lagi!");</script>';
    }
}

$conn = null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Barang Baru</title>
    <style>
        /* Gaya form input */
        label {
            display: inline-block;
            width: 120px;
        }
        input[type="text"], input[type="number"], input[type="date"] {
            width: 200px;
            padding: 5px;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <?php include 'headers.php'; ?>
    <div class="content">
        <h1>Tambah Barang Baru</h1>
        <?php echo $alert; ?>
        <form action="" method="POST">
            <label for="id_barang">ID Barang:</label>
            <input type="text" name="id_barang" id="id_barang" required>
            <br>
            <label for="nama_barang">Nama Barang:</label>
            <input type="text" name="nama_barang" id="nama_barang" required>
            <br>
            <label for="merk">Merk:</label>
            <input type="text" name="merk" id="merk" required>
            <br>
            <label for="kategori">Kategori:</label>
            <select name="kategori" required>
                <option value="" disabled selected>Pilih Kategori</option>
                <?php foreach ($kategoris as $kategori) : ?>
                    <option value="<?php echo $kategori['id_kategori']; ?>"><?php echo $kategori['nama_kategori']; ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <label for="harga_beli">Harga Beli:</label>
            <input type="number" name="harga_beli" id="harga_beli" required>
            <br>
            <label for="harga_jual">Harga Jual:</label>
            <input type="number" name="harga_jual" id="harga_jual" required>
            <br>
            <label for="stok">Stok:</label>
            <input type="number" name="stok" id="stok" required>
            <br>
            <label for="tgl_input">Tanggal Input:</label>
            <input type="date" name="tgl_input" id="tgl_input" value="<?php echo date('Y-m-d'); ?>" required readonly>
            <br>
            <label for="tgl_update">Tanggal Update:</label>
            <input type="date" name="tgl_update" id="tgl_update" value="<?php echo date('Y-m-d'); ?>" required readonly>
            <br>
            <input type="submit" value="Simpan">
        </form>
    </div>
</body>
</html>
