<?php
// database_functions.php

// Fungsi koneksi ke database
function connectDatabase()
{
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'toserba';

    $koneksi = new mysqli($host, $username, $password, $database);

    // Cek koneksi berhasil atau tidak
    if ($koneksi->connect_error) {
        die("Koneksi database gagal: " . $koneksi->connect_error);
    }

    return $koneksi;
}

// Fungsi untuk mendapatkan data transaksi dari database berdasarkan ID nota
function getDataTransaksiDariDatabase($idNota)
{
    $koneksi = connectDatabase();
    $query = "SELECT * FROM penjualan WHERE id_penjualan = '$idNota'";
    $result = $koneksi->query($query);

    $dataTransaksi = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $dataTransaksi[] = $row;
        }
    }

    return $dataTransaksi;
}

// Fungsi untuk mendapatkan total harga dari database berdasarkan ID nota
function getTotalHargaDariDatabase($idNota)
{
    $koneksi = connectDatabase();
    $query = "SELECT SUM(total) AS total_harga FROM penjualan WHERE id_penjualan = '$idNota'";
    $result = $koneksi->query($query);

    if ($result->num_rows > 0) {
        $totalHarga = $result->fetch_assoc()['total_harga'];
        return $totalHarga;
    }

    return 0;
}

// Fungsi untuk mendapatkan uang pembayaran dari database berdasarkan ID nota
function getUangPembayaranDariDatabase($idNota)
{
    $koneksi = connectDatabase();
    $query = "SELECT uang_pembayaran FROM penjualan WHERE id_penjualan = '$idNota'";
    $result = $koneksi->query($query);

    if ($result->num_rows > 0) {
        $uangPembayaran = $result->fetch_assoc()['uang_pembayaran'];
        return $uangPembayaran;
    }

    return 0;
}

// Fungsi untuk mendapatkan uang kembalian dari database berdasarkan ID nota
function getUangKembalianDariDatabase($idNota)
{
    $koneksi = connectDatabase();
    $query = "SELECT uang_kembalian FROM penjualan WHERE id_penjualan = '$idNota'";
    $result = $koneksi->query($query);

    if ($result->num_rows > 0) {
        $uangKembalian = $result->fetch_assoc()['uang_kembalian'];
        return $uangKembalian;
    }

    return 0;
}
?>
