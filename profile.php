<?php
require 'db_config.php';

$id_member = 2; // Ganti dengan ID member yang sesuai dengan pengguna yang sedang login

// Query untuk mendapatkan data member berdasarkan ID
$query = "SELECT * FROM member WHERE id_member = :id_member";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id_member', $id_member);
$stmt->execute();
$member = $stmt->fetch(PDO::FETCH_ASSOC);

// Inisialisasi variabel untuk pesan alert
$alertMessage = '';

// Cek apakah form untuk mengganti data profile sudah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_perubahan'])) {
    $nm_member = $_POST['nm_member'];
    $alamat_member = $_POST['alamat_member'];
    $telepon = $_POST['telepon'];
    $email = $_POST['email'];
    $NIK = $_POST['NIK'];

    // Query untuk update data member berdasarkan ID
    $query_update_profile = "UPDATE member SET nm_member = :nm_member, alamat_member = :alamat_member, 
              telepon = :telepon, email = :email, NIK = :NIK WHERE id_member = :id_member";
    $stmt_update_profile = $conn->prepare($query_update_profile);
    $stmt_update_profile->bindParam(':nm_member', $nm_member);
    $stmt_update_profile->bindParam(':alamat_member', $alamat_member);
    $stmt_update_profile->bindParam(':telepon', $telepon);
    $stmt_update_profile->bindParam(':email', $email);
    $stmt_update_profile->bindParam(':NIK', $NIK);
    $stmt_update_profile->bindParam(':id_member', $id_member);

    if ($stmt_update_profile->execute()) {
        // Set data member dengan data yang baru diubah
        $member['nm_member'] = $nm_member;
        $member['alamat_member'] = $alamat_member;
        $member['telepon'] = $telepon;
        $member['email'] = $email;
        $member['NIK'] = $NIK;
        
        // Pesan alert untuk menampilkan bahwa data berhasil diubah
        $alertMessage = '<script>alert("Data berhasil diubah!");</script>';
    } else {
        echo "Gagal melakukan update profil.";
    }
}

$conn = null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profil Pengguna</title>
    <style>
        /* Gaya tampilan halaman profile */
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
        }
        .profile-form {
            width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .profile-form label {
            display: block;
            margin-bottom: 8px;
        }
        .profile-form input[type="text"],
        .profile-form textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .profile-form button {
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
    <h1>Profil Pengguna</h1>
    <?php echo $alertMessage; ?> <!-- Menampilkan pesan alert jika ada -->
    <div class="profile-form">
        <form action="" method="POST">
            <label for="nm_member">Nama Lengkap:</label>
            <input type="text" name="nm_member" value="<?php echo $member['nm_member']; ?>" required>
            <label for="alamat_member">Alamat:</label>
            <textarea name="alamat_member" required><?php echo $member['alamat_member']; ?></textarea>
            <label for="telepon">Telepon:</label>
            <input type="text" name="telepon" value="<?php echo $member['telepon']; ?>" required>
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo $member['email']; ?>" required>
            <label for="NIK">NIK:</label>
            <input type="text" name="NIK" value="<?php echo $member['NIK']; ?>" required>
            <button type="submit" name="simpan_perubahan">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>
