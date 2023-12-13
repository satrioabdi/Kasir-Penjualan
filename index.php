<?php
session_start();

// Cek apakah user telah login atau belum, jika belum redirect ke halaman login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

require 'db_config.php';

// Menghitung jumlah total barang
$stmt_barang = $conn->query("SELECT COUNT(*) AS total_barang FROM barang");
$row_barang = $stmt_barang->fetch(PDO::FETCH_ASSOC);
$total_barang = $row_barang['total_barang'];

// Menghitung jumlah total kategori
$stmt_kategori = $conn->query("SELECT COUNT(*) AS total_kategori FROM kategori");
$row_kategori = $stmt_kategori->fetch(PDO::FETCH_ASSOC);
$total_kategori = $row_kategori['total_kategori'];

// Menghitung total penjualan
$stmt_total_penjualan = $conn->query("SELECT COUNT(*) AS total_nota FROM nota");
$row_total_penjualan = $stmt_total_penjualan->fetch(PDO::FETCH_ASSOC);
$total_nota = $row_total_penjualan['total_nota'];

$conn = null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>TOSERBA PAK SAPARDI</title>
    <!-- Tambahkan link ke CSS jika diperlukan -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="template/bootstrap.min.css">
    <link rel="stylesheet" href="custem.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <?php include 'headers.php'; ?>

    <div class="content"> <!-- Ganti class "container" dengan "content" -->
        <?php
        if (isset($_GET['login_success']) && $_GET['login_success'] === 'true') {
            echo '<script>alert("Login berhasil! Selamat datang, '.$_SESSION['username'].'");</script>';
        }
        ?>

        <div class="summary">
            <h1 class="text-center">DASHBOARD</h1>
            <div class="row justify-content-center">
                <div class="col-6 col-md-4">
                    <div class="summary-item bg-light p-4 text-center">
                        <h3>Total Barang</h3>
                        <p class="fs-3"><?php echo $total_barang; ?></p>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="summary-item bg-light p-4 text-center">
                        <h3>Total Kategori</h3>
                        <p class="fs-3"><?php echo $total_kategori; ?></p>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="summary-item bg-light p-4 text-center">
                        <h3>Total Penjualan</h3>
                        <p class="fs-3"><?php echo $total_nota; ?></p>
                </div>
            </div>
        </div>
    </div> 

    <script src="template/jquery-3.6.0.min.js"></script>
    <script src="template/bootstrap.min.js"></script>
    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');

            var toggleBtn = document.getElementById('toggleBtn');
            toggleBtn.classList.toggle('open');
        }
    </script>
</body>
</html>
