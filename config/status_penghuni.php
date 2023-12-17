<?php
// Koneksi ke database
include 'function.php';

// Ambil data dari POST request
$id_penghuni = $_POST['id_penghuni'];
$status_penghuni = $_POST['status_penghuni'];

// Update data penghunis
$query = "UPDATE penghuni SET status_penghuni='$status_penghuni' WHERE id_penghuni=$id_penghuni";
$result = mysqli_query($koneksi, $query);

if ($result) {
  echo "Success";
} else {
  echo "Error";
}
