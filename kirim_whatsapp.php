<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['nomor_whatsapp'])) {
    // Ambil data dari form yang diinputkan sebelumnya
    $nama_penghuni = $_GET['nama_penghuni'];
    $alamat_penghuni = $_GET['alamat_penghuni'];
    $kontak_penghuni = $_GET['kontak_penghuni'];
    $email_penghuni = $_GET['email_penghuni'];
    $nomor_kamar_penghuni = $_GET['nomor_kamar_penghuni'];
    $tanggal_masuk_penghuni = $_GET['tanggal_masuk_penghuni'];
    $tanggal_keluar_penghuni = $_GET['tanggal_keluar_penghuni'];
    $tanda_jadi_penghuni = $_GET['tanda_jadi_penghuni'];
    $username_penghuni = $_GET['username_penghuni'];

    // Format nomor WhatsApp dengan mengganti angka 0 menjadi 62
    $nomorWhatsApp = preg_replace('/^0/', '62', $_GET['nomor_whatsapp']);

    // Pesan yang akan dikirim ke WhatsApp
    $pesan = "Wisma Mutiara Selaras\n";
    $pesan .= "Jl. Banteng Raya 3 Gg. Sadewa No. 5 Ngabean Kulon, Sinduharjo, Kec. Ngaglik, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55581\n\n";
    $pesan .= "Halo, " . $nama_penghuni . " Berikut Adalah Rincian Pendaftaran Anda:\n";
    $pesan .= "Nama: " . $nama_penghuni . "\n";
    $pesan .= "Alamat: " . $alamat_penghuni . "\n";
    $pesan .= "Kontak: " . $kontak_penghuni . "\n";
    $pesan .= "Email: " . $email_penghuni . "\n\n";
    $pesan .= "Nomor Kamar: " . $nomor_kamar_penghuni . "\n";
    $pesan .= "Tanggal Masuk/Check-In: " . $tanggal_masuk_penghuni . "\n";
    $pesan .= "Tanggal Keluar/Check-Out: " . $tanggal_keluar_penghuni . "\n";
    $pesan .= "Tanda Jadi: " . $tanda_jadi_penghuni . "\n\n";
    $pesan .= "Username: " . $username_penghuni . "\n";
    $pesan .= "Password: wms";

    // Buat link untuk membuka WhatsApp dengan pesan yang sudah diisi
    $url = "https://wa.me/{$nomorWhatsApp}?text=" . urlencode($pesan);

    // Arahkan pengguna ke link WhatsApp
    header("Location: $url");
    exit;
} else {
    // Jika tidak ada nomor WhatsApp yang diterima, arahkan kembali ke halaman sebelumnya
    header("Location: index.php?page=daftar_penghuni");
    exit;
}
