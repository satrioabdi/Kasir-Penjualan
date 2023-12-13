<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="custem.css">
    <link rel="stylesheet" href="csspenjualan.css">
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <?php include 'headers.php'; ?>
    <div class="container">
        <div class="content">
            <h1>Transaksi Penjualan</h1>
            <form id="formTransaksi">
                <div id="barangForm">
                    <!-- Form untuk memilih barang -->
                    <label for="barang">Barang:</label>
                    <select class="barang-select" name="barang[]" required>
                        <?php
                        // Kode PHP untuk menampilkan pilihan barang sama seperti sebelumnya
                        $koneksi = mysqli_connect('127.0.0.1', 'root', '', 'toserba');
                        $query = "SELECT id_barang, nama_barang, harga_jual FROM barang";
                        $result = mysqli_query($koneksi, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . $row['id_barang'] . "' data-harga='" . $row['harga_jual'] . "'>" . $row['nama_barang'] . " - " . $row['harga_jual'] . "</option>";
                        }
                        mysqli_close($koneksi);
                        ?>
                    </select>
                    <br><br>
                    <label for="jumlah">Jumlah:</label>
                    <input type="number" class="jumlah-input" name="jumlah[]" min="1" required>
                    <br><br>
                </div>
                <button type="button" id="tambahBarangButton">Tambah Barang</button>
                <br><br>
                <label for="total">Total Harga:</label>
                <input type="text" id="total" name="total" readonly>
                <br><br>
                <label for="pembayaran">Pembayaran:</label>
                <input type="number" id="pembayaran" name="pembayaran" min="0" required>
                <br><br>
                <label for="kembalian">Kembalian:</label>
                <input type="text" id="kembalian" name="kembalian" readonly>
                <br><br>
                <button type="submit">Buat Transaksi</button>
                <button type="button" id="cetakBuktiPembayaran" style="display: none;">Cetak Bukti Pembayaran</button>
            </form>

            <div class="kategori-list">
                <h2>Data Transaksi</h2>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Total Harga</th>
                            <th>Tanggal Input</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="dataTransaksi">
                        <!-- Tabel data transaksi akan ditampilkan di sini -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Kode JavaScript -->
    <script>
        // Fungsi untuk menghitung total harga berdasarkan jumlah dan harga jual
        function hitungTotal() {
            const barangSelects = document.getElementsByClassName('barang-select');
            const jumlahInputs = document.getElementsByClassName('jumlah-input');
            let totalHarga = 0;

            for (let i = 0; i < barangSelects.length; i++) {
                const selectedOption = barangSelects[i].options[barangSelects[i].selectedIndex];
                const hargaJual = parseFloat(selectedOption.dataset.harga);
                const jumlah = parseInt(jumlahInputs[i].value);
                totalHarga += hargaJual * jumlah;
            }

            document.getElementById('total').value = totalHarga.toFixed(2);
        }

        // Fungsi untuk menampilkan data transaksi setelah transaksi berhasil
        function tampilkanDataTransaksi(dataTransaksi) {
            const tbody = document.getElementById('dataTransaksi');
            tbody.innerHTML = '';

            dataTransaksi.forEach((transaksi, index) => {
                const row = `<tr>
                                <td>${index + 1}</td>
                                <td>${transaksi.id_barang}</td>
                                <td>${transaksi.jumlah}</td>
                                <td>${transaksi.total}</td>
                                <td>${transaksi.tanggal_input}</td>
                                <td><button type="button" class="hapusBarangButton">Hapus</button></td>
                            </tr>`;
                tbody.innerHTML += row;
            });

            // Tambahkan event listener pada tombol "Hapus Barang" setelah data transaksi ditampilkan
            const hapusBarangButtons = document.getElementsByClassName('hapusBarangButton');
            for (let i = 0; i < hapusBarangButtons.length; i++) {
                hapusBarangButtons[i].addEventListener('click', function () {
                    const tr = this.parentElement.parentElement;
                    tr.remove();
                    hitungTotal(); // Hitung ulang total harga setelah menghapus barang
                });
            }
        }

        // Fungsi untuk mengaktifkan tombol "Cetak Bukti Pembayaran"
        function aktifkanTombolCetak() {
            document.getElementById('cetakBuktiPembayaran').style.display = 'block';
        }

        // Fungsi untuk mengirimkan transaksi penjualan ke server dan menyimpan ke database
        function kirimTransaksi(event) {
            event.preventDefault();

            const barangSelects = document.getElementsByClassName('barang-select');
            const jumlahInputs = document.getElementsByClassName('jumlah-input');
            const totalHarga = parseFloat(document.getElementById('total').value);
            const pembayaran = parseFloat(document.getElementById('pembayaran').value);

            if (pembayaran < totalHarga) {
                alert('Uang pembayaran kurang');
                return;
            } else if (pembayaran === totalHarga) {
                document.getElementById('kembalian').value = '0';
                alert('Belanjaan Berhasil Di Bayar.');
            } else {
                const kembalian = pembayaran - totalHarga;
                document.getElementById('kembalian').value = kembalian.toFixed(2);
                alert('Belanjaan Berhasil Di Bayar. Kembalian: Rp ' + kembalian.toFixed(2));
            }

            const dataTransaksi = [];

            for (let i = 0; i < barangSelects.length; i++) {
                const idBarang = barangSelects[i].value;
                const jumlah = parseInt(jumlahInputs[i].value);
                const total = parseFloat(jumlah * parseFloat(barangSelects[i].options[barangSelects[i].selectedIndex].dataset.harga));

                const transaksi = {
                    id_barang: idBarang,
                    jumlah: jumlah,
                    total: total.toFixed(2)
                };

                dataTransaksi.push(transaksi);
            }

            fetch('simpan_transaksi.php', {
                method: 'POST',
                body: JSON.stringify(dataTransaksi),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(result => {
                // Transaksi berhasil disimpan ke database, panggil fungsi tampilkanDataTransaksi untuk menampilkan data transaksi terbaru
                tampilkanDataTransaksi(result);
                // Reset form
                document.getElementById('formTransaksi').reset();
                // Reset total harga dan pembayaran
                document.getElementById('total').value = '';
                document.getElementById('pembayaran').value = '';
                // Aktifkan tombol "Cetak Bukti Pembayaran"
                aktifkanTombolCetak();
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        const tambahBarangButton = document.getElementById('tambahBarangButton');
        tambahBarangButton.addEventListener('click', function () {
            const barangForm = document.getElementById('barangForm');
            const cloneBarangForm = barangForm.cloneNode(true);
            const deleteButton = document.createElement('button');
            deleteButton.textContent = 'Hapus Barang';
            deleteButton.type = 'button';
            deleteButton.addEventListener('click', function() {
                cloneBarangForm.remove();
                hitungTotal(); // Hitung ulang total harga setelah menghapus barang
            });
            cloneBarangForm.appendChild(deleteButton);
            tambahBarangButton.before(cloneBarangForm);
            const barangSelects = cloneBarangForm.getElementsByClassName('barang-select');
            const jumlahInputs = cloneBarangForm.getElementsByClassName('jumlah-input');
            for (let i = 0; i < barangSelects.length; i++) {
                barangSelects[i].addEventListener('change', hitungTotal);
                jumlahInputs[i].addEventListener('input', hitungTotal);
            }
        });

        fetch('ambil_data_transaksi.php')
        .then(response => response.json())
        .then(data => tampilkanDataTransaksi(data))
        .catch(error => console.error('Error:', error));

        document.getElementById('formTransaksi').addEventListener('submit', kirimTransaksi);

        // Fungsi untuk mencetak bukti pembayaran dalam format PDF menggunakan TCPDF
        function cetakBuktiPembayaran() {
            const dataTransaksi = [];
            const tabelTransaksi = document.getElementById('dataTransaksi').getElementsByTagName('tr');
            for (let i = 1; i < tabelTransaksi.length; i++) {
                const row = tabelTransaksi[i].getElementsByTagName('td');
                const transaksi = {
                    id_barang: row[1].textContent,
                    jumlah: row[2].textContent,
                    total: row[3].textContent,
                    tanggal_input: row[4].textContent,
                };
                dataTransaksi.push(transaksi);
            }

            fetch('cetak_nota.php', {
                method: 'POST',
                body: JSON.stringify(dataTransaksi),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.blob())
            .then(blob => {
                // Mengubah blob menjadi file PDF dan memberikan nama file
                const url = window.URL.createObjectURL(new Blob([blob]));
                const a = document.createElement('a');
                a.href = url;
                a.download = 'bukti_pembayaran.pdf';
                a.click();
                window.URL.revokeObjectURL(url);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        // Tambahkan event listener pada tombol "Cetak Bukti Pembayaran"
        document.getElementById('cetakBuktiPembayaran').addEventListener('click', cetakBuktiPembayaran);
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
