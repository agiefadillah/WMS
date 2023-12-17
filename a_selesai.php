<?php
require '../config/function.php';

if (isset($_GET['id_kamar'])) {
    $status = $_GET['id_kamar'];
    $query = "DELETE FROM kamar WHERE id_kamar='$status' ";
    mysqli_query($koneksi, $query);
    header("Location: ../index.php?page=daftarkamar");
}

if (isset($_GET['id_penyewa'])) {
    $id_penyewa = $_GET['id_penyewa'];

    // Matikan sementara pengecekan foreign key
    mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS=0");

    $query_penyewa = mysqli_query($koneksi, "SELECT * FROM penyewa WHERE id_penyewa='$id_penyewa'");
    $data_penyewa = mysqli_fetch_assoc($query_penyewa);

    // Ambil id kamar dari data penyewa
    $id_kamar = $data_penyewa['id_kamar'];

    // Update status kamar menjadi tersedia
    mysqli_query($koneksi, "UPDATE kamar SET status_kamar='tersedia' WHERE id_kamar='$id_kamar'");

    // Hapus data penyewa
    mysqli_query($koneksi, "DELETE FROM penyewa WHERE id_penyewa='$id_penyewa'");

    mysqli_query($koneksi, "DELETE FROM pengaturan WHERE id_penyewa='$id_penyewa'");

    // Nyalakan kembali pengecekan foreign key
    mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS=1");

    header("Location: ../index.php?page=daftarpenghuni");
}


if (isset($_GET['id_transaksi'])) {
    $status = $_GET['id_transaksi'];
    $query = "DELETE FROM transaksi WHERE id_transaksi='$status' ";
    mysqli_query($koneksi, $query);
    header("Location: ../index.php?page=daftartransaksi");
}

if (isset($_GET['id_tagihan'])) {
    $status = $_GET['id_tagihan'];
    $query = "DELETE FROM tagihan WHERE id_tagihan='$status' ";
    mysqli_query($koneksi, $query);
    header("Location: ../index.php?page=daftar_tagihan");
}

if (isset($_GET['id_tamu'])) {
    $status = $_GET['id_tamu'];
    $query = "DELETE FROM tamu WHERE id_tamu='$status' ";
    mysqli_query($koneksi, $query);
    header("Location: ../index.php?page=daftar_tamu");
}

if (isset($_GET['id_keluhan'])) {
    $status = $_GET['id_keluhan'];
    $query = "DELETE FROM keluhan WHERE id_keluhan='$status' ";
    mysqli_query($koneksi, $query);
    header("Location: ../index.php?page=p_daftarkeluhan");
}

if (isset($_GET['id_pengeluaran'])) {
    $status = $_GET['id_pengeluaran'];
    $query = "DELETE FROM pengeluaran WHERE id_pengeluaran='$status' ";
    mysqli_query($koneksi, $query);
    header("Location: ../index.php?page=daftarpengeluaran");
}

if (isset($_GET['id_rekening'])) {
    $status = $_GET['id_rekening'];
    $query = "DELETE FROM rekening WHERE id_rekening='$status' ";
    mysqli_query($koneksi, $query);
    header("Location: ../index.php?page=daftarrekening");
}

if (isset($_GET['id_lokasi'])) {
    $status = $_GET['id_lokasi'];
    $query = "DELETE FROM lokasi_kamar WHERE id_lokasi='$status' ";
    mysqli_query($koneksi, $query);
    header("Location: ../index.php?page=tambah_lokasikamar");
}
