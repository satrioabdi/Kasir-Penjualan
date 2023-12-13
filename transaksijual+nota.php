<?php
// Panggil file tcpdf_include.php untuk menghubungkan TCPDF
require_once('tcpdf/tcpdf.php');

// Fungsi untuk membuat dan menyimpan file PDF
function buatBuktiPembayaranPDF($idNota, $dataTransaksi, $totalHarga, $uangPembayaran, $uangKembalian)
{
    // Buat objek TCPDF
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

    // Atur informasi dokumen
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Nama Anda');
    $pdf->SetTitle('Bukti Pembayaran');
    $pdf->SetSubject('Bukti Pembayaran Transaksi');

    // Atur header dan footer (opsional)
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // Atur header
    $pdf->SetHeaderData('', 0, 'Bukti Pembayaran Transaksi', 'Tanggal: ' . date('d/m/Y'));

    // Atur font
    $pdf->SetFont('helvetica', '', 12);

    // Tambahkan halaman baru
    $pdf->AddPage();

    // Tampilkan informasi transaksi
    $html = '
    <h1>BUKTI PEMBAYARAN TRANSAKSI</h1>
    <p>Tanggal: ' . date('d/m/Y') . '</p>
    <p>ID Nota: ' . $idNota . '</p>
    <table border="1">
      <tr>
        <th>ID Barang</th>
        <th>Nama Barang</th>
        <th>Jumlah</th>
        <th>Subtotal</th>
      </tr>
  ';

    foreach ($dataTransaksi as $barang) {
        $html .= '
      <tr>
        <td>' . $barang['id_barang'] . '</td>
        <td>' . $barang['nama_barang'] . '</td>
        <td>' . $barang['jumlah'] . '</td>
        <td>' . ($barang['harga_jual'] * $barang['jumlah']) . '</td>
      </tr>
    ';
    }

    $html .= '
      <tr>
        <td colspan="3" align="right">Total Harga:</td>
        <td>' . $totalHarga . '</td>
      </tr>
      <tr>
        <td colspan="3" align="right">Uang Pembayaran:</td>
        <td>' . $uangPembayaran . '</td>
      </tr>
      <tr>
        <td colspan="3" align="right">Uang Kembalian:</td>
        <td>' . $uangKembalian . '</td>
      </tr>
    </table>
  ';

    // Tampilkan konten HTML di halaman PDF
    $pdf->writeHTML($html, true, false, true, false, '');

    // Simpan file PDF
    $pdf->Output('bukti_pembayaran.pdf', 'D');
}

// Periksa apakah ada parameter GET "cetak" yang bernilai "1"
if (isset($_GET['cetak']) && $_GET['cetak'] === '1') {
    // Ambil data transaksi dan informasi lainnya dari server (sesuaikan dengan implementasi Anda)
    $dataTransaksi = array(); // Ganti dengan data transaksi yang sesuai dari server
    $totalHarga = 0; // Ganti dengan total harga yang sesuai dari server
    $uangPembayaran = 0; // Ganti dengan uang pembayaran yang sesuai dari server
    $uangKembalian = 0; // Ganti dengan uang kembalian yang sesuai dari server
    $idNota = 0; // Ganti dengan ID nota yang sesuai dari server

    // Panggil fungsi untuk membuat dan menyimpan file PDF dan mengatur output sebagai 'D' (download)
    buatBuktiPembayaranPDF($idNota, $dataTransaksi, $totalHarga, $uangPembayaran, $uangKembalian);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transaksi Penjualan</title>
  <style>
    /* Styles for the entire page */
    body {
      font-family: Arial, sans-serif;
      background-color: #f1f1f1;
      margin: 0;
      padding: 0;
    }

    /* Styles for the header */
    h1 {
      text-align: center;
      color: #333;
      padding: 20px 0;
    }

    /* Styles for the Keranjang table */
    h2 {
      margin-top: 20px;
    }

    table {
      margin: 10px 0;
      width: 100%;
      background-color: #fff;
      border-radius: 4px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    th,
    td {
      padding: 10px;
      border: 1px solid #ccc;
      text-align: center;
    }

    th {
      background-color: #4CAF50;
      color: #fff;
    }

    tr:hover {
      background-color: #f2f2f2;
    }

    .subtotal {
      font-weight: bold;
    }

    /* Styles for the search bar */
    .search-container {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-top: 10px;
    }

    .search-container label {
      margin-right: 10px;
    }

    .search-container input[type="text"] {
      padding: 8px;
      width: 300px;
      border: none;
      border-radius: 4px;
    }

    .search-container button {
      padding: 8px 16px;
      background-color: #4CAF50;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .search-container button:hover {
      background-color: #45a049;
    }

    /* Styles for the Total Harga and Pembayaran */
    .total-container {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-top: 10px;
    }

    .total-container div {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin: 0 20px;
    }

    .total-container div label {
      margin-bottom: 5px;
    }

    .total-container input[type="number"],
    .total-container input[type="text"] {
      padding: 8px;
      width: 200px;
      border: none;
      border-radius: 4px;
    }

    /* Styles for the Checkout button */
    .center-container {
    text-align: center;
    margin-top: 20px;
}

.checkout {
    display: inline-block;
    margin-right: 10px; /* Atur jarak ke kanan pada tombol Bayar */
    background-color: #4CAF50; /* Warna hijau untuk tombol Bayar */
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: 8px 16px;
    text-decoration: none;
    cursor: pointer;
}

.checkout:hover {
    background-color: #45a049; /* Warna hover hijau untuk tombol Bayar */
}

.home-button {
    display: inline-block;
    margin-left: 10px; /* Atur jarak ke kiri pada tautan Cetak Bukti Pembayaran */
    background-color: #007bff; /* Warna biru untuk tombol Cetak */
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: 8px 16px;
    text-decoration: none;
    cursor: pointer;
}

.home-button:hover {
    background-color: #0056b3; /* Warna hover biru untuk tombol Cetak */
}
    /* Styles for the Uang Kembalian field (read-only) */
    input[readonly] {
      background-color: #f1f1f1;
    }
  </style>
</head>

<body>
<?php include 'sidebar.php'; ?>
    <?php include 'headers.php'; ?>
    <div class="content">
  <div class="container">
  <h1>Transaksi Penjualan</h1>

   <!-- Kolom Search -->
  <div class="search-container">
    <label for="searchBarang">Cari Barang:</label>
    <input type="text" id="searchBarang" placeholder="Masukkan nama barang...">
    <button onclick="searchBarang()">Cari</button>
  </div>

  <!-- Tabel Hasil Pencarian -->
  <h2>Hasil Pencarian</h2>
  <table>
    <thead>
      <tr>
        <th>ID Barang</th>
        <th>Nama Barang</th>
        <th>Harga Jual</th>
        <th>Tambah ke Keranjang</th>
      </tr>
    </thead>
    <tbody id="hasilPencarian">
      <!-- Hasil pencarian akan ditampilkan di sini -->
    </tbody>
  </table>

  <!-- Tabel Keranjang -->
  <h2>Keranjang</h2>
  <table>
    <thead>
      <tr>
        <th>ID Barang</th>
        <th>Nama Barang</th>
        <th>Harga Jual</th>
        <th>Jumlah</th>
        <th>Subtotal</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody id="keranjang">
      <!-- Isi keranjang akan ditampilkan di sini -->
    </tbody>
  </table>

  <!-- Total Harga dan Pembayaran -->
  <div class="total-container">
    <div>
      <label for="totalHarga">Total Harga:</label>
      <input type="text" id="totalHarga" readonly>
    </div>
    <div>
      <label for="uangPembayaran">Uang Pembayaran:</label>
      <input type="number" id="uangPembayaran" onchange="hitungKembalian()" min="0">
    </div>
    <div>
      <label for="uangKembalian">Uang Kembalian:</label>
      <input type="text" id="uangKembalian" readonly>
    </div>
  </div>

  <!-- Tombol Checkout -->
  <div class="center-container">
    <button class="checkout" onclick="checkout()">Bayar</button>
    <a class="home-button" href="generate_pdf.php?cetak=1&idNota=<?php echo $idNota; ?>">Cetak Bukti Pembayaran</a>
  </div>

  <!-- Tambahkan sebelum </body> di halaman transaksi_penjualan.php -->
  <script>
    // Fungsi untuk melakukan pencarian barang
    function searchBarang() {
      const keyword = document.getElementById('searchBarang').value;
      fetch(`search_barang.php?keyword=${keyword}`)
        .then(response => response.json())
        .then(data => displaySearchResults(data)); // Ganti displaySearchResults() dengan tambahKeKeranjang()
    }

    // Fungsi untuk menampilkan hasil pencarian di tabel
    function displaySearchResults(data) {
      const tableBody = document.getElementById('hasilPencarian');
      tableBody.innerHTML = '';

      data.forEach(barang => {
        const row = document.createElement('tr');
        row.innerHTML = `
        <td>${barang.id_barang}</td>
        <td>${barang.nama_barang}</td>
        <td>${barang.harga_jual}</td>
        <td><button onclick="tambahKeKeranjang('${barang.id_barang}', '${barang.nama_barang}', ${barang.harga_jual})">Tambah ke Keranjang</button></td>
      `;
        tableBody.appendChild(row);
      });
    }

    // Fungsi untuk menambahkan barang ke keranjang
    function tambahKeKeranjang(idBarang, namaBarang, hargaJual) {
      const tableBody = document.getElementById('keranjang');

      // Periksa apakah barang sudah ada di keranjang
      const existingRow = tableBody.querySelector(`tr[data-id="${idBarang}"]`);
      if (existingRow) {
        // Jika barang sudah ada, tambahkan jumlahnya
        const inputJumlah = existingRow.querySelector('td:nth-child(4) input');
        const jumlah = parseInt(inputJumlah.value) + 1;
        inputJumlah.value = jumlah;
        hitungSubtotal(inputJumlah, hargaJual);
      } else {
        // Jika barang belum ada, tambahkan sebagai item baru di keranjang
        const row = document.createElement('tr');
        row.setAttribute('data-id', idBarang);
        row.innerHTML = `
        <td>${idBarang}</td>
        <td>${namaBarang}</td>
        <td>${hargaJual}</td>
        <td><input type="number" min="1" value="1" onchange="hitungSubtotal(this, ${hargaJual})"></td>
        <td class="subtotal">${hargaJual}</td>
        <td><button class="btn btn-danger btn-sm" onclick="hapusBarang(this)">Hapus</button></td>
      `;
        tableBody.appendChild(row);
      }

      hitungTotalHarga();

      // Clear the search input field after adding an item to the cart
      document.getElementById('searchBarang').value = '';

      // Clear the search results table after adding an item to the cart
      document.getElementById('hasilPencarian').innerHTML = '';
    }
    
    // Fungsi untuk menghitung subtotal dan total harga
    function hitungSubtotal(inputJumlah, hargaJual) {
      const jumlah = parseInt(inputJumlah.value);
      const subtotal = jumlah * hargaJual;
      const row = inputJumlah.parentElement.parentElement;
      row.querySelector('.subtotal').textContent = subtotal;

      hitungTotalHarga();
    }

    function hitungTotalHarga() {
      const subtotalElements = document.querySelectorAll('.subtotal');
      let totalHarga = 0;

      subtotalElements.forEach(subtotalElement => {
        totalHarga += parseInt(subtotalElement.textContent);
      });

      document.getElementById('totalHarga').value = totalHarga;
      hitungKembalian();
    }

    // Fungsi untuk menghitung uang kembalian
    function hitungKembalian() {
      const totalHarga = parseInt(document.getElementById('totalHarga').value);
      const uangPembayaran = parseInt(document.getElementById('uangPembayaran').value);
      const uangKembalian = uangPembayaran - totalHarga;

      document.getElementById('uangKembalian').value = uangKembalian;
    }

    // Fungsi untuk menghapus barang dari keranjang
    function hapusBarang(button) {
      const row = button.parentElement.parentElement;
      row.remove();
      hitungTotalHarga();
    }

   // Fungsi untuk checkout dan menyimpan data ke database
function checkout() {
    const keranjang = document.querySelectorAll('#keranjang tr');
    const dataTransaksi = [];
    const totalHarga = parseInt(document.getElementById('totalHarga').value);
    const uangPembayaran = parseInt(document.getElementById('uangPembayaran').value);

    // Validasi input uangPembayaran
    if (isNaN(uangPembayaran) || uangPembayaran < totalHarga) {
        alert('Uang pembayaran kurang!');
        return; // Mencegah eksekusi lebih lanjut jika uangPembayaran tidak valid
    }

    keranjang.forEach(row => {
        const idBarang = row.querySelector('td:nth-child(1)').textContent;
        const jumlah = row.querySelector('td:nth-child(4) input').value;
        dataTransaksi.push({ id_barang: idBarang, jumlah });
    });

    // Kirim dataTransaksi ke server untuk disimpan ke database (ganti dengan endpoint Anda)
    fetch('simpan_transaksi.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(dataTransaksi)
    })
    .then(response => response.json())
    .then(data => {
        // Ambil ID Nota dari respons
        const idNota = data.id_nota;

        // Tampilkan pesan transaksi berhasil
        alert('Transaksi berhasil! ID Nota: ' + idNota);

        // Cetak bukti pembayaran
        cetakBuktiPembayaran(idNota);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}



// Fungsi untuk mencetak bukti pembayaran
function cetakBuktiPembayaran(idNota) {
    // Fetch file PDF dan ubah respons menjadi blob (binary large object)
    fetch(`generate_pdf.php?cetak=1&idNota=${idNota}`)
        .then(response => response.blob())
        .then(blob => {
            // Buat URL objek blob
            const urlBlob = window.URL.createObjectURL(blob);

            // Buat elemen <a> untuk cetak bukti pembayaran
            const link = document.createElement('a');
            link.href = urlBlob;
            link.target = '_blank'; // Buka link dalam tab/baru
            link.click();

            // Hapus URL objek blob setelah proses selesai
            window.URL.revokeObjectURL(urlBlob);
        })
        .catch(error => {
            console.error('Error:', error);
        });
}


      // Fungsi untuk mencari barang berdasarkan keseluruhan huruf
    function filterBarangByLetter() {
      const keyword = document.getElementById('searchBarang').value.toLowerCase();
      const hasilPencarian = document.querySelectorAll('#hasilPencarian tr');

      hasilPencarian.forEach(barang => {
        const namaBarang = barang.querySelector('td:nth-child(2)').textContent.toLowerCase();
        if (namaBarang.includes(keyword)) {
          barang.style.display = ''; // Tampilkan barang jika nama mengandung huruf yang ditekan
        } else {
          barang.style.display = 'none'; // Sembunyikan barang jika nama tidak mengandung huruf yang ditekan
        }
      });
    }

    // Event listener untuk input field pencarian
    document.getElementById('searchBarang').addEventListener('input', filterBarangByLetter);
  </script>

</body>

</html>