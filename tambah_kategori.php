<?php
require 'db_config.php';

// Inisialisasi variabel untuk menampilkan alert setelah penambahan kategori
$alert = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama_kategori = $_POST['nama_kategori'];

    // Query untuk menyimpan data kategori ke database
    $query_insert = "INSERT INTO kategori (nama_kategori) VALUES (:nama_kategori)";
    $stmt_insert = $conn->prepare($query_insert);
    $stmt_insert->bindParam(':nama_kategori', $nama_kategori);

    // Cek apakah data berhasil disimpan ke database
    if ($stmt_insert->execute()) {
        // Jika berhasil, set variabel alert untuk menampilkan pesan sukses
        $alert = '<script>alert("Kategori berhasil disimpan!");</script>';
        // Redirect ke halaman kategori.php setelah menampilkan alert
        $alert .= '<script>window.location.replace("kategori.php");</script>';
    } else {
        // Jika gagal, set variabel alert untuk menampilkan pesan gagal
        $alert = '<script>alert("Gagal menyimpan kategori. Silakan coba lagi!");</script>';
    }
}

$conn = null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Kategori Baru</title>
    <style>
        /* Gaya tampilan halaman tambah kategori */
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            color: #333;
            padding: 20px 0;
        }
        .form-container {
            width: 400px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 4px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .form-container label {
            display: block;
            margin-bottom: 10px;
        }
        .form-container input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-container button {
            display: block;
            margin-top: 20px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 8px 16px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #45a049;
        }
        .home-button {
            display: inline-block;
            margin-top: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 8px 16px;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <?php include 'headers.php'; ?>
    <div class="content">
        <h1>Tambah Kategori Baru</h1>
        <?php echo $alert; ?>
        <div class="form-container">
            <form action="" method="POST">
                <label for="nama_kategori">Nama Kategori:</label>
                <input type="text" name="nama_kategori" required>

                <button type="submit">Tambah Kategori</button>
            </form>
        </div>
    </div>
</body>
</html>
