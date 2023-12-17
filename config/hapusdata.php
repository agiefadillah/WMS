<?php
require 'function.php';

if (isset($_GET['id_lokasi'])) {
    $status = $_GET['id_lokasi'];
    $query = "DELETE FROM lokasi_kamar WHERE id_lokasi='$status'";
    mysqli_query($koneksi, $query);
    header("Location: ../index.php?page=daftar_lokasi_kamar");
}

if (isset($_GET['id_kamar'])) {
    $status = $_GET['id_kamar'];
    $query = "DELETE FROM kamar WHERE id_kamar='$status'";
    mysqli_query($koneksi, $query);
    header("Location: ../index.php?page=daftar_kamar");
}

if (isset($_GET['id_penghuni'])) {
    $id_penghuni = $_GET['id_penghuni'];

    // Matikan sementara pengecekan foreign key
    mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS=0");

    // Cari data penghuni berdasarkan id_penghuni
    $query_penghuni = "SELECT id_users FROM penghuni WHERE id_penghuni = '$id_penghuni'";
    $result_penghuni = mysqli_query($koneksi, $query_penghuni);

    if ($result_penghuni) {
        $row_penghuni = mysqli_fetch_assoc($result_penghuni);
        $id_users = $row_penghuni['id_users'];

        // Hapus data penghuni dari tabel penghuni
        $query_delete_penghuni = "DELETE FROM penghuni WHERE id_penghuni = '$id_penghuni'";
        mysqli_query($koneksi, $query_delete_penghuni);

        // Hapus data pengguna (users) terkait dari tabel users
        $query_delete_users = "DELETE FROM users WHERE id_users = '$id_users'";
        mysqli_query($koneksi, $query_delete_users);

        // Redirect ke halaman daftar_kamar setelah menghapus data
        header("Location: ../index.php?page=daftar_penghuni");
    } else {
        // Jika data penghuni tidak ditemukan
        echo "Data penghuni tidak ditemukan.";
    }
    // Nyalakan kembali pengecekan foreign key
    mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS=1");
}

if (isset($_GET['id_rekening'])) {
    $status = $_GET['id_rekening'];
    $query = "DELETE FROM rekening WHERE id_rekening='$status' ";
    mysqli_query($koneksi, $query);
    header("Location: ../index.php?page=daftar_rekening");
}

if (isset($_GET['id_pengeluaran'])) {
    $status = $_GET['id_pengeluaran'];
    $query = "DELETE FROM pengeluaran WHERE id_pengeluaran='$status' ";
    mysqli_query($koneksi, $query);
    header("Location: ../index.php?page=daftar_pengeluaran");
}

if (isset($_GET['id_kendaraan'])) {
    $status = $_GET['id_kendaraan'];
    $query = "DELETE FROM kendaraan WHERE id_kendaraan='$status' ";
    mysqli_query($koneksi, $query);
    header("Location: ../index.php?page=daftar_kendaraan");
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
    header("Location: ../index.php?page=daftar_keluhan_penghuni");
}
