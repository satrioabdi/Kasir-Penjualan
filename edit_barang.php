    <?php
    require 'db_config.php';

    // Mendapatkan ID barang dari parameter URL
    $id_barang = $_GET['id'];

    // Query untuk mendapatkan data barang berdasarkan ID
    $query = "SELECT * FROM barang WHERE id_barang = :id_barang";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_barang', $id_barang);
    $stmt->execute();
    $barang = $stmt->fetch(PDO::FETCH_ASSOC);

    // Query untuk mendapatkan daftar kategori
    $query_kategori = "SELECT * FROM kategori";
    $stmt_kategori = $conn->prepare($query_kategori);
    $stmt_kategori->execute();
    $kategoris = $stmt_kategori->fetchAll(PDO::FETCH_ASSOC);

    // Cek apakah form sudah disubmit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_barang = $_POST['id_barang'];
        $nama_barang = $_POST['nama_barang'];
        $merk = $_POST['merk'];
        $kategori = $_POST['kategori'];
        $harga_beli = $_POST['harga_beli'];
        $harga_jual = $_POST['harga_jual'];
        $stok = $_POST['stok'];

        // Query untuk update data barang berdasarkan ID
        $query_update = "UPDATE barang SET nama_barang = :nama_barang, merk = :merk, id_kategori = :kategori, 
                harga_beli = :harga_beli, harga_jual = :harga_jual, stok = :stok WHERE id_barang = :id_barang";
        $stmt_update = $conn->prepare($query_update);
        $stmt_update->bindParam(':nama_barang', $nama_barang);
        $stmt_update->bindParam(':merk', $merk);
        $stmt_update->bindParam(':kategori', $kategori);
        $stmt_update->bindParam(':harga_beli', $harga_beli);
        $stmt_update->bindParam(':harga_jual', $harga_jual);
        $stmt_update->bindParam(':stok', $stok);
        $stmt_update->bindParam(':id_barang', $id_barang);

        if ($stmt_update->execute()) {
            // Jika proses update berhasil, alihkan ke halaman barang.php
            echo '<script>alert("Barang berhasil diubah!");</script>';
            echo '<script>window.location.replace("barang.php");</script>';
            exit();
        } else {
            echo "Gagal melakukan update data barang.";
        }
    }

    $conn = null;
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Edit Barang</title>
        <style>
            /* Gaya tampilan halaman edit_barang */
            body {
                font-family: Arial, sans-serif;
                background-color: #f1f1f1;
                margin: 0;
                padding: 0;
            }
            h1 {
                text-align: center;
            }
            .edit-form {
                width: 400px;
                margin: 0 auto;
                background-color: #fff;
                padding: 20px;
                border-radius: 4px;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            }
            .edit-form label {
                display: block;
                margin-bottom: 8px;
            }
            .edit-form input[type="text"],
            .edit-form select {
                width: 100%;
                padding: 8px;
                border: 1px solid #ccc;
                border-radius: 4px;
            }
            .edit-form button {
                margin-top: 10px;
                background-color: #4CAF50;
                color: #fff;
                border: none;
                border-radius: 4px;
                padding: 8px 16px;
                cursor: pointer;
            }
        </style>
    </head>
    <body>
    <?php include 'sidebar.php'; ?>
            <?php include 'headers.php'; ?>
            <div class="content">
        <h1>Edit Barang</h1>
        <div class="edit-form">
            <form action="" method="POST">
                <input type="hidden" name="id_barang" value="<?php echo $barang['id_barang']; ?>">
                <label for="nama_barang">Nama Barang:</label>
                <input type="text" name="nama_barang" value="<?php echo $barang['nama_barang']; ?>" required>

                <label for="merk">Merk:</label>
                <input type="text" name="merk" value="<?php echo $barang['merk']; ?>" required>

                <label for="kategori">Kategori:</label>
                <select name="kategori" required>
                    <?php foreach ($kategoris as $kategori) : ?>
                        <option value="<?php echo $kategori['id_kategori']; ?>" <?php echo ($barang['id_kategori'] == $kategori['id_kategori']) ? 'selected' : ''; ?>>
                            <?php echo $kategori['nama_kategori']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="harga_beli">Harga Beli:</label>
                <input type="text" name="harga_beli" value="<?php echo $barang['harga_beli']; ?>" required>

                <label for="harga_jual">Harga Jual:</label>
                <input type="text" name="harga_jual" value="<?php echo $barang['harga_jual']; ?>" required>

                <label for="stok">Stok:</label>
                <input type="text" name="stok" value="<?php echo $barang['stok']; ?>" required>

                <button type="submit">Simpan</button>
            </form>
        </div>
    </body>
    </html>
