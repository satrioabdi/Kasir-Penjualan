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
            

            <?php
            require 'db_config.php';

            // Mendapatkan kata kunci pencarian dari query string
            $searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';

            // Query untuk mencari barang berdasarkan kata kunci
            $query = "SELECT * FROM barang WHERE nama_barang LIKE :searchKeyword";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':searchKeyword', '%' . $searchKeyword . '%');
            $stmt->execute();
            $barangList = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $conn = null;
            ?>

            <div class="container">
                <h1 class="text-center">Daftar Barang</h1>

                <div class="search-form center">
        <form action="barang.php" method="GET">
            <input type="text" name="search" placeholder="Cari Kategori" value="<?php echo $searchKeyword; ?>">
            <button type="submit">Cari</button>
        </form>
    </div>

                <div class="center">
                    <a class="add-button" href="add_barang.php">Tambah Barang</a>
                </div>

                
                <div class="kategori-list ">
                    <?php if (isset($barangList) && !empty($barangList)) : ?>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Merk</th>
                                    <th>Kategori</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Jual</th>
                                    <th>Satuan Barang</th>
                                    <th>Stok</th>
                                    <th class="col-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($barangList as $barang) : ?>
                                    <tr>
                                        <td><?php echo $barang['id_barang']; ?></td>
                                        <td><?php echo $barang['nama_barang']; ?></td>
                                        <td><?php echo $barang['merk']; ?></td>
                                        <td><?php echo $barang['id_kategori']; ?></td>
                                        <td><?php echo $barang['harga_beli']; ?></td>
                                        <td><?php echo $barang['harga_jual']; ?></td>
                                        <td><?php echo $barang['satuan_barang']; ?></td>
                                        <td><?php echo $barang['stok']; ?></td>
                                        <td>
                                            <a href="edit_barang.php?id=<?php echo $barang['id_barang']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="delete_barang.php?id=<?php echo $barang['id_barang']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <p class="text-center">Tidak ada barang yang ditemukan.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Memasukkan script dari Bootstrap 5 dan jQuery (jika diperlukan) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
