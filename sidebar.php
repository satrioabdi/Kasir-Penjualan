<?php
$activePage = basename($_SERVER['PHP_SELF'], ".php");
?>

<style>
    /* Gaya untuk sidebar */
    .sidebar {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        width: 280px;
        background-color: #343a40;
        padding: 1rem;
        overflow-y: auto;
    }

    /* Gaya untuk judul "TOSERBA PAK SAPARDI" */
    .sidebar .fs-4 {
        margin-bottom: 1.5rem;
    }

    /* Gaya untuk link dalam sidebar */
    .sidebar a {
        color: #fff;
    }

    /* Gaya untuk link aktif */
    .sidebar .nav-link.active {
        font-weight: bold;
    }

    /* Gaya untuk item menu */
    .sidebar .nav-item {
        margin-bottom: 5px;
        padding-top: 5px;
        padding-bottom: 5px;
    }

    /* Gaya untuk garis pemisah */
    .sidebar .separator {
        margin-top: 1rem;
        margin-bottom: 1rem;
        border: 1px solid #fff;
    }

    /* Gaya untuk teks di bawah garis pemisah */
    .sidebar .description {
        color: #ccc;
        font-size: 14px;
    }
    
    /* Gaya untuk konten utama */
    .content {
        /* Pastikan konten utama memiliki margin kiri yang cukup untuk memberi ruang bagi sidebar */
        margin-left: 280px; /* Lebar sidebar */
    }
</style>


<div class="sidebar">
    <a href="index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <span class="fs-4">TOSERBA PAK SAPARDI</span>
    </a>
    <hr class="separator"> <!-- Tambahkan garis pemisah di bawah tulisan "TOSERBA PAK SAPARDI" -->
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="barang.php" class="nav-link <?php echo ($activePage == 'barang') ? 'active' : ''; ?>" aria-current="page">
                <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"/></svg>
                Barang
            </a>
            <hr class="separator"> <!-- Tambahkan garis pemisah untuk item menu -->
        </li>
        <li class="nav-item">
            <a href="kategori.php" class="nav-link <?php echo ($activePage == 'kategori') ? 'active' : ''; ?>" aria-current="page">
                <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"/></svg>
                Kategori
            </a>
            <hr class="separator"> <!-- Tambahkan garis pemisah untuk item menu -->
        </li>
        <li class="nav-item">
            <a href="transaksi_jualrevisi.php" class="nav-link <?php echo ($activePage == 'transaksi_jual') ? 'active' : ''; ?>" aria-current="page">
                <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#table"/></svg>
                Transaksi Jual
            </a>
            <hr class="separator"> <!-- Tambahkan garis pemisah untuk item menu -->
        </li>
        <li class="nav-item">
            <a href="laporan_penjualan.php" class="nav-link <?php echo ($activePage == 'laporan_penjualan') ? 'active' : ''; ?>" aria-current="page">
                <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#grid"/></svg>
                Laporan Penjualan
            </a>
            <hr class="separator"> <!-- Tambahkan garis pemisah untuk item menu -->
        </li>
        <li class="nav-item">
            <a href="pengaturan_toko.php" class="nav-link <?php echo ($activePage == 'pengaturan_toko') ? 'active' : ''; ?>" aria-current="page">
                <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#people-circle"/></svg>
                Pengaturan Toko
            </a>
            <hr class="separator"> <!-- Tambahkan garis pemisah di bawah tulisan "TOSERBA PAK SAPARDI" -->
        </li>
    </ul>
</div>
<div class="container-fluid">

<script src="sidebar.js"></script>
