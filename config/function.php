<?php
date_default_timezone_set("Asia/Jakarta");
include 'koneksi.php';

function connectDB()
{
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "bismillah";

    $koneksi = mysqli_connect($host, $username, $password, $database);

    if (mysqli_connect_errno()) {
        die("Failed to connect to database: " . mysqli_connect_error());
    }

    return $koneksi;
}

function tampildata($data)
{
    if (empty($data) || $data == 0) {
        return "-";
    } else {
        return $data;
    }
}

// Fungsi untuk mengganti angka 0 dengan 62 pada nomor WhatsApp
function formatWhatsAppNumber($number)
{
    return preg_replace('/^0/', '62', $number);
}

function hitungTanggalKeluar($tanggal_masuk, $durasi_sewa, $periode_sewa)
{
    // Ubah tanggal masuk menjadi objek DateTime
    $tanggal_masuk_obj = new DateTime($tanggal_masuk);

    // Hitung tanggal keluar berdasarkan periode sewa dan durasi sewa
    if ($periode_sewa === 'bulan') {
        $tanggal_masuk_obj->add(new DateInterval('P' . $durasi_sewa . 'M'));
    } elseif ($periode_sewa === 'tahun') {
        $tanggal_masuk_obj->add(new DateInterval('P' . $durasi_sewa . 'Y'));
    }

    // Format tanggal keluar sebagai string dalam format Y-m-d
    $tanggal_keluar = $tanggal_masuk_obj->format('Y-m-d');

    return $tanggal_keluar;
}

function tambahLokasiKamar($data)
{
    global $koneksi;

    $namaLokasi = $data['nama_lokasi'];

    // Cek apakah lokasi kamar sudah ada dalam database
    $queryCek = "SELECT * FROM lokasi_kamar WHERE nama_lokasi = '$namaLokasi'";
    $resultCek = mysqli_query($koneksi, $queryCek);
    if (mysqli_num_rows($resultCek) > 0) {
        // Lokasi kamar sudah ada, kembalikan nilai 0 untuk menunjukkan gagal tambah data
        return 0;
    }

    // Tambahkan lokasi kamar baru
    $queryTambah = "INSERT INTO lokasi_kamar (nama_lokasi) VALUES ('$namaLokasi')";
    $resultTambah = mysqli_query($koneksi, $queryTambah);
    if ($resultTambah) {
        // Data berhasil ditambahkan, kembalikan jumlah baris yang terpengaruh
        return mysqli_affected_rows($koneksi);
    } else {
        // Terjadi kesalahan saat menambahkan data
        return -1;
    }

    mysqli_close($koneksi);
}

function ubahLokasiKamar($data)
{
    global $koneksi;

    $idLokasi = $data['id_lokasi'];
    $namaLokasi = $data['nama_lokasi'];

    // Cek apakah lokasi dengan nama yang sama sudah ada
    $queryCek = "SELECT id_lokasi FROM lokasi_kamar WHERE nama_lokasi = '$namaLokasi' AND id_lokasi <> '$idLokasi'";
    $resultCek = mysqli_query($koneksi, $queryCek);

    if (mysqli_num_rows($resultCek) > 0) {
        // Lokasi sudah ada, maka return 0
        return 0;
    } else {
        // Lokasi belum ada, lanjutkan dengan proses update
        $queryUpdate = "UPDATE lokasi_kamar SET nama_lokasi = '$namaLokasi' WHERE id_lokasi = '$idLokasi'";
        $resultUpdate = mysqli_query($koneksi, $queryUpdate);

        if ($resultUpdate) {
            // Data berhasil diubah, maka return 1
            // return 1;
            return mysqli_affected_rows($koneksi);
        } else {
            // Gagal mengubah data, maka return -1
            return -1;
        }
    }
}

function tambahKamar($data)
{
    global $koneksi;

    $lokasi_kamar = $data['lokasi_kamar'];
    $nomor_kamar = $data['nomor_kamar'];
    $harga_sewa = $data['harga_sewa'];
    $status_kamar = $data['status_kamar'];

    // Dapatkan id_lokasi berdasarkan nama_lokasi
    $query_lokasi = "SELECT id_lokasi FROM lokasi_kamar WHERE nama_lokasi = '$lokasi_kamar'";
    $result_lokasi = mysqli_query($koneksi, $query_lokasi);
    $row_lokasi = mysqli_fetch_assoc($result_lokasi);
    $id_lokasi = $row_lokasi['id_lokasi'];

    // Periksa apakah data dengan nomor_kamar yang sama sudah ada dalam tabel
    $query_cek = "SELECT COUNT(*) FROM kamar WHERE nomor_kamar = '$nomor_kamar'";
    $result_cek = mysqli_query($koneksi, $query_cek);
    $row_cek = mysqli_fetch_array($result_cek);

    if ($row_cek[0] > 0) {
        // Jika data sudah ada, tampilkan pesan kesalahan
        return 0;
    }

    $query = "INSERT INTO kamar (nomor_kamar, id_lokasi, harga_kamar, status_kamar) 
        VALUES ('$nomor_kamar', '$id_lokasi', '$harga_sewa', '$status_kamar')";

    if (mysqli_query($koneksi, $query)) {
        return true; // Jika query berhasil dijalankan
    } else {
        // Jika terjadi kesalahan dalam menjalankan query
        return -1;
    }
}

function ubahKamar($data, $id_kamar)
{
    global $koneksi;

    $lokasi_kamar = mysqli_real_escape_string($koneksi, $data['lokasi_kamar']);
    $nomor_kamar = mysqli_real_escape_string($koneksi, $data['nomor_kamar']);
    $harga_sewa = mysqli_real_escape_string($koneksi, $data['harga_sewa']);
    $status_kamar = mysqli_real_escape_string($koneksi, $data['status_kamar']);

    // Dapatkan id_lokasi berdasarkan nama_lokasi
    $query_lokasi = "SELECT id_lokasi FROM lokasi_kamar WHERE nama_lokasi = '$lokasi_kamar'";
    $result_lokasi = mysqli_query($koneksi, $query_lokasi);
    $row_lokasi = mysqli_fetch_assoc($result_lokasi);
    $id_lokasi = $row_lokasi['id_lokasi'];

    // Periksa apakah data dengan nomor_kamar yang sama sudah ada dalam tabel (kecuali untuk data dengan id_kamar yang sama)
    $query_cek = "SELECT COUNT(*) FROM kamar WHERE nomor_kamar = '$nomor_kamar' AND id_kamar != '$id_kamar'";
    $result_cek = mysqli_query($koneksi, $query_cek);
    $row_cek = mysqli_fetch_array($result_cek);

    if ($row_cek[0] > 0) {
        return 0;
    }

    $query = "UPDATE kamar SET nomor_kamar = '$nomor_kamar', id_lokasi = '$id_lokasi', harga_kamar = '$harga_sewa', status_kamar = '$status_kamar' WHERE id_kamar = '$id_kamar'";

    if (mysqli_query($koneksi, $query)) {
        return true; // Jika query berhasil dijalankan
    } else {
        // Jika terjadi kesalahan dalam menjalankan query
        return -1;
    }
}

function tambahPenghuniTidakAktif($data)
{
    global $koneksi;

    date_default_timezone_set("Asia/Jakarta");

    $tanggalRegistrasi = $data['tanggal_registrasi'];

    $nama = mysqli_real_escape_string($koneksi, $data['nama_penghuni']);
    $alamat = mysqli_real_escape_string($koneksi, $data['alamat_penghuni']);
    $kontak = mysqli_real_escape_string($koneksi, $data['kontak_penghuni']);
    $email = mysqli_real_escape_string($koneksi, $data['email_penghuni']);
    $fp = $_FILES["identitas_penghuni"]["name"];
    $ext = pathinfo($fp, PATHINFO_EXTENSION);
    $random = crypt($fp, time());
    $sp = $_FILES["identitas_penghuni"]["tmp_name"];

    $idKamar = mysqli_real_escape_string($koneksi, $data['id_kamar']);
    $tanggalMasuk = $data['tanggal_masuk'];
    $durasiSewa = $data['durasi_sewa'];
    $periodeSewa = $data['periode_sewa'];

    $username = mysqli_real_escape_string($koneksi, $data['username']);
    $password = mysqli_real_escape_string($koneksi, $data['password']);
    // Enkripsi password menggunakan password_hash()
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $keterangan = mysqli_real_escape_string($koneksi, $data['keterangan_penghuni']);

    // Cek apakah file identitas_penghuni diupload
    if (!empty($fp) && !empty($sp)) {
        $folder = "imageupload/" . $random . '.' . $ext;
        $folderdb = "imageupload/" . $random . '.' . $ext;
        move_uploaded_file($sp, $folder);
    } else {
        $folderdb = "img/nodata.jpg";
    }

    // Menghitung tanggal keluar berdasarkan durasi sewa dan periode sewa
    $tanggalMasukObj = new DateTime($tanggalMasuk);
    if ($periodeSewa == 'bulan') {
        $tanggalKeluarObj = $tanggalMasukObj->add(new DateInterval('P' . $durasiSewa . 'M'));
    } elseif ($periodeSewa == 'tahun') {
        $tanggalKeluarObj = $tanggalMasukObj->add(new DateInterval('P' . $durasiSewa . 'Y'));
    } else {
        echo "Periode sewa tidak valid.";
        return false;
    }
    $tanggalKeluar = $tanggalKeluarObj->format('Y-m-d');

    // Periksa apakah username atau email sudah ada dalam tabel users
    $query_cek = "SELECT COUNT(*) as username_count, (SELECT COUNT(*) FROM users WHERE email = '$email') as email_count, (SELECT COUNT(*) FROM users WHERE kontak = '$kontak') as kontak_count FROM users WHERE username = '$username'";
    $result_cek = mysqli_query($koneksi, $query_cek);
    $row_cek = mysqli_fetch_array($result_cek);

    if ($row_cek['username_count'] > 0) {
        return 'username';
    } elseif ($row_cek['email_count'] > 0) {
        return 'email';
    } elseif ($row_cek['kontak_count'] > 0) {
        return 'kontak';
    }

    // Mulai transaksi
    mysqli_autocommit($koneksi, false);

    // Insert data penghuni ke tabel users
    $query_users = "INSERT INTO users (nama, kontak, email, alamat, tipe, username, password) VALUES ('$nama', '$kontak', '$email', '$alamat', 'penghuni', '$username', '$hashedPassword')";
    $result_users = mysqli_query($koneksi, $query_users);

    if (!$result_users) {
        mysqli_rollback($koneksi);
        echo "Error: " . mysqli_error($koneksi);
        return false;
    }

    $idUsers = mysqli_insert_id($koneksi);

    // Insert data penghuni ke tabel penghuni
    $query_penghuni = "INSERT INTO penghuni (id_users, id_kamar, tanggal_registrasi_penghuni, tanggal_masuk_penghuni, tanggal_keluar_penghuni, 
                                            identitas_penghuni, keterangan_penghuni, status_penghuni) 
    VALUES ('$idUsers', '$idKamar', '$tanggalRegistrasi', '$tanggalMasuk', '$tanggalKeluar', '$folderdb', '$keterangan', 'tidak aktif')";
    $result_penghuni = mysqli_query($koneksi, $query_penghuni);

    if (!$result_penghuni) {
        mysqli_rollback($koneksi);
        echo "Error: " . mysqli_error($koneksi);
        return false;
    }

    // Commit transaksi jika semua query berhasil dieksekusi
    mysqli_commit($koneksi);

    return true; // Sukses menambahkan data penghuni
}

function ubahPenghuniTidakAktif($data, $id_penghuni)
{
    global $koneksi;

    // Update tanggal keluar jika berubah
    $tanggal_registrasi = date("Y-m-d", strtotime($data['tanggal_registrasi']));
    $query_update_tanggal_registrasi = "UPDATE penghuni SET tanggal_registrasi_penghuni = '$tanggal_registrasi' WHERE id_penghuni = '$id_penghuni'";

    $query_update_tanggal_registrasi = mysqli_query($koneksi, $query_update_tanggal_registrasi);

    if (!$query_update_tanggal_registrasi) {
        echo "Error: " . mysqli_error($koneksi);
        return false;
    }

    // Update data penghuni pada tabel users
    $nama = htmlspecialchars($data['nama_penghuni']);
    $alamat = htmlspecialchars($data['alamat_penghuni']);
    $kontak = htmlspecialchars($data['kontak_penghuni']);
    $email = htmlspecialchars($data['email_penghuni']);

    // Cek apakah email sudah ada dalam tabel users
    $query_cek_email = "SELECT COUNT(*) as email_count FROM users WHERE email = '$email'";
    $result_cek_email = mysqli_query($koneksi, $query_cek_email);
    $row_cek_email = mysqli_fetch_assoc($result_cek_email);
    $email_count = $row_cek_email['email_count'];

    // Cek apakah kontak sudah ada dalam tabel users
    $query_cek_kontak = "SELECT COUNT(*) as kontak_count FROM users WHERE kontak = '$kontak'";
    $result_cek_kontak = mysqli_query($koneksi, $query_cek_kontak);
    $row_cek_kontak = mysqli_fetch_assoc($result_cek_kontak);
    $kontak_count = $row_cek_kontak['kontak_count'];

    if ($email_count > 0 && $email !== $data['email_penghuni']) {
        return 'email';
    } elseif ($kontak_count > 0 && $kontak !== $data['kontak_penghuni']) {
        return 'kontak';
    }

    $query_update_users = "UPDATE users SET nama = '$nama', alamat = '$alamat', kontak = '$kontak', email = '$email' WHERE id_users = (SELECT id_users FROM penghuni WHERE id_penghuni = '$id_penghuni')";

    $result_update_users = mysqli_query($koneksi, $query_update_users);

    if (!$result_update_users) {
        echo "Error: " . mysqli_error($koneksi);
        return false;
    }

    // Update tanggal keluar jika berubah
    $tanggal_keluar = date("Y-m-d", strtotime($data['tanggal_keluar']));
    $query_update_tanggal_keluar = "UPDATE penghuni SET tanggal_keluar_penghuni = '$tanggal_keluar' WHERE id_penghuni = '$id_penghuni'";

    $result_update_tanggal_keluar = mysqli_query($koneksi, $query_update_tanggal_keluar);

    if (!$result_update_tanggal_keluar) {
        echo "Error: " . mysqli_error($koneksi);
        return false;
    }

    // Update data penghuni pada tabel penghuni
    $tanggal_masuk = date("Y-m-d", strtotime($data['tanggal_masuk']));
    $durasi_sewa = (int)$data['durasi_sewa'];
    $periode_sewa = $data['periode_sewa'];

    // Hitung tanggal keluar berdasarkan tanggal masuk, durasi sewa, dan periode sewa
    $tanggal_keluar = hitungTanggalKeluar($tanggal_masuk, $durasi_sewa, $periode_sewa);
    $query_update_penghuni = "UPDATE penghuni SET id_kamar = '{$data['id_kamar']}', tanggal_masuk_penghuni = '$tanggal_masuk', tanggal_keluar_penghuni = '$tanggal_keluar' WHERE id_penghuni = '$id_penghuni'";

    $result_update_penghuni = mysqli_query($koneksi, $query_update_penghuni);

    if (!$result_update_penghuni) {
        echo "Error: " . mysqli_error($koneksi);
        return false;
    }

    // Update identitas_penghuni jika ada file yang diunggah
    if ($_FILES['identitas_penghuni']['error'] === UPLOAD_ERR_OK) {

        $nama_file = $_FILES['identitas_penghuni']['name'];
        $ext = pathinfo($nama_file, PATHINFO_EXTENSION);
        $random = time();
        $fotouploadrandomname = $random . '.' . $ext;
        $file_tmp = $_FILES['identitas_penghuni']['tmp_name'];
        $folder_upload = "imageupload/" . $fotouploadrandomname;

        if (move_uploaded_file($file_tmp, $folder_upload)) {
            // Delete the existing identitas_penghuni image
            $query_select_identitas = "SELECT identitas_penghuni FROM penghuni WHERE id_penghuni = '$id_penghuni'";
            $result_select_identitas = mysqli_query($koneksi, $query_select_identitas);
            $row = mysqli_fetch_assoc($result_select_identitas);
            unlink($row['identitas_penghuni']);

            $query_update_identitas = "UPDATE penghuni SET identitas_penghuni = '$folder_upload' WHERE id_penghuni = '$id_penghuni'";
            $result_update_identitas = mysqli_query($koneksi, $query_update_identitas);

            if (!$result_update_identitas) {
                echo "Error: " . mysqli_error($koneksi);
                return false;
            }
        } else {
            echo "Error: Gagal mengunggah file identitas penghuni.";
            return false;
        }
    }

    // Commit transaksi
    mysqli_commit($koneksi);
    mysqli_autocommit($koneksi, true);

    // Update keterangan_penghuni
    $keterangan = htmlspecialchars($data['keterangan_penghuni']);
    $query_update_keterangan = "UPDATE penghuni SET keterangan_penghuni = '$keterangan' WHERE id_penghuni = '$id_penghuni'";

    $result_update_keterangan = mysqli_query($koneksi, $query_update_keterangan);

    if (!$result_update_keterangan) {
        echo "Error: " . mysqli_error($koneksi);
        return false;
    }

    return true;
}

function tambahPenghuniAktif($data)
{
    global $koneksi;

    date_default_timezone_set("Asia/Jakarta");

    $tanggalRegistrasi = $data['tanggal_registrasi'];

    $nama = mysqli_real_escape_string($koneksi, $data['nama_penghuni']);
    $alamat = mysqli_real_escape_string($koneksi, $data['alamat_penghuni']);
    $kontak = mysqli_real_escape_string($koneksi, $data['kontak_penghuni']);
    $email = mysqli_real_escape_string($koneksi, $data['email_penghuni']);

    $fp = $_FILES["identitas_penghuni"]["name"];
    $ext = pathinfo($fp, PATHINFO_EXTENSION);
    $random = crypt($fp, time());
    $sp = $_FILES["identitas_penghuni"]["tmp_name"];

    $idKamar = mysqli_real_escape_string($koneksi, $data['id_kamar']);

    $username = mysqli_real_escape_string($koneksi, $data['username']);
    $password = mysqli_real_escape_string($koneksi, $data['password']);
    // Enkripsi password menggunakan password_hash()
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $keterangan = mysqli_real_escape_string($koneksi, $data['keterangan_penghuni']);

    // Cek apakah file identitas_penghuni diupload
    if (!empty($fp) && !empty($sp)) {
        $folder = "imageupload/" . $random . '.' . $ext;
        $folderdb = "imageupload/" . $random . '.' . $ext;
        move_uploaded_file($sp, $folder);
    } else {
        $folderdb = "img/nodata.jpg";
    }

    // Periksa apakah username atau email sudah ada dalam tabel users
    $query_cek = "SELECT COUNT(*) as username_count, (SELECT COUNT(*) FROM users WHERE email = '$email') as email_count, (SELECT COUNT(*) FROM users WHERE kontak = '$kontak') as kontak_count FROM users WHERE username = '$username'";
    $result_cek = mysqli_query($koneksi, $query_cek);
    $row_cek = mysqli_fetch_array($result_cek);

    if ($row_cek['username_count'] > 0) {
        return 'username';
    } elseif ($row_cek['email_count'] > 0) {
        return 'email';
    } elseif ($row_cek['kontak_count'] > 0) {
        return 'kontak';
    }

    // Mulai transaksi
    mysqli_autocommit($koneksi, false);

    // Insert data penghuni ke tabel users
    $query_users = "INSERT INTO users (nama, kontak, email, alamat, tipe, username, password) VALUES ('$nama', '$kontak', '$email', '$alamat', 'penghuni', '$username', '$hashedPassword')";
    $result_users = mysqli_query($koneksi, $query_users);

    if (!$result_users) {
        mysqli_rollback($koneksi);
        echo "Error: " . mysqli_error($koneksi);
        return false;
    }

    $idUsers = mysqli_insert_id($koneksi);

    // Insert data penghuni ke tabel penghuni
    $query_penghuni = "INSERT INTO penghuni (id_users, id_kamar, tanggal_registrasi_penghuni, tanggal_masuk_penghuni, tanggal_keluar_penghuni, 
                                            identitas_penghuni, keterangan_penghuni, status_penghuni) 
    VALUES ('$idUsers', '$idKamar', '$tanggalRegistrasi', NOW(), NOW(), '$folderdb', '$keterangan', 'aktif')";
    $result_penghuni = mysqli_query($koneksi, $query_penghuni);

    if (!$result_penghuni) {
        mysqli_rollback($koneksi);
        echo "Error: " . mysqli_error($koneksi);
        return false;
    }

    // Commit transaksi jika semua query berhasil dieksekusi
    mysqli_commit($koneksi);

    return true; // Sukses menambahkan data penghuni
}

function ubahPenghuniAktif($data, $id_penghuni)
{
    global $koneksi;

    // Update tanggal keluar jika berubah
    $tanggal_registrasi = date("Y-m-d", strtotime($data['tanggal_registrasi']));
    $query_update_tanggal_registrasi = "UPDATE penghuni SET tanggal_registrasi_penghuni = '$tanggal_registrasi' WHERE id_penghuni = '$id_penghuni'";

    $query_update_tanggal_registrasi = mysqli_query($koneksi, $query_update_tanggal_registrasi);

    if (!$query_update_tanggal_registrasi) {
        echo "Error: " . mysqli_error($koneksi);
        return false;
    }

    // Update data penghuni pada tabel users
    $nama = htmlspecialchars($data['nama_penghuni']);
    $alamat = htmlspecialchars($data['alamat_penghuni']);
    $kontak = htmlspecialchars($data['kontak_penghuni']);
    $email = htmlspecialchars($data['email_penghuni']);

    // Cek apakah email sudah ada dalam tabel users
    $query_cek_email = "SELECT COUNT(*) as email_count FROM users WHERE email = '$email'";
    $result_cek_email = mysqli_query($koneksi, $query_cek_email);
    $row_cek_email = mysqli_fetch_assoc($result_cek_email);
    $email_count = $row_cek_email['email_count'];

    // Cek apakah kontak sudah ada dalam tabel users
    $query_cek_kontak = "SELECT COUNT(*) as kontak_count FROM users WHERE kontak = '$kontak'";
    $result_cek_kontak = mysqli_query($koneksi, $query_cek_kontak);
    $row_cek_kontak = mysqli_fetch_assoc($result_cek_kontak);
    $kontak_count = $row_cek_kontak['kontak_count'];

    if ($email_count > 0 && $email !== $data['email_penghuni']) {
        return 'email';
    } elseif ($kontak_count > 0 && $kontak !== $data['kontak_penghuni']) {
        return 'kontak';
    }

    $query_update_users = "UPDATE users SET nama = '$nama', alamat = '$alamat', kontak = '$kontak', email = '$email' WHERE id_users = (SELECT id_users FROM penghuni WHERE id_penghuni = '$id_penghuni')";

    $result_update_users = mysqli_query($koneksi, $query_update_users);

    if (!$result_update_users) {
        echo "Error: " . mysqli_error($koneksi);
        return false;
    }

    // Update identitas_penghuni jika ada file yang diunggah
    if ($_FILES['identitas_penghuni']['error'] === UPLOAD_ERR_OK) {

        $nama_file = $_FILES['identitas_penghuni']['name'];
        $ext = pathinfo($nama_file, PATHINFO_EXTENSION);
        $random = time();
        $fotouploadrandomname = $random . '.' . $ext;
        $file_tmp = $_FILES['identitas_penghuni']['tmp_name'];
        $folder_upload = "imageupload/" . $fotouploadrandomname;

        if (move_uploaded_file($file_tmp, $folder_upload)) {
            // Delete the existing identitas_penghuni image
            $query_select_identitas = "SELECT identitas_penghuni FROM penghuni WHERE id_penghuni = '$id_penghuni'";
            $result_select_identitas = mysqli_query($koneksi, $query_select_identitas);
            $row = mysqli_fetch_assoc($result_select_identitas);
            unlink($row['identitas_penghuni']);

            $query_update_identitas = "UPDATE penghuni SET identitas_penghuni = '$folder_upload' WHERE id_penghuni = '$id_penghuni'";
            $result_update_identitas = mysqli_query($koneksi, $query_update_identitas);

            if (!$result_update_identitas) {
                echo "Error: " . mysqli_error($koneksi);
                return false;
            }
        } else {
            echo "Error: Gagal mengunggah file identitas penghuni.";
            return false;
        }
    }

    // Update keterangan_penghuni
    $keterangan = htmlspecialchars($data['keterangan_penghuni']);
    $query_update_keterangan = "UPDATE penghuni SET keterangan_penghuni = '$keterangan' WHERE id_penghuni = '$id_penghuni'";

    $result_update_keterangan = mysqli_query($koneksi, $query_update_keterangan);

    if (!$result_update_keterangan) {
        echo "Error: " . mysqli_error($koneksi);
        return false;
    }

    return true;
}

function WMSCheckIn($data)
{
    global $koneksi;

    $id_kamar = $data['id_kamar'];
    $id_penghuni = $data['id_penghuni'];
    $tanggal_masuk = $data['tanggal_masuk'];
    $tanggal_keluar = $data['tanggal_keluar'];
    $durasi_sewa = $data['durasi_sewa'];
    $periode_sewa = $data['periode_sewa'];

    // Jika durasi sewa dalam tahun, ubah menjadi bulan
    if ($periode_sewa === 'tahun' && is_numeric($durasi_sewa)) {
        $durasi_tahun = intval($durasi_sewa);
        $durasi_bulan = $durasi_tahun * 12;
        $durasi_sewa = $durasi_bulan;
    }

    // Masukkan data ke tabel penghuni_in_house
    $query_insert = "INSERT INTO penghuni_in_house (id_kamar, id_penghuni, tanggal_masuk_pih, tanggal_keluar_pih, durasi_sewa_pih) 
    VALUES ('$id_kamar', '$id_penghuni', '$tanggal_masuk', '$tanggal_keluar', '$durasi_sewa')";
    mysqli_query($koneksi, $query_insert);

    // Update status kamar menjadi "Terisi" berdasarkan id_kamar yang sesuai
    $query_update_kamar = "UPDATE kamar SET status_kamar = 'Terisi' WHERE id_kamar = '$id_kamar'";
    mysqli_query($koneksi, $query_update_kamar);

    // Update id_kamar, tanggal_masuk_penghuni, dan tanggal_keluar_penghuni pada tabel penghuni berdasarkan id_penghuni
    $query_update_penghuni = "UPDATE penghuni SET id_kamar = '$id_kamar',
                              id_kamar = '$id_kamar',
                              tanggal_masuk_penghuni = '$tanggal_masuk',
                              tanggal_keluar_penghuni = '$tanggal_keluar'
                            WHERE id_penghuni = '$id_penghuni'";
    mysqli_query($koneksi, $query_update_penghuni);


    // Mengembalikan jumlah baris yang terpengaruh oleh query INSERT dan UPDATE
    return mysqli_affected_rows($koneksi);
}

function WMSCheckOut($data)
{
    global $koneksi;

    $id_kamar = $data['id_kamar'];
    $id_penghuni = $data['id_penghuni'];
    $tanggal_checkout = $data['tanggal_checkout'];
    $keterangan_checkout = $data['keterangan_checkout'];

    $querySatu = "INSERT INTO check_out (id_kamar, id_penghuni, tanggal_checkout, keterangan_checkout) 
    VALUES ('$id_kamar', '$id_penghuni', '$tanggal_checkout', '$keterangan_checkout')";
    mysqli_query($koneksi, $querySatu);

    $queryDua = "DELETE FROM penghuni_in_house WHERE id_penghuni = '$id_penghuni'";
    mysqli_query($koneksi, $queryDua);

    $queryTiga = "UPDATE kamar SET status_kamar = 'Tersedia' WHERE id_kamar = '$id_kamar'";
    mysqli_query($koneksi, $queryTiga);

    $queryEmpat = "UPDATE penghuni SET 
    status_penghuni = 'tidak aktif', tanggal_keluar_penghuni = '$tanggal_checkout' WHERE id_penghuni = '$id_penghuni'";
    mysqli_query($koneksi, $queryEmpat);

    // Mengembalikan jumlah baris yang terpengaruh oleh query INSERT dan UPDATE
    return mysqli_affected_rows($koneksi);
}

function ubahWMSCheckOut($data)
{
    global $koneksi;

    $id_checkout = $data['id_checkout'];
    $id_penghuni = $data['id_penghuni'];
    $tanggal_checkout = $data['tanggal_checkout'];
    $keterangan_checkout = $data['keterangan_checkout'];

    $querySatu = "UPDATE check_out SET 
                 tanggal_checkout = '$tanggal_checkout', keterangan_checkout = '$keterangan_checkout'
                 WHERE id_checkout = '$id_checkout'";
    mysqli_query($koneksi, $querySatu);

    // Pengecekan apakah tanggal_checkout pada form berbeda dengan tanggal_checkout pada database
    $queryCek = "SELECT tanggal_keluar_penghuni FROM penghuni WHERE id_penghuni = '$id_penghuni'";
    $result = mysqli_query($koneksi, $queryCek);
    $row = mysqli_fetch_assoc($result);
    $tanggal_keluar_penghuni_db = $row['tanggal_keluar_penghuni'];

    if ($tanggal_checkout != $tanggal_keluar_penghuni_db) {
        $queryDua = "UPDATE penghuni SET 
                tanggal_keluar_penghuni = '$tanggal_checkout' 
                WHERE id_penghuni = '$id_penghuni'";
        mysqli_query($koneksi, $queryDua);
    }

    // Mengembalikan jumlah baris yang terpengaruh oleh query INSERT dan UPDATE
    return mysqli_affected_rows($koneksi);
}

function tambahKeluhan_Penghuni($data)
{
    $koneksi = connectDB();

    $id_penghuni = $_SESSION['id_penghuni']; // Ambil ID penghuni dari session
    $isi_keluhan = mysqli_real_escape_string($koneksi, $data['isi_keluhan']);
    $tanggal_keluhan = date('Y-m-d H:i:s');

    // Proses upload file foto keluhan
    $fp = $_FILES["gambar_keluhan"]["name"];
    $ext = pathinfo($fp, PATHINFO_EXTENSION);
    $random = crypt($fp, time());
    $sp = $_FILES["gambar_keluhan"]["tmp_name"];

    // Proses upload file foto keluhan
    if (!empty($fp) && !empty($sp)) {
        $folder = "imageupload/" . $random . '.' . $ext;
        $folderdb = "imageupload/" . $random . '.' . $ext;
        move_uploaded_file($sp, $folder);
    } else {
        $folderdb = "img/nodata.jpg";
    }

    $query = "INSERT INTO keluhan (id_penghuni, tanggal_keluhan, gambar_keluhan, isi_keluhan) 
              VALUES ('$id_penghuni', '$tanggal_keluhan', '$folderdb', '$isi_keluhan')";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

function ubahKeluhan_Penghuni($data, $gambar_keluhan, $id_keluhan)
{
    $koneksi = connectDB();

    $isi_keluhan = mysqli_real_escape_string($koneksi, $data['isi_keluhan']);

    // Check if a new image is uploaded
    if ($_FILES["gambar_keluhan"]["error"] === UPLOAD_ERR_NO_FILE) {
        // No new image uploaded, update pengeluaran data without changing the bukti_bayar field
        $query = "UPDATE keluhan SET isi_keluhan = '$isi_keluhan' WHERE id_keluhan = '$id_keluhan'";
    } else {
        // Delete the existing payment proof image
        $queryhapus = "SELECT gambar_keluhan FROM keluhan WHERE id_keluhan='$id_keluhan'";
        $datahapus = mysqli_query($koneksi, $queryhapus);
        $row = mysqli_fetch_assoc($datahapus);
        unlink($row['gambar_keluhan']);

        // Upload the new payment proof image
        $fp = $_FILES["gambar_keluhan"]["name"];
        $ext = pathinfo($fp, PATHINFO_EXTENSION);
        $random = time();
        $folder = "imageupload/" . $random . '.' . $ext;
        $folderdb = "imageupload/" . $random . '.' . $ext;
        move_uploaded_file($_FILES["gambar_keluhan"]["tmp_name"], $folder);

        // Update pengeluaran data with the new image details
        $query = "UPDATE keluhan SET gambar_keluhan = '$folderdb', isi_keluhan = '$isi_keluhan' WHERE id_keluhan = '$id_keluhan'";
    }

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

function tambahRekening($data)
{
    global $koneksi;

    $jenis_pembayaran = $data['jenis_pembayaran'];
    $nomor_rekening = $data['nomor_rekening'];
    $pemilik_rekening = $data['pemilik_rekening'];

    // Periksa apakah data sudah ada dalam database
    $query = "SELECT * FROM rekening WHERE jenis_pembayaran = '$jenis_pembayaran' AND nomor_rekening = '$nomor_rekening'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        // Data sudah ada, tolak penambahan data
        return -1;
    } else {
        // Data belum ada, tambahkan data ke database
        $queryInsert = "INSERT INTO rekening (jenis_pembayaran, nomor_rekening, pemilik_rekening) 
                        VALUES ('$jenis_pembayaran', '$nomor_rekening', '$pemilik_rekening')";
        mysqli_query($koneksi, $queryInsert);
        return mysqli_affected_rows($koneksi);
    }
}

function ubahRekening($data)
{
    global $koneksi;

    $id_rekening = $data['id_rekening'];
    $jenis_pembayaran = $data['jenis_pembayaran'];
    $nomor_rekening = $data['nomor_rekening'];
    $pemilik_rekening = $data['pemilik_rekening'];

    // Periksa apakah data sudah ada dalam database
    $query = "SELECT * FROM rekening WHERE jenis_pembayaran = '$jenis_pembayaran' AND nomor_rekening = '$nomor_rekening' AND id_rekening != '$id_rekening'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        // Data sudah ada, tolak perubahan data
        return -1;
    } else {
        // Data belum ada, lakukan perubahan data
        $queryUpdate = "UPDATE rekening 
                        SET jenis_pembayaran = '$jenis_pembayaran', nomor_rekening = '$nomor_rekening', pemilik_rekening = '$pemilik_rekening' 
                        WHERE id_rekening = '$id_rekening'";
        mysqli_query($koneksi, $queryUpdate);
        return mysqli_affected_rows($koneksi);
    }
}

function tambahPengeluaran($data)
{
    global $koneksi;

    $tanggal_pengeluaran = $data["tanggal_pengeluaran"];
    $keterangan_pengeluaran = $data["keterangan_pengeluaran"];
    $nominal_pengeluaran = $data["nominal_pengeluaran"];

    $fp = $_FILES["uploadgambar"]["name"];
    $ext = pathinfo($fp, PATHINFO_EXTENSION);
    $random = crypt($fp, time());
    $sp = $_FILES["uploadgambar"]["tmp_name"];

    // Proses upload file bukti keluhan
    if (!empty($fp) && !empty($sp)) {
        $folder = "imageupload/" . $random . '.' . $ext;
        $folderdb = "imageupload/" . $random . '.' . $ext;
        move_uploaded_file($sp, $folder);
    } else {
        $folderdb = "img/nodata.jpg";
    }

    $query =
        "INSERT INTO pengeluaran (tanggal_pengeluaran, keterangan_pengeluaran, nominal_pengeluaran, bukti_bayar) 
        VALUES ('$tanggal_pengeluaran', '$keterangan_pengeluaran', '$nominal_pengeluaran', '$folderdb')";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

function ubahPengeluaran($data)
{
    global $koneksi;

    $id = $data['id_pengeluaran'];
    $tanggal_pengeluaran = $data["tanggal_pengeluaran"];
    $keterangan_pengeluaran = $data["keterangan_pengeluaran"];
    $nominal_pengeluaran = $data["nominal_pengeluaran"];

    // Check if a new image is uploaded
    if ($_FILES["uploadgambar"]["error"] === UPLOAD_ERR_NO_FILE) {
        // No new image uploaded, update pengeluaran data without changing the bukti_bayar field
        $query =
            "UPDATE pengeluaran 
            SET tanggal_pengeluaran = '$tanggal_pengeluaran', keterangan_pengeluaran = '$keterangan_pengeluaran', nominal_pengeluaran = '$nominal_pengeluaran' 
            WHERE id_pengeluaran = '$id'";
    } else {
        // Delete the existing payment proof image
        $queryhapus = "SELECT bukti_bayar FROM pengeluaran WHERE id_pengeluaran='$id'";
        $datahapus = mysqli_query($koneksi, $queryhapus);
        $row = mysqli_fetch_assoc($datahapus);
        unlink($row['bukti_bayar']);

        // Upload the new payment proof image
        $fp = $_FILES["uploadgambar"]["name"];
        $ext = pathinfo($fp, PATHINFO_EXTENSION);
        $random = time();
        $folder = "imageupload/" . $random . '.' . $ext;
        $folderdb = "imageupload/" . $random . '.' . $ext;
        move_uploaded_file($_FILES["uploadgambar"]["tmp_name"], $folder);

        // Update pengeluaran data with the new image details
        $query =
            "UPDATE pengeluaran 
            SET tanggal_pengeluaran = '$tanggal_pengeluaran', keterangan_pengeluaran = '$keterangan_pengeluaran', nominal_pengeluaran = '$nominal_pengeluaran', bukti_bayar = '$folderdb' 
            WHERE id_pengeluaran = '$id'";
    }

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

function tambahKendaraan($data)
{
    global $koneksi;

    $nomor_kendaraan = $data['nomor_kendaraan'];
    $jenis_kendaraan = $data['jenis_kendaraan'];
    $model_kendaraan = $data['model_kendaraan'];
    $id_penghuni = $data['id_penghuni'];

    // Periksa apakah data nomor kendaraan sudah ada dalam database
    $query = "SELECT * FROM kendaraan WHERE nomor_kendaraan = '$nomor_kendaraan'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        // Data nomor kendaraan sudah ada, tolak penambahan data
        return -1;
    } else {
        // Data nomor kendaraan belum ada, tambahkan data ke database
        $queryInsert = "INSERT INTO kendaraan (nomor_kendaraan, jenis_kendaraan, model_kendaraan, id_penghuni) 
                        VALUES ('$nomor_kendaraan', '$jenis_kendaraan', '$model_kendaraan', '$id_penghuni')";
        mysqli_query($koneksi, $queryInsert);
        return mysqli_affected_rows($koneksi);
    }
}

function ubahKendaraan($id_kendaraan, $data)
{
    global $koneksi;

    $nomor_kendaraan = $data['nomor_kendaraan'];
    $jenis_kendaraan = $data['jenis_kendaraan'];
    $model_kendaraan = $data['model_kendaraan'];
    $id_penghuni = $data['id_penghuni'];

    // Periksa apakah data sudah ada dalam database
    $query = "SELECT * FROM kendaraan WHERE nomor_kendaraan = '$nomor_kendaraan' AND id_kendaraan != $id_kendaraan";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        // Data sudah ada, tolak perubahan data
        return -1;
    } else {
        // Data belum ada, update data kendaraan di database
        $queryUpdate = "UPDATE kendaraan SET nomor_kendaraan = '$nomor_kendaraan', jenis_kendaraan = '$jenis_kendaraan', model_kendaraan = '$model_kendaraan', id_penghuni = $id_penghuni WHERE id_kendaraan = $id_kendaraan";
        mysqli_query($koneksi, $queryUpdate);
        return mysqli_affected_rows($koneksi);
    }
}

function tambahTagihan($data)
{
    global $koneksi;

    // Ambil nilai-nilai yang diperlukan dari data yang dikirimkan melalui formulir
    $tanggalTagihan = $data['tanggal_tagihan'];
    $idPenghuni = $data['id_penghuni'];
    $kategoriTagihan = $data['kategori_tagihan'];

    // Inisialisasi variabel untuk menyimpan data tagihan
    $deskripsiTagihan = '';
    $jumlahTagihan = 0;
    $keteranganTagihan = '';

    // Tentukan data tagihan berdasarkan kategori tagihan yang dipilih
    if ($kategoriTagihan === 'sewa_kamar') {
        $deskripsiTagihan = $data['deskripsi_sewa_kamar'];
        $jumlahTagihan = $data['jumlah_sewa_kamar'];
        $keteranganTagihan = $data['keterangan_sewa_kamar'];
        $kodetagihan = '01';
    } elseif ($kategoriTagihan === 'sewa_kendaraan') {
        $deskripsiTagihan = $data['deskripsi_sewa_kendaraan'];
        $jumlahTagihan = $data['jumlah_sewa_kendaraan'];
        $keteranganTagihan = $data['keterangan_sewa_kendaraan'];
        $kodetagihan = '03';
    } elseif ($kategoriTagihan === 'sewa_lainnya') {
        $deskripsiTagihan = $data['deskripsi_sewa_lainnya'];
        $jumlahTagihan = $data['jumlah_sewa_lainnya'];
        $keteranganTagihan = $data['keterangan_sewa_lainnya'];
        $kodetagihan = '04';
    } elseif ($kategoriTagihan === 'Tanda Jadi') {
        $deskripsiTagihan = $data['deskripsi_tanda_jadi'];
        $jumlahTagihan = $data['jumlah_tanda_jadi'];
        $keteranganTagihan = $data['keterangan_tanda_jadi'];
        $kodetagihan = '05';
    }

    $query = "SELECT MAX(RIGHT(no_tagihan, 3)) AS max_number FROM tagihan WHERE kategori_tagihan = '$kategoriTagihan'";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    $lastNumber = intval($row['max_number']);
    $nextNumber = $lastNumber + 1;
    $formattedNumber = sprintf("%03d", $nextNumber);
    $nomorTagihan = $kodetagihan . $formattedNumber;

    // Buat query untuk menyimpan data tagihan
    $query = "INSERT INTO tagihan (id_penghuni, no_tagihan, kategori_tagihan, deskripsi_tagihan, jumlah_tagihan, tanggal_tagihan, status_tagihan, keterangan_tagihan, jumlah_sisa_tagihan) 
              VALUES ('$idPenghuni', '$nomorTagihan', '$kategoriTagihan', '$deskripsiTagihan', '$jumlahTagihan', '$tanggalTagihan', 'Belum Bayar', '$keteranganTagihan', '$jumlahTagihan')";

    // Eksekusi query
    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

function ubahTagihan($data)
{
    global $koneksi;

    $id_tagihan = $data['id_tagihan'];
    $tanggal_tagihan = $data['tanggal_tagihan'];
    $id_penghuni = $data['id_penghuni'];
    $deskripsi_tagihan = $data['deskripsi_tagihan'];
    $jumlah_tagihan = $data['jumlah_tagihan'];
    $keterangan_tagihan = $data['keterangan_tagihan'];
    $status_tagihan = $data['status_tagihan'];

    $query = "UPDATE tagihan 
    SET tanggal_tagihan='$tanggal_tagihan', 
    id_penghuni='$id_penghuni', 
    deskripsi_tagihan='$deskripsi_tagihan',
    jumlah_tagihan='$jumlah_tagihan', 
    keterangan_tagihan='$keterangan_tagihan', 
    status_tagihan='$status_tagihan' 
    WHERE id_tagihan='$id_tagihan'";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

function ubahTagihanBelumLunas($data)
{
    global $koneksi;

    $id_tagihan = $data['id_tagihan'];
    $tanggal_tagihan = $data['tanggal_tagihan'];
    $id_penghuni = $data['id_penghuni'];
    $deskripsi_tagihan = $data['deskripsi_tagihan'];
    $jumlah_tagihan = $data['jumlah_tagihan'];
    $jumlah_bayar_tagihan = $data['jumlah_bayar_tagihan'];
    $jumlah_sisa_tagihan = $data['jumlah_sisa_tagihan'];
    $keterangan_tagihan = $data['keterangan_tagihan'];

    $query = "UPDATE tagihan 
    SET tanggal_tagihan='$tanggal_tagihan', 
    id_penghuni='$id_penghuni', 
    deskripsi_tagihan='$deskripsi_tagihan',
    jumlah_tagihan='$jumlah_tagihan',
    jumlah_bayar_tagihan='$jumlah_bayar_tagihan',
    jumlah_sisa_tagihan='$jumlah_sisa_tagihan', 
    keterangan_tagihan='$keterangan_tagihan', 
    status_tagihan='Belum Lunas' 
    WHERE id_tagihan='$id_tagihan'";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

function pindahKamar($data)
{
    global $koneksi;

    $id_penghuni = $data['id_penghuni'];
    $id_kamar = $data['id_kamar'];
    $alasan_pindah = $data['alasan_pindah'];

    // ambil id kamar lama
    $queryKamarLama = "SELECT penghuni.id_kamar 
                       FROM penghuni 
                       INNER JOIN kamar ON penghuni.id_kamar = kamar.id_kamar 
                       WHERE penghuni.id_penghuni = '$id_penghuni'";
    $resultKamarLama = mysqli_query($koneksi, $queryKamarLama);
    $idKamarLama = mysqli_fetch_assoc($resultKamarLama)['id_kamar'];

    // ambil id kamar baru
    $queryKamarBaru = "SELECT id_kamar FROM kamar WHERE id_kamar = '$id_kamar'";
    $resultKamarBaru = mysqli_query($koneksi, $queryKamarBaru);
    $idKamarBaru = mysqli_fetch_assoc($resultKamarBaru)['id_kamar'];

    // update kamar baru menjadi terisi
    $querySatu = "UPDATE kamar SET status_kamar='terisi' WHERE id_kamar='$id_kamar'";
    mysqli_query($koneksi, $querySatu);

    // update kamar lama menjadi tersedia
    $queryDua = "UPDATE kamar k
    INNER JOIN penghuni p ON k.id_kamar = p.id_kamar
    SET k.status_kamar = 'tersedia'
    WHERE p.id_penghuni = '$id_penghuni'";
    mysqli_query($koneksi, $queryDua);

    // update id kamar pada data penghuni
    $queryTiga = "UPDATE penghuni SET id_kamar='$id_kamar' WHERE id_penghuni='$id_penghuni'";
    mysqli_query($koneksi, $queryTiga);

    // update id kamar pada data penghuni in house
    $queryEmpat = "UPDATE penghuni_in_house SET id_kamar='$id_kamar' WHERE id_penghuni='$id_penghuni'";
    mysqli_query($koneksi, $queryEmpat);

    // insert data ke tabel pindah_kamar
    $waktuPindah = date('Y-m-d H:i:s');
    $queryEmpat = "INSERT INTO pindah_kamar (id_penghuni, kamar_sebelum, kamar_sesudah, waktu_pindah, alasan_pindah) 
                   VALUES ('$id_penghuni', '$idKamarLama', '$idKamarBaru', '$waktuPindah', '$alasan_pindah')";
    mysqli_query($koneksi, $queryEmpat);

    return mysqli_affected_rows($koneksi);
}

function tambahTamu($data)
{
    global $koneksi;

    $id_penghuni = $data['id_penghuni'];
    $tanggal_tamu = $data['tanggal_tamu'];
    $nama_tamu = $data['nama_tamu'];
    $waktu_menginap_tamu = $data['waktu_menginap_tamu'];
    $tarif_tamu = $data['tarif_tamu'];
    $kodetagihan = '02';
    $total_tagihan = $waktu_menginap_tamu * $tarif_tamu;

    $query = "SELECT MAX(RIGHT(no_tagihan, 3)) AS max_number FROM tagihan";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    $lastNumber = intval($row['max_number']);
    $nextNumber = $lastNumber + 1;
    $formattedNumber = sprintf("%03d", $nextNumber);
    $nomorTagihan = $kodetagihan . $formattedNumber;

    $fp = $_FILES["identitas_tamu"]["name"];
    $ext = pathinfo($fp, PATHINFO_EXTENSION);
    $random = crypt($fp, time());
    $sp = $_FILES["identitas_tamu"]["tmp_name"];

    // Proses upload file identitas tamu
    if (!empty($fp) && !empty($sp)) {
        $folder = "imageupload/" . $random . '.' . $ext;
        $folderdb = "imageupload/" . $random . '.' . $ext;
        move_uploaded_file($sp, $folder);
    } else {
        $folderdb = "img/nodata.jpg";
    }

    // Insert data ke tabel tagihan
    $query_tagihan = "INSERT INTO tagihan (id_penghuni, no_tagihan, kategori_tagihan, deskripsi_tagihan, jumlah_tagihan, tanggal_tagihan, status_tagihan, keterangan_tagihan) 
                      VALUES ('$id_penghuni', '$nomorTagihan', 'sewa_tamu', 'Tamu', '$total_tagihan', '$tanggal_tamu', 'Belum Bayar', '')";
    mysqli_query($koneksi, $query_tagihan);

    // Ambil id_tagihan yang baru saja di-generate
    $id_tagihan = mysqli_insert_id($koneksi);

    // Insert data ke tabel tamu dengan menyertakan id_tagihan
    $query_tamu = "INSERT INTO tamu (id_penghuni, id_tagihan, tanggal_tamu, nama_tamu, identitas_tamu, waktu_menginap_tamu, tarif_tamu) 
                   VALUES ('$id_penghuni', '$id_tagihan', '$tanggal_tamu', '$nama_tamu', '$folderdb', '$waktu_menginap_tamu', '$tarif_tamu')";
    mysqli_query($koneksi, $query_tamu);

    return mysqli_affected_rows($koneksi);
}

function ubahTamu($data)
{
    global $koneksi;

    $id_tamu = $data['id_tamu'];
    $tanggal_tamu = $data['tanggal_tamu'];
    $nama_tamu = $data['nama_tamu'];
    $waktu_menginap_tamu = $data['waktu_menginap_tamu'];
    $tarif_tamu = $data['tarif_tamu'];

    // Periksa apakah ada gambar baru yang diunggah
    if ($_FILES["identitas_tamu"]["error"] === UPLOAD_ERR_NO_FILE) {
        // Tidak ada gambar baru yang diunggah, perbarui data tamu tanpa mengubah kolom identitas_tamu
        $query_update_tamu = "UPDATE tamu SET tanggal_tamu='$tanggal_tamu', nama_tamu='$nama_tamu', waktu_menginap_tamu='$waktu_menginap_tamu', tarif_tamu='$tarif_tamu' WHERE id_tamu='$id_tamu'";
        mysqli_query($koneksi, $query_update_tamu);

        // Dapatkan data tagihan terkait
        $query_get_tagihan = "SELECT id_tagihan FROM tamu WHERE id_tamu='$id_tamu'";
        $result_get_tagihan = mysqli_query($koneksi, $query_get_tagihan);
        $row_get_tagihan = mysqli_fetch_assoc($result_get_tagihan);
        $id_tagihan = $row_get_tagihan['id_tagihan'];

        // Perbarui data tagihan terkait
        $total_tagihan = $waktu_menginap_tamu * $tarif_tamu;
        $query_update_tagihan = "UPDATE tagihan SET jumlah_tagihan='$total_tagihan' WHERE id_tagihan='$id_tagihan'";
        mysqli_query($koneksi, $query_update_tagihan);

        return mysqli_affected_rows($koneksi);
    } else {
        // Hapus gambar identitas_tamu yang ada
        $query_hapus = "SELECT identitas_tamu FROM tamu WHERE id_tamu='$id_tamu'";
        $data_hapus = mysqli_query($koneksi, $query_hapus);
        $row = mysqli_fetch_assoc($data_hapus);
        unlink($row['identitas_tamu']);

        // Unggah gambar identitas_tamu yang baru
        $fp = $_FILES["identitas_tamu"]["name"];
        $ext = pathinfo($fp, PATHINFO_EXTENSION);
        $random = time();
        $folder = "imageupload/" . $random . '.' . $ext;
        $folderdb = "imageupload/" . $random . '.' . $ext;
        move_uploaded_file($_FILES["identitas_tamu"]["tmp_name"], $folder);

        // Perbarui data tamu dengan detail gambar baru
        $query_update_tamu =
            "UPDATE tamu SET 
        tanggal_tamu='$tanggal_tamu', 
        nama_tamu='$nama_tamu', 
        identitas_tamu='$folderdb', 
        waktu_menginap_tamu='$waktu_menginap_tamu', 
        tarif_tamu='$tarif_tamu'
        WHERE id_tamu='$id_tamu'";
        mysqli_query($koneksi, $query_update_tamu);

        // Dapatkan data tagihan terkait
        $query_get_tagihan = "SELECT id_tagihan FROM tamu WHERE id_tamu='$id_tamu'";
        $result_get_tagihan = mysqli_query($koneksi, $query_get_tagihan);
        $row_get_tagihan = mysqli_fetch_assoc($result_get_tagihan);
        $id_tagihan = $row_get_tagihan['id_tagihan'];

        // Perbarui data tagihan terkait
        $total_tagihan = $waktu_menginap_tamu * $tarif_tamu;
        $query_update_tagihan = "UPDATE tagihan SET jumlah_tagihan='$total_tagihan' WHERE id_tagihan='$id_tagihan'";
        mysqli_query($koneksi, $query_update_tagihan);

        return mysqli_affected_rows($koneksi);
    }
}

function ubahAkun($data)
{
    global $koneksi;

    $id_users = $_SESSION['id_users'];

    $username = mysqli_real_escape_string($koneksi, $data['username']);
    $kontak = mysqli_real_escape_string($koneksi, $data['kontak']);
    $email = mysqli_real_escape_string($koneksi, $data['email']);

    $password_lama = mysqli_real_escape_string($koneksi, $data['password_lama']);
    $password_baru = mysqli_real_escape_string($koneksi, $data['password_baru']);
    $konfirmasi_password = mysqli_real_escape_string($koneksi, $data['konfirmasi_password']);

    // Periksa apakah username atau email sudah ada dalam tabel users
    $query_cek = "SELECT COUNT(*) as username_count, (SELECT COUNT(*) FROM users WHERE email = '$email') as email_count, (SELECT COUNT(*) FROM users WHERE kontak = '$kontak') as kontak_count FROM users WHERE username = '$username'";
    $result_cek = mysqli_query($koneksi, $query_cek);
    $row_cek = mysqli_fetch_array($result_cek);

    if ($row_cek['email_count'] > 0 && $email !== $data['email']) {
        return 'email';
    } elseif ($row_cek['kontak_count'] > 0 && $kontak !== $data['kontak']) {
        return 'kontak';
    } elseif ($row_cek['username_count'] > 0 && $username !== $data['username']) {
        return 'username';
    }

    // Validasi konfirmasi password
    $queryPassword = "SELECT password FROM users WHERE id_users = $id_users";
    $resultPassword = mysqli_query($koneksi, $queryPassword);
    $row = mysqli_fetch_assoc($resultPassword);
    $hashedPassword = $row['password'];

    if (!password_verify($password_lama, $hashedPassword)) {
        return 'passwordlama';
    }

    if ($password_baru !== $konfirmasi_password) {
        return 'konfirmasipass';
    }

    // Mulai transaksi
    mysqli_autocommit($koneksi, false);

    // Jika semua validasi berhasil, lakukan pembaruan informasi akun
    $hashedNewPassword = password_hash($password_baru, PASSWORD_DEFAULT);

    $updateQuery = "UPDATE users SET username = '$username', email = '$email', kontak = '$kontak', password = '$hashedNewPassword' WHERE id_users = $id_users";
    mysqli_query($koneksi, $updateQuery);

    // Commit transaksi jika semua query berhasil dieksekusi
    mysqli_commit($koneksi);

    return true;
}

function tambahBayarTagihan($data)
{
    global $koneksi;

    $id_penghuni = $_SESSION['id_penghuni'];
    $tanggal_pembayaran = $data['tanggal_pembayaran'];
    $id_tagihan = $data['id_tagihan'];
    $jumlah_pembayaran = $data['tagihan_penghuni'];
    $keterangan_pembayaran = $data['keterangan'];

    $fp = $_FILES["bukti_pembayaran"]["name"];
    $ext = pathinfo($fp, PATHINFO_EXTENSION);
    $random = crypt($fp, time());
    $sp = $_FILES["bukti_pembayaran"]["tmp_name"];

    // Cek apakah file identitas_penghuni diupload
    if (!empty($fp) && !empty($sp)) {
        $folder = "imageupload/" . $random . '.' . $ext;
        $folderdb = "imageupload/" . $random . '.' . $ext;
        move_uploaded_file($sp, $folder);
    } else {
        $folderdb = "img/nodata.jpg";
    }

    // Insert data pembayaran ke tabel pembayaran
    $query = "INSERT INTO pembayaran (id_penghuni, tanggal_pembayaran, jumlah_pembayaran, keterangan_pembayaran, bukti_pembayaran) VALUES ('$id_penghuni', '$tanggal_pembayaran', '$jumlah_pembayaran', '$keterangan_pembayaran', '$folderdb')";
    mysqli_query($koneksi, $query);
    $id_pembayaran = mysqli_insert_id($koneksi);

    // Insert data tagihan yang dipilih ke tabel pembayaran_tagihan
    foreach ($id_tagihan as $tagihan_id) {
        $query = "INSERT INTO pembayaran_tagihan (id_pembayaran, id_tagihan) VALUES ('$id_pembayaran', '$tagihan_id')";
        mysqli_query($koneksi, $query);
    }

    return mysqli_affected_rows($koneksi);
}

















// Fungsi untuk memperbarui informasi akun pengguna
function updateAccount($username, $email, $kontak, $password_lama, $password_baru, $konfirmasi_password)
{
    global $koneksi;

    $id_users = $_SESSION['id_users'];

    // Validasi perubahan username
    $queryUsername = "SELECT * FROM users WHERE username = '$username' AND id_users != $id_users";
    $resultUsername = mysqli_query($koneksi, $queryUsername);

    if (mysqli_num_rows($resultUsername) > 0) {
        return "Username sudah digunakan. Harap pilih username lain.";
    }

    // Validasi perubahan email
    $queryEmail = "SELECT * FROM users WHERE email = '$email' AND id_users != $id_users";
    $resultEmail = mysqli_query($koneksi, $queryEmail);

    if (mysqli_num_rows($resultEmail) > 0) {
        return "Email sudah digunakan. Harap masukkan email lain.";
    }

    // Validasi perubahan kontak
    $queryKontak = "SELECT * FROM users WHERE kontak = '$kontak' AND id_users != $id_users";
    $resultKontak = mysqli_query($koneksi, $queryKontak);

    if (mysqli_num_rows($resultKontak) > 0) {
        return "Nomor telepon sudah digunakan. Harap masukkan nomor telepon lain.";
    }

    // Validasi konfirmasi password
    $queryPassword = "SELECT password FROM users WHERE id_users = $id_users";
    $resultPassword = mysqli_query($koneksi, $queryPassword);
    $row = mysqli_fetch_assoc($resultPassword);
    $hashedPassword = $row['password'];

    if (!password_verify($password_lama, $hashedPassword)) {
        return "Password lama yang Anda masukkan salah.";
    }

    if ($password_baru !== $konfirmasi_password) {
        return "Konfirmasi password tidak sesuai dengan password baru.";
    }

    // Jika semua validasi berhasil, lakukan pembaruan informasi akun
    $hashedNewPassword = password_hash($password_baru, PASSWORD_DEFAULT);
    $updateQuery = "UPDATE users SET username = '$username', email = '$email', kontak = '$kontak', password = '$hashedNewPassword' WHERE id_users = $id_users";
    mysqli_query($koneksi, $updateQuery);

    return "Informasi akun berhasil diperbarui!";
}
