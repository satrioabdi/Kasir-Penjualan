<?php
// Check if the form is submitted for search
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve search data
    $bulan = $_POST['bulan'] ?? ''; // Menggunakan Null Coalescing Operator untuk menetapkan nilai default ''
    $tahun = $_POST['tahun'] ?? ''; // Menggunakan Null Coalescing Operator untuk menetapkan nilai default ''
    $hari = $_POST['hari'] ?? ''; // Menggunakan Null Coalescing Operator untuk menetapkan nilai default ''
    

    // Create a date filter based on the selected criteria
    $dateFilter = '';
    if (!empty($bulan) && !empty($tahun)) {
        $dateFilter = "DATE_FORMAT(tanggal_input, '%m-%Y') = '$bulan-$tahun'";
    } elseif (!empty($hari)) {
        $dateFilter = "tanggal_input = '$hari'";
    }
}

require 'db_config.php';

// Retrieve sales data based on the selected criteria
if (isset($dateFilter) && !empty($dateFilter)) {
    $sql = "SELECT n.id_nota, n.id_barang, b.nama_barang, n.jumlah, b.harga_beli, n.total, m.nm_member AS kasir, n.tanggal_input
            FROM nota n
            INNER JOIN barang b ON n.id_barang = b.id_barang
            LEFT JOIN member m ON n.id_member = m.id_member
            WHERE $dateFilter
            ORDER BY n.id_nota";
} else {
    $sql = "SELECT n.id_nota, n.id_barang, b.nama_barang, n.jumlah, b.harga_beli, n.total, m.nm_member AS kasir, n.tanggal_input
            FROM nota n
            INNER JOIN barang b ON n.id_barang = b.id_barang
            LEFT JOIN member m ON n.id_member = m.id_member
            ORDER BY n.id_nota";
}

// Fetch data from the database
$stmt = $conn->query($sql);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$conn = null;
?>

<!DOCTYPE html>
<html>

<head>
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            margin: 0;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #4CAF50;
        }


     

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        button {
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Additional CSS for a more appealing design */
        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .form-group label {
            margin-right: 10px;
            font-weight: bold;
        }

        .form-group select,
        .form-group input[type="date"] {
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group select {
            min-width: 150px;
        }

        .form-group input[type="date"] {
            min-width: 200px;
        }

        button {
            padding: 10px 15px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }

        table {
            margin-top: 30px;
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        p.no-data {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: #777;
            margin-top: 20px;
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
        <h1>Laporan Penjualan</h1>
        <div class="container">
            <form method="POST" action="laporan_penjualan.php">
                <div class="form-group">
                    <label for="bulan">Bulan:</label>
                    <select id="bulan" name="bulan">
                        <option value="">Pilih Bulan</option>
                        <?php
                        // Menampilkan opsi bulan dari 01 hingga 12
                        for ($i = 1; $i <= 12; $i++) {
                            $month = str_pad($i, 2, '0', STR_PAD_LEFT); // Mengubah format bulan menjadi 2 digit angka
                            echo '<option value="' . $month . '">' . date('F', mktime(0, 0, 0, $i, 1)) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tahun">Tahun:</label>
                    <select id="tahun" name="tahun">
                        <option value="">Pilih Tahun</option>
                        <?php
                        // Menampilkan opsi tahun dari 2020 hingga 2023 (atau sesuai dengan data yang ada di database)
                        $tahunSaatIni = date('Y');
                        for ($tahun = $tahunSaatIni; $tahun >= 2020; $tahun--) {
                            echo '<option value="' . $tahun . '">' . $tahun . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="hari">Hari:</label>
                    <?php
                    // Mendapatkan tanggal saat ini dalam format "DD-MM-YYYY"
                    $tanggalSaatIni = date('d-m-Y');
                    ?>
                    <input type="date" id="hari" name="hari" value="<?php echo $tanggalSaatIni; ?>">
                </div>
                <div class="form-group">
                    <button type="submit">Search</button>
                </div>
            </form>
            <?php if (isset($data) && !empty($data)): ?>
                <table>
                    <tr>
                        <th>Nomer</th>
                        <th>ID Barang</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Modal (Harga Beli Barang)</th>
                        <th>Total</th>
                        <th>Kasir</th>
                        <th>Tanggal Input</th>
                    </tr>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <td>
                                <?php echo $row['id_nota']; ?>
                            </td>
                            <td>
                                <?php echo $row['id_barang']; ?>
                            </td>
                            <td>
                                <?php echo $row['nama_barang']; ?>
                            </td>
                            <td>
                                <?php echo $row['jumlah']; ?>
                            </td>
                            <td>
                                <?php echo $row['harga_beli']; ?>
                            </td>
                            <td>
                                <?php echo $row['total']; ?>
                            </td>
                            <td>
                                <?php echo $row['kasir']; ?>
                            </td>
                            <td>
                                <?php echo $row['tanggal_input']; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No data found.</p>
            <?php endif; ?>
        </div>
</body>

</html>