<?php
require 'config/function.php';

// Fungsi untuk memeriksa apakah kontak sudah terdaftar
function checkKontak($kontak)
{
    global $koneksi;

    $sql = "SELECT * FROM users WHERE kontak = '$kontak'";
    $result = $koneksi->query($sql);

    if ($result->num_rows > 0) {
        return true; // Kontak sudah terdaftar
    } else {
        return false; // Kontak belum terdaftar
    }
}

// Fungsi untuk memeriksa apakah email sudah terdaftar
function checkEmail($email)
{
    global $koneksi;

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $koneksi->query($sql);

    if ($result->num_rows > 0) {
        return true; // Email sudah terdaftar
    } else {
        return false; // Email belum terdaftar
    }
}

// Fungsi untuk memeriksa apakah username sudah tersedia
function checkUsername($username)
{
    global $koneksi;

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $koneksi->query($sql);

    if ($result->num_rows > 0) {
        return true; // Username tidak tersedia
    } else {
        return false; // Username tersedia
    }
}

// Menerima data dari request AJAX
if (isset($_GET['check'])) {
    if ($_GET['check'] == 'kontak') {
        $kontak = $_GET['kontak'];
        if (checkKontak($kontak)) {
            echo "Kontak Sudah Terdaftar <br> Harap Gunakan Kontak Lain";
        }
    } elseif ($_GET['check'] == 'email') {
        $email = $_GET['email'];
        if (checkEmail($email)) {
            echo "Email Sudah Terdaftar <br> Harap Gunakan Email Lain";
        }
    } elseif ($_GET['check'] == 'username') {
        $username = $_GET['username'];
        if (checkUsername($username)) {
            echo "Username Tidak Tersedia <br> Harap Gunakan Username Lain";
        }
    }
}

$koneksi->close();
