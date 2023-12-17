<?php
// Koneksi ke database
include 'function.php';

// Ambil data dari POST request
$id_keluhan = $_POST['id_keluhan'];
$status_keluhan = $_POST['status_keluhan'];

// Update data tagihan
$query = "UPDATE keluhan SET status_keluhan='$status_keluhan' WHERE id_keluhan=$id_keluhan";
$result = mysqli_query($koneksi, $query);

if ($result) {
  echo "success";
} else {
  echo "error";
}
