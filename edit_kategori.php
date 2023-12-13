<?php
require 'db_config.php';

// Periksa apakah ID kategori telah disediakan melalui parameter GET
if (isset($_GET['id'])) {
    $kategoriID = $_GET['id'];

    // Query untuk mengambil data kategori berdasarkan ID
    $query = "SELECT * FROM kategori WHERE id_kategori = :kategoriID";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':kategoriID', $kategoriID);
    $stmt->execute();

    // Periksa apakah kategori ditemukan berdasarkan ID
    if ($stmt->rowCount() > 0) {
        $kategori = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "Kategori tidak ditemukan.";
        exit;
    }
} else {
    echo "ID Kategori tidak diberikan.";
    exit;
}

// Periksa apakah form telah disubmit untuk menyimpan perubahan kategori
if (isset($_POST['submit'])) {
    $nama_kategori = $_POST['nama_kategori'];

    // Query untuk memperbarui kategori di database berdasarkan ID
    $query = "UPDATE kategori SET nama_kategori = :nama_kategori WHERE id_kategori = :kategoriID";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':nama_kategori', $nama_kategori);
    $stmt->bindValue(':kategoriID', $kategoriID);

    // Eksekusi query
    if ($stmt->execute()) {
        // Redirect ke halaman kategori setelah berhasil memperbarui
        header('Location: kategori.php');
        exit;
    } else {
        echo "Terjadi kesalahan saat menyimpan perubahan.";
    }
}

$conn = null;
?>

<!DOCTYPE html>
<html>
<head>
    <title> Edit Kategori</title>
    <style>
        /* Gaya tampilan halaman edit_kategori */
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
        .edit-form {
            width: 400px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 4px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .edit-form label {
            display: block;
            margin-bottom: 8px;
        }
        .edit-form input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .edit-form button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .edit-form button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <?php include 'headers.php'; ?>
    <div class="content">
    <h1>Edit Kategori</h1>
    <div class="edit-form">
        <form method="POST" action="">
            <label for="nama_kategori">Nama Kategori:</label>
            <input type="text" name="nama_kategori" value="<?php echo $kategori['nama_kategori']; ?>" required>

            <button type="submit" name="submit" onclick="showAlert()">Simpan Perubahan</button>
        </form>
    </div>

    <script>
        function showAlert() {
            alert("Kategori berhasil diubah!");
        }
    </script>
</body>
</html>
