<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="custem.css">
    <title>TOSERBA ANJAY - Kategori</title>
   
</head>
<body>

    <?php include 'sidebar.php'; ?>
    <?php include 'headers.php'; ?>

    <div class="container">
    <div class="content">

    <?php
require 'db_config.php';

// Mendapatkan kata kunci pencarian dari query string
$searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';

// Query untuk mencari kategori berdasarkan kata kunci
$query = "SELECT * FROM kategori WHERE nama_kategori LIKE :searchKeyword";
$stmt = $conn->prepare($query);
$stmt->bindValue(':searchKeyword', '%' . $searchKeyword . '%');
$stmt->execute();
$kategoriList = $stmt->fetchAll(PDO::FETCH_ASSOC);

$conn = null;
?>

        <h1>Daftar Kategori</h1>

        <div class="search-form center">
            <form action="kategori.php" method="GET">
                <input type="text" name="search" placeholder="Cari Kategori" value="<?php echo $searchKeyword; ?>">
                <button type="submit">Cari</button>
            </form>
        </div>

        <div class="center">
            <a class="add-button" href="tambah_kategori.php">Tambah Kategori</a>
        </div>

        <div class="kategori-list">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID Kategori</th>
                        <th>Nama Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($kategoriList) : ?>
                        <?php foreach ($kategoriList as $kategori) : ?>
                            <tr>
                                <td><?php echo $kategori['id_kategori']; ?></td>
                                <td><?php echo $kategori['nama_kategori']; ?></td>
                                <td>
                                    <a href="edit_kategori.php?id=<?php echo $kategori['id_kategori']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete_kategori.php?id=<?php echo $kategori['id_kategori']; ?>" class="btn btn-danger btn-sm">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="2">Tidak ada kategori yang ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        </div>
    </div>
</body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</html>
