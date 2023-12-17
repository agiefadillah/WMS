<?php
// Koneksi ke database
include 'function.php';

// Ambil data dari POST request
$id_tagihan = $_POST['id_tagihan'];
$status_tagihan = $_POST['status_tagihan'];
$jumlah_tagihan = $_POST['jumlah_tagihan'];

// Tentukan data tagihan berdasarkan kategori tagihan yang dipilih
if ($status_tagihan === 'Belum Bayar') {
  $queryUpdateStatus = "UPDATE tagihan SET status_tagihan='Belum Bayar' WHERE id_tagihan=$id_tagihan";
  $queryUpdateJumlah = "UPDATE tagihan SET jumlah_bayar_tagihan=0, jumlah_sisa_tagihan=0 WHERE id_tagihan=$id_tagihan";

  mysqli_query($koneksi, $queryUpdateStatus);
  mysqli_query($koneksi, $queryUpdateJumlah);
} elseif ($status_tagihan === 'Lunas') {
  $queryUpdateStatus = "UPDATE tagihan SET status_tagihan='Lunas' WHERE id_tagihan=$id_tagihan";
  $queryUpdateJumlah = "UPDATE tagihan SET jumlah_bayar_tagihan=$jumlah_tagihan, jumlah_sisa_tagihan=0 WHERE id_tagihan=$id_tagihan";

  mysqli_query($koneksi, $queryUpdateStatus);
  mysqli_query($koneksi, $queryUpdateJumlah);
}

if ($result) {
  echo "Success";
} else {
  echo "Error";
}
