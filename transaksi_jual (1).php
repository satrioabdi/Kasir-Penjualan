<?php
require 'db_config.php';


// Jika terdapat data keranjangBelanja yang dikirimkan dari halaman transaksi penjualan
if (isset($_POST['keranjangBelanja']) && !empty($_POST['keranjangBelanja'])) {
  // Koneksi ke database (gantikan 'nama_database', 'username', 'password' sesuai dengan pengaturan Anda)
  $koneksi = new mysqli('localhost', 'username', 'password', 'nama_database');

  // Cek koneksi database
  if ($koneksi->connect_error) {
    die('Koneksi database gagal: ' . $koneksi->connect_error);
  }

  // Escape data dari keranjangBelanja untuk menghindari SQL Injection
  $keranjangBelanja = $_POST['keranjangBelanja'];
  $escapedKeranjangBelanja = array();
  foreach ($keranjangBelanja as $item) {
    $id_barang = $koneksi->real_escape_string($item['id_barang']);
    $jumlah = (int) $item['jumlah'];
    $total = (int) $item['subtotal'];
    $escapedKeranjangBelanja[] = "('$id_barang', '$id_member', $jumlah, $total, NOW())";
  }

  // Buat query untuk melakukan INSERT ke tabel penjualan
  $query = "INSERT INTO `penjualan` (`id_penjualan`,`id_barang`, `id_member`, `jumlah`, `total`, `tanggal_input`) VALUES ";
  $query .= implode(", ", $escapedKeranjangBelanja);

  // Jalankan query
  if ($koneksi->query($query) === TRUE) {
    // Jika INSERT berhasil, redirect ke halaman sukses atau tampilkan pesan sukses
    header('Location: transaksi_sukses.php');
    exit;
  } else {
    // Jika terjadi kesalahan saat melakukan INSERT, tampilkan pesan error atau redirect ke halaman error
    echo 'Error: ' . $query . '<br>' . $koneksi->error;
    // atau
    // header('Location: transaksi_error.php');
    // exit;
  }

  // Tutup koneksi ke database
  $koneksi->close();
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Transaksi Penjualan</title>
  <link rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    .container {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
    }

    h1,
    h2 {
      text-align: center;
    }

    .search-bar {
      display: flex;
      margin-bottom: 20px;
    }

    .search-bar input {
      flex-grow: 1;
      padding: 8px;
      border: 1px solid #ccc;
    }

    .search-bar button {
      padding: 8px 16px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
    }

    .barang-list {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      grid-gap: 20px;
    }

    .barang-item {
      border: 1px solid #ccc;
      padding: 10px;
    }

    .keranjang-belanja {
      margin-top: 40px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      padding: 8px;
      text-align: center;
      border: 1px solid #ccc;
    }

    button {
      padding: 8px 16px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
      float: right;
      margin-top: 10px;
    }
  </style>
</head>

<body>
<?php include 'sidebar.php'; ?>
    <?php include 'headers.php'; ?>
    <div class="content">
  <div class="container">
    <h1>Transaksi Penjualan</h1>
    <div class="search-bar">
      <input type="text" id="searchInput" placeholder="Cari barang...">
      <button onclick="searchBarang()">Cari</button>
    </div>
    <div class="barang-list">
      <!-- Daftar barang hasil pencarian akan ditampilkan di sini -->
    </div>
    <div class="keranjang-belanja">
      <h2>Keranjang Belanja</h2>
      <table>
        <thead>
          <tr>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="keranjangTableBody">
          <!-- Daftar barang yang ada di keranjang akan ditampilkan di sini -->
        </tbody>
      </table>
      <div class="total-harga">
        <p>Total Harga: <span id="totalHarga">Rp 0</span></p>
      </div>
      <div class="uang-pembayaran">
        <label for="inputUang">Uang Pembayaran:</label>
        <input type="number" id="inputUang" min="0" onchange="hitungKembalian()">
      </div>
      <div class="uang-kembalian">
        <p>Uang Kembalian: <span id="uangKembalian">Rp 0</span></p>
        <div class="keranjang-belanja">
          <!-- ... konten lainnya ... -->
          <form method="post" action="">
            <!-- Simpan data keranjangBelanja dalam input hidden untuk dikirimkan ke server -->
            <input type="hidden" name="keranjangBelanja" id="keranjangBelanjaInput">
            <button type="submit">Checkout</button>
        </div>
        <script>

          function getBarangListFromDatabase(callback) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
              if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                  var barangList = JSON.parse(xhr.responseText);
                  callback(barangList);
                } else {
                  console.error('Terjadi kesalahan saat mengambil data barang.');
                }
              }
            };
            xhr.open('GET', 'get_barang.php', true);
            xhr.send();
          }

          function showBarangList(keyword) {
            const barangListDiv = document.querySelector('.barang-list');
            barangListDiv.innerHTML = '';

            getBarangListFromDatabase(function (barangList) {
              const filteredBarangList = barangList.filter(barang => barang.nama_barang.toLowerCase().includes(keyword));
              filteredBarangList.forEach(barang => {
                const barangItemDiv = document.createElement('div');
                barangItemDiv.classList.add('barang-item');
                barangItemDiv.innerHTML = `
        <h3>${barang.nama_barang}</h3>
        <p>Harga Jual: Rp ${barang.harga_jual}</p>
        <button onclick="addToKeranjang('${barang.id_barang}', '${barang.nama_barang}', ${barang.harga_jual})">Masukkan Keranjang</button>
      `;
                barangListDiv.appendChild(barangItemDiv);
              });
            });
          }


          function searchBarang() {
            const keyword = document.getElementById('searchInput').value.toLowerCase();
            showBarangList(keyword);
          }

          function addToKeranjang(id_barang, nama, harga) {
            const keranjangTableBody = document.getElementById('keranjangTableBody');
            const row = keranjangTableBody.insertRow();
            const namaCell = row.insertCell(0);
            const hargaCell = row.insertCell(1);
            const jumlahCell = row.insertCell(2);
            const subtotalCell = row.insertCell(3);
            const hapusCell = row.insertCell(4);

            namaCell.innerHTML = nama;
            hargaCell.innerHTML = `Rp ${harga}`;
            jumlahCell.innerHTML = `<input type="number" value="1" min="1" onchange="updateSubtotal(this, ${harga})">`;
            subtotalCell.innerHTML = `Rp ${harga}`;
            hapusCell.innerHTML = '<button onclick="hapusBarang(this)">Hapus</button>';

            // Tambahkan data barang ke dalam keranjangBelanja
            const jumlah = parseInt(jumlahCell.querySelector('input').value);
            const subtotal = jumlah * harga;
            const item = {
              id_barang: id_barang, // Simpan id_barang dari tabel barang ke dalam keranjangBelanja
              nama: nama,
              harga: harga,
              jumlah: jumlah,
              subtotal: subtotal,
            };
            keranjangBelanja.push(item);

            // Perbarui total harga semua barang di keranjang
            hitungTotalHarga();
          }

          function updateSubtotal(input, harga) {
            const jumlah = parseInt(input.value);
            const subtotal = jumlah * harga;
            const row = input.parentNode.parentNode;
            const subtotalCell = row.cells[3];
            subtotalCell.innerHTML = `Rp ${subtotal}`;

            // Perbarui total harga semua barang di keranjang
            hitungTotalHarga();
          }

          function hitungTotalHarga() {
            const keranjangTableBody = document.getElementById('keranjangTableBody');
            const rows = keranjangTableBody.getElementsByTagName('tr');
            let totalHarga = 0;

            for (let i = 0; i < rows.length; i++) {
              const row = rows[i];
              const subtotalCell = row.cells[3];
              const subtotal = parseInt(subtotalCell.innerText.replace('Rp ', ''));
              totalHarga += subtotal;
            }

            const totalHargaSpan = document.getElementById('totalHarga');
            totalHargaSpan.innerText = `Rp ${totalHarga}`;

            // Perbarui uang kembalian
            hitungKembalian();
          }

          function hitungKembalian() {
            const inputUang = document.getElementById('inputUang');
            const totalHarga = parseInt(document.getElementById('totalHarga').innerText.replace('Rp ', ''));
            const uangKembalian = inputUang.value - totalHarga;

            const uangKembalianSpan = document.getElementById('uangKembalian');
            uangKembalianSpan.innerText = `Rp ${uangKembalian}`;
          }

          function hapusBarang(button) {
            const row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
          }

          function resetKeranjang() {
            keranjangBelanja.length = 0;
            const keranjangTableBody = document.getElementById('keranjangTableBody');
            keranjangTableBody.innerHTML = '';
            hitungTotalHarga();
          }
          function checkout() {
            // Pastikan bahwa keranjangBelanja tidak kosong
            if (keranjangBelanja.length === 0) {
              alert("Keranjang belanja masih kosong. Tambahkan barang terlebih dahulu.");
              return;
            }

            // Masukkan data keranjangBelanja ke dalam input hidden sebelum melakukan submit form
            const keranjangBelanjaInput = document.getElementById('keranjangBelanjaInput');
            keranjangBelanjaInput.value = JSON.stringify(keranjangBelanja);

            // Submit form untuk menyimpan data checkout ke dalam database
            const form = document.querySelector('form');
            form.submit();
          }


        </script>
</body>

</html>