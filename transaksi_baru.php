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
                <!-- Tambahkan elemen tanggalInput -->
                <label for="tanggalInput">Tanggal Input:</label>
                <input type="date" id="tanggalInput" name="tanggalInput" required>

                <div id="barangForm">
                    <!-- Form untuk mencari barang -->
                    <label for="barang">Cari Barang:</label>
                    <input type="text" id="searchBarang" name="barang" autocomplete="off">
                    <button type="button" id="cariButton">Cari</button>
                    <ul id="hasil_pencarian"></ul>
                    <br>
                    <table id="dataBarang">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Total Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Baris tabel data barang akan ditampilkan di sini -->
                        </tbody>
                    </table>
                </div>
                <br>
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
            </form>

            <button type="button" id="cetakBuktiPembayaran" style="display: none;">Cetak Bukti Pembayaran</button>

            <div class="kategori-list">
                <h2>Data Transaksi</h2>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Variabel global untuk menyimpan data barang yang dipilih
    let barangDipilih = {};

    // Fungsi untuk mencari barang berdasarkan keyword
    function cariBarang(keyword) {
        $.ajax({
            url: 'cari_barang.php',
            type: 'GET',
            data: { keyword: keyword },
            dataType: 'json',
            success: function(response) {
                console.log(response); // Tambahkan baris ini untuk memeriksa data respons dari server
                let hasilHTML = '';
                if (response.length > 0) {
                    response.forEach(barang => {
                        hasilHTML += `<li><a href="#" onclick="pilihBarang(${barang.id}, '${barang.nama_barang}', ${barang.harga_jual});">${barang.nama_barang} - ${barang.harga_jual}</a></li>`;
                    });
                } else {
                    hasilHTML = '<li>Tidak ada barang yang ditemukan.</li>';
                }
                document.getElementById('hasil_pencarian').innerHTML = hasilHTML;
            },
            error: function() {
                document.getElementById('hasil_pencarian').innerHTML = '<li>Gagal melakukan pencarian.</li>';
            }
        });
    }

    function pilihBarang(id, nama, hargaJual) {
        barangDipilih = { id, nama, hargaJual };
        document.getElementById('searchBarang').value = ''; // Setel kotak pencarian menjadi kosong
        document.getElementById('hasil_pencarian').innerHTML = '';
        
        // Tambahkan data barang yang dipilih ke dalam tabel dataBarang
        const jumlah = 1; // Atur jumlah default menjadi 1 (Anda bisa mengubahnya sesuai kebutuhan)
        const total = hargaJual * jumlah;
        tambahBarisDataBarang(nama, jumlah, total);
    }

    function tampilkanDataTransaksi(data) {
    const tableBody = document.getElementById('dataTransaksi');
    tableBody.innerHTML = '';

    data.forEach((transaksi, index) => {
        const newRow = tableBody.insertRow();
        newRow.innerHTML = `
            <td>${index + 1}</td>
            <td>${transaksi.nama}</td>
            <td>${transaksi.jumlah}</td>
            <td>${transaksi.totalHarga}</td>
            <td>${transaksi.tanggal_input}</td>
            <td><button type="button" class="hapusTransaksi btn btn-danger" data-id="${index}">Hapus</button></td>
        `;
    });

        // Tambahkan event listener untuk tombol "Hapus Transaksi"
        const hapusTransaksiButtons = document.getElementsByClassName('hapusTransaksi');
    for (let i = 0; i < hapusTransaksiButtons.length; i++) {
        hapusTransaksiButtons[i].addEventListener('click', hapusTransaksi);
    }
}

function hapusTransaksi(event) {
    const transaksiIndex = event.target.getAttribute('data-id');
    const data = JSON.parse(localStorage.getItem('dataTransaksi'));
    data.splice(transaksiIndex, 1);
    localStorage.setItem('dataTransaksi', JSON.stringify(data));
    tampilkanDataTransaksi(data);
}
document.getElementById('cetakBuktiPembayaran').addEventListener('click', cetakBuktiPembayaran);



   // Fungsi untuk menambahkan baris data barang ke dalam tabel dataBarang
function tambahBarisDataBarang(nama, jumlah, total) {
    const tableBody = document.getElementById('dataBarang').getElementsByTagName('tbody')[0];
    const newRow = tableBody.insertRow();
    const namaCell = newRow.insertCell(0);
    const jumlahCell = newRow.insertCell(1);
    const totalCell = newRow.insertCell(2);
    const aksiCell = newRow.insertCell(3);

    namaCell.textContent = nama;
    jumlahCell.innerHTML = `<input type="number" class="jumlah" value="${jumlah}" min="1" required>`;
    totalCell.textContent = total;
    aksiCell.innerHTML = `<button type="button" class="hapusBarang btn btn-danger">Hapus</button>`;

    // Tambahkan event listener untuk tombol "Hapus Barang"
    const hapusBarangButtons = document.getElementsByClassName('hapusBarang');
    for (let i = 0; i < hapusBarangButtons.length; i++) {
        hapusBarangButtons[i].addEventListener('click', hapusBarang);
    }

    // Tambahkan event listener untuk input jumlah barang
    const jumlahInputs = document.getElementsByClassName('jumlah');
    for (let i = 0; i < jumlahInputs.length; i++) {
        jumlahInputs[i].addEventListener('input', updateTotalHarga);
    }

    // Update total harga setelah menambahkan data barang baru
    updateTotalHarga();
}

    // Fungsi untuk menghapus baris barang dari tabel dataBarang
    function hapusBarang(event) {
        const row = event.target.closest('tr');
        row.remove();
        updateTotalHarga();
    }

// Fungsi untuk menghitung total harga keseluruhan
function updateTotalHarga() {
    const jumlahInputs = document.getElementsByClassName('jumlah');
    let totalHarga = 0;
    for (let i = 0; i < jumlahInputs.length; i++) {
        const jumlahInput = jumlahInputs[i];
        const jumlah = parseInt(jumlahInput.value);
        const hargaJual = barangDipilih.hargaJual; // Ganti 'hargaJual' dengan 'harga_jual' jika diperlukan
        const total = hargaJual * jumlah;
        totalHarga += total;
    }
    document.getElementById('total').value = totalHarga.toFixed(2);
}


// Fungsi untuk menambahkan baris data barang ke dalam tabel dataBarang
function tambahBarisDataBarang(nama, jumlah, total) {
    const tableBody = document.getElementById('dataBarang').getElementsByTagName('tbody')[0];
    const newRow = tableBody.insertRow();
    const namaCell = newRow.insertCell(0);
    const jumlahCell = newRow.insertCell(1);
    const totalCell = newRow.insertCell(2);
    const aksiCell = newRow.insertCell(3);

    namaCell.textContent = nama;
    jumlahCell.innerHTML = `<input type="number" class="jumlah" value="${jumlah}" min="1" required>`;
    totalCell.textContent = total;
    aksiCell.innerHTML = `<button type="button" class="hapusBarang btn btn-danger">Hapus</button>`;

    // Tambahkan event listener untuk tombol "Hapus Barang"
    const hapusBarangButtons = document.getElementsByClassName('hapusBarang');
    for (let i = 0; i < hapusBarangButtons.length; i++) {
        hapusBarangButtons[i].addEventListener('click', hapusBarang);
    }

    // Tambahkan event listener untuk input jumlah barang
    const jumlahInputs = document.getElementsByClassName('jumlah');
    for (let i = 0; i < jumlahInputs.length; i++) {
        jumlahInputs[i].addEventListener('input', updateTotalHarga);
    }

    // Update total harga setelah menambahkan data barang baru
    updateTotalHarga();
}


  // Event listener untuk tombol "Buat Transaksi"
  document.getElementById('formTransaksi').addEventListener('submit', function(event) {
        event.preventDefault();
        const pembayaran = parseFloat(document.getElementById('pembayaran').value);
        const totalHarga = parseFloat(document.getElementById('total').value);
        if (pembayaran < totalHarga) {
            alert('Uang kurang. Silahkan masukkan pembayaran yang cukup.');
        } else {
            const kembalian = pembayaran - totalHarga;
            document.getElementById('kembalian').value = kembalian.toFixed(2);
            kirimTransaksi();
        }
    });

 // Fungsi untuk mengirimkan data transaksi ke server dan menyimpannya ke dalam database
 function kirimTransaksi() {
    // Mendapatkan data dari tabel dataBarang dan menyimpannya dalam array
    const tabelData = [];
    const tableBody = document.getElementById('dataBarang').getElementsByTagName('tbody')[0];
    const rows = tableBody.getElementsByTagName('tr');
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const nama = row.cells[0].textContent;
        const jumlah = parseInt(row.cells[1].getElementsByTagName('input')[0].value);
        const totalHarga = parseFloat(row.cells[2].textContent);
        const data = { nama, jumlah, totalHarga };
        tabelData.push(data);
    }

    // Mendapatkan nilai tanggal dari input
    const tanggalInput = document.getElementById('tanggalInput').value;

    // Ubah format tanggal menjadi "YYYY-MM-DD"
    const tanggalFormatted = tanggalInput.split("-").reverse().join("-");

    // Tambahkan tanggal_input ke setiap data transaksi
    for (let i = 0; i < tabelData.length; i++) {
        tabelData[i].tanggal_input = tanggalFormatted;
    }

    $.ajax({
        url: 'simpan_transaksi.php', // Ganti 'simpan_transaksi.php' dengan URL sesuai dengan backend Anda
        type: 'POST',
        data: JSON.stringify(tabelData),
        dataType: 'json',
        contentType: 'application/json',
        success: function (response) {
            // Panggil fungsi tampilkanDataTransaksi untuk menampilkan data transaksi terbaru
            tampilkanDataTransaksi(response);

            // Reset form dan data barang yang dipilih setelah transaksi berhasil disimpan
            document.getElementById('formTransaksi').reset();
            document.getElementById('dataBarang').getElementsByTagName('tbody')[0].innerHTML = '';
            document.getElementById('total').value = '';
            document.getElementById('searchBarang').value = '';
            barangDipilih = {};
            localStorage.setItem('dataTransaksi', JSON.stringify(response));

            // Tampilkan tombol "Cetak Bukti Pembayaran" setelah transaksi berhasil disimpan
            document.getElementById('cetakBuktiPembayaran').style.display = 'block';

            // Cetak bukti pembayaran secara otomatis
            cetakBuktiPembayaran();

            // Tampilkan alert "Transaksi berhasil disimpan!"
            alert('Transaksi berhasil disimpan!');
        },
        error: function () {
            // Jika terjadi kesalahan saat menyimpan transaksi
            // tampilkan pesan "Gagal menyimpan transaksi. Silahkan coba lagi."
            alert('Gagal menyimpan transaksi. Silahkan coba lagi.');
        }
    });
}
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


       // Event listener untuk saat formulir "Buat Transaksi" disubmit
       document.getElementById('formTransaksi').addEventListener('submit', function (event) {
            event.preventDefault();
            const pembayaran = parseFloat(document.getElementById('pembayaran').value);
            const totalHarga = parseFloat(document.getElementById('total').value);
            if (pembayaran < totalHarga) {
                alert('Uang kurang. Silahkan masukkan pembayaran yang cukup.');
            } else {
                const kembalian = pembayaran - totalHarga;
                document.getElementById('kembalian').value = kembalian.toFixed(2);
                kirimTransaksi();
            }
        });

             // Event listener untuk input pencarian barang
             const searchBarang = document.getElementById('searchBarang');
        searchBarang.addEventListener('input', function () {
            const keyword = this.value;
            if (keyword === '') {
                document.getElementById('hasil_pencarian').innerHTML = '';
            } else {
                cariBarang(keyword);
            }
        });

          // Event listener untuk deteksi tombol Enter pada input pencarian
          searchBarang.addEventListener('keypress', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                const keyword = this.value;
                if (keyword !== '') {
                    cariBarang(keyword);
                }
            }
        });

     
   // Panggil fungsi ambilDataTransaksi() saat halaman dimuat
   ambilDataTransaksi();

    // Panggil fungsi setupEventListeners() untuk menambahkan event listeners pada elemen yang diperlukan
    setupEventListeners();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
