<?php
include 'koneksi.php';
date_default_timezone_set("Asia/Jakarta");

function connectDB()
{
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "wms";

    $koneksi = mysqli_connect($host, $username, $password, $database);

    if (mysqli_connect_errno()) {
        die("Failed to connect to database: " . mysqli_connect_error());
    }

    return $koneksi;
}

function namaBulan()
{
    $bulan = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember'
    ];
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

    $query = "INSERT INTO kamar (nomor_kamar, lokasi_kamar_id, harga_kamar, status_kamar) 
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

    $query = "UPDATE kamar SET nomor_kamar = '$nomor_kamar', lokasi_kamar_id = '$id_lokasi', harga_kamar = '$harga_sewa', status_kamar = '$status_kamar' WHERE id_kamar = '$id_kamar'";

    if (mysqli_query($koneksi, $query)) {
        return true; // Jika query berhasil dijalankan
    } else {
        // Jika terjadi kesalahan dalam menjalankan query
        return -1;
    }
}

function tambahPenghuni($data)
{
    global $koneksi;

    $nama = mysqli_real_escape_string($koneksi, $data['nama_penghuni']);
    $alamat = mysqli_real_escape_string($koneksi, $data['alamat_penghuni']);
    $kontak = mysqli_real_escape_string($koneksi, $data['kontak_penghuni']);
    $username = mysqli_real_escape_string($koneksi, $data['username']);
    $password = mysqli_real_escape_string($koneksi, $data['password']);
    $email = mysqli_real_escape_string($koneksi, $data['email_penghuni']);
    $idKamar = mysqli_real_escape_string($koneksi, $data['id_kamar']);
    $tanggalMasuk = mysqli_real_escape_string($koneksi, $data['tanggal_masuk']);
    $tanggalMasuk = mysqli_real_escape_string($koneksi, $data['tanggal_masuk']);
    $durasiSewa = mysqli_real_escape_string($koneksi, $data['durasi_sewa']);
    $periodeSewa = mysqli_real_escape_string($koneksi, $data['periode_sewa']);

    $fp = $_FILES["identitas_penghuni"]["name"];
    $ext = pathinfo($fp, PATHINFO_EXTENSION);
    $random = crypt($fp, time());
    $sp = $_FILES["identitas_penghuni"]["tmp_name"];

    // Cek apakah file identitas_penghuni diupload
    if (!empty($fp) && !empty($sp)) {
        $folder = "foto/" . $random . '.' . $ext;
        $folderdb = "foto/" . $random . '.' . $ext;
        move_uploaded_file($sp, $folder);
    } else {
        $folderdb = "foto/nodata.png";
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
    $query_cek = "SELECT COUNT(*) FROM users WHERE username = '$username' OR email = '$email'";
    $result_cek = mysqli_query($koneksi, $query_cek);
    $row_cek = mysqli_fetch_array($result_cek);

    if ($row_cek[0] > 0) {
        if ($row_cek['username'] == $username) {
            echo "<script>alert('Data dengan Username Tersebut Sudah Ada.');</script>";
        } elseif ($row_cek['email'] == $email) {
            echo "<script>alert('Data dengan Email Tersebut Sudah Ada.');</script>";
        } elseif ($row_cek['kontak'] == $kontak) {
            echo "<script>alert('Data dengan Kontak Tersebut Sudah Ada.');</script>";
        }
        return false;
    }

    // Enkripsi password menggunakan password_hash()
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert data penghuni ke tabel users
    $query_users = "INSERT INTO users (nama, kontak, email, alamat, tipe, username, password) VALUES ('$nama', '$kontak', '$email', '$alamat', 'penghuni', '$username', '$hashedPassword')";
    $result_users = mysqli_query($koneksi, $query_users);

    if (!$result_users) {
        echo "Error: " . mysqli_error($koneksi);
        return false;
    }

    $idUsers = mysqli_insert_id($koneksi);

    // Update status kamar menjadi "terisi"
    $query_update_kamar = "UPDATE kamar SET status_kamar = 'terisi' WHERE id_kamar = '$idKamar'";
    $result_update_kamar = mysqli_query($koneksi, $query_update_kamar);

    if (!$result_update_kamar) {
        echo "Error: " . mysqli_error($koneksi);
        return false;
    }

    // Insert data penghuni ke tabel penghuni
    $query_penghuni = "INSERT INTO penghuni (id_users, id_kamar, tanggal_masuk_penghuni, tanggal_keluar_penghuni, identitas_penghuni) VALUES ('$idUsers', '$idKamar', '$tanggalMasuk', '$tanggalKeluar', '$folderdb')";
    $result_penghuni = mysqli_query($koneksi, $query_penghuni);

    if (!$result_penghuni) {
        echo "Error: " . mysqli_error($koneksi);
        return false;
    }

    return true; // Sukses menambahkan data penghuni
}

function ubahPenghuni($data, $id_penghuni)
{
    global $koneksi;

    $nama = mysqli_real_escape_string($koneksi, $data['nama_penghuni']);
    $alamat = mysqli_real_escape_string($koneksi, $data['alamat_penghuni']);
    $kontak = mysqli_real_escape_string($koneksi, $data['kontak_penghuni']);
    $email = mysqli_real_escape_string($koneksi, $data['email_penghuni']);
    $tanggalMasuk = mysqli_real_escape_string($koneksi, $data['tanggal_masuk']);
    $tanggalKeluar = mysqli_real_escape_string($koneksi, $data['tanggal_keluar']);

    // Periksa apakah email atau kontak sudah ada dalam tabel users
    $query_cek = "SELECT COUNT(*) FROM users WHERE (email = '$email' OR kontak = '$kontak') AND id_users 
    <> (SELECT id_users FROM penghuni WHERE id_penghuni = '$id_penghuni')";
    $result_cek = mysqli_query($koneksi, $query_cek);
    $row_cek = mysqli_fetch_array($result_cek);

    if ($row_cek[0] > 0) {
        if ($row_cek['email'] == $email) {
            echo "<script>alert('Data dengan Email Tersebut Sudah Ada.');</script>";
        } elseif ($row_cek['kontak'] == $kontak) {
            echo "<script>alert('Data dengan Kontak Tersebut Sudah Ada.');</script>";
        }
        return false;
    }

    // Update data penghuni pada tabel users
    $query_update_users = "UPDATE users SET nama = '$nama', email = '$email', kontak = '$kontak', alamat = '$alamat' WHERE id_users = (SELECT id_users FROM penghuni WHERE id_penghuni = '$id_penghuni')";
    $result_update_users = mysqli_query($koneksi, $query_update_users);

    if (!$result_update_users) {
        echo "Error: " . mysqli_error($koneksi);
        return false;
    }

    // Update data penghuni pada tabel penghuni
    // Check if a new image is uploaded
    if ($_FILES["identitas_penghuni"]["error"] === UPLOAD_ERR_NO_FILE) {
        // No new image uploaded, update pengeluaran data without changing the bukti_bayar field
        $query_update_penghuni = "UPDATE penghuni SET tanggal_masuk_penghuni = '$tanggalMasuk' WHERE id_penghuni = '$id_penghuni'";
        $result_update_penghuni = mysqli_query($koneksi, $query_update_penghuni);

        if (!$result_update_penghuni) {
            echo "Error: " . mysqli_error($koneksi);
            return false;
        }

        return true;
    } else {
        // Delete the existing payment proof image
        $queryhapus = "SELECT identitas_penghuni FROM penghuni WHERE id_penghuni='$id_penghuni'";
        $datahapus = mysqli_query($koneksi, $queryhapus);
        $row = mysqli_fetch_assoc($datahapus);
        unlink($row['identitas_penghuni']);

        // Upload the new payment proof image
        $fp = $_FILES["identitas_penghuni"]["name"];
        $ext = pathinfo($fp, PATHINFO_EXTENSION);
        $random = time();
        $folder = "foto/" . $random . '.' . $ext;
        $folderdb = "foto/" . $random . '.' . $ext;
        move_uploaded_file($_FILES["identitas_penghuni"]["tmp_name"], $folder);

        // Update pengeluaran data with the new image details
        $query_update_penghuni =
            "UPDATE penghuni SET 
        tanggal_masuk_penghuni = '$tanggalMasuk', 
        tanggal_keluar_penghuni = '$tanggalKeluar', 
        identitas_penghuni = '$folderdb'
        WHERE id_penghuni = '$id_penghuni'";
        $result_update_penghuni = mysqli_query($koneksi, $query_update_penghuni);

        if (!$result_update_penghuni) {
            echo "Error: " . mysqli_error($koneksi);
            return false;
        }
        return true;
    }
}


function pindahKamar($data)
{
    global $koneksi;

    $id_penghuni = $data['id_penghuni'];
    $id_kamar = $data['id_kamar'];

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

    // insert data ke tabel pindah_kamar
    $waktuPindah = date('Y-m-d H:i:s');
    $queryEmpat = "INSERT INTO pindah_kamar (id_penghuni, kamar_sebelum, kamar_sesudah, waktu_pindah) 
                   VALUES ('$id_penghuni', '$idKamarLama', '$idKamarBaru', '$waktuPindah')";
    mysqli_query($koneksi, $queryEmpat);

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
        $folder = "foto/" . $random . '.' . $ext;
        $folderdb = "foto/" . $random . '.' . $ext;
        move_uploaded_file($sp, $folder);
    } else {
        $folderdb = "foto/nodata.png";
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
        $folder = "foto/" . $random . '.' . $ext;
        $folderdb = "foto/" . $random . '.' . $ext;
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

function tambahKeluhan($data)
{
    global $koneksi;

    $id_penghuni = $data["id_penghuni"];
    $isi_keluhan = $data["isi_keluhan"];
    $tanggal_keluhan = date("Y-m-d H:i:s");

    // Proses upload file foto keluhan
    $fp = $_FILES["gambar_keluhan"]["name"];
    $ext = pathinfo($fp, PATHINFO_EXTENSION);
    $random = crypt($fp, time());
    $sp = $_FILES["gambar_keluhan"]["tmp_name"];

    // Proses upload file foto keluhan
    if (!empty($fp) && !empty($sp)) {
        $folder = "foto/" . $random . '.' . $ext;
        $folderdb = "foto/" . $random . '.' . $ext;
        move_uploaded_file($sp, $folder);
    } else {
        $folderdb = "foto/nodata.png";
    }


    $query =
        "INSERT INTO keluhan (id_penghuni, tanggal_keluhan, gambar_keluhan, isi_keluhan) 
        VALUES ('$id_penghuni', '$tanggal_keluhan', '$folderdb', '$isi_keluhan')";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

function ubahKeluhan($data)
{
    global $koneksi;

    $id_keluhan = $data["id_keluhan"];
    $id_penghuni = $data["id_penghuni"];
    $isi_keluhan = $data["isi_keluhan"];
    $tanggal_keluhan = date("Y-m-d H:i:s");

    // Check if a new image is uploaded
    if ($_FILES["gambar_keluhan"]["error"] === UPLOAD_ERR_NO_FILE) {
        // No new image uploaded, update pengeluaran data without changing the bukti_bayar field
        $query =
            "UPDATE keluhan 
            SET id_penghuni = '$id_penghuni', isi_keluhan = '$isi_keluhan', tanggal_keluhan = '$tanggal_keluhan' 
            WHERE id_keluhan = '$id_keluhan'";
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
        $folder = "foto/" . $random . '.' . $ext;
        $folderdb = "foto/" . $random . '.' . $ext;
        move_uploaded_file($_FILES["gambar_keluhan"]["tmp_name"], $folder);

        // Update pengeluaran data with the new image details
        $query =
            "UPDATE keluhan 
            SET id_penghuni = '$id_penghuni', isi_keluhan = '$isi_keluhan', tanggal_keluhan = '$tanggal_keluhan', gambar_keluhan = '$folderdb' 
            WHERE id_keluhan = '$id_keluhan'";
    }

    mysqli_query($koneksi, $query);

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
        $folder = "foto/" . $random . '.' . $ext;
        $folderdb = "foto/" . $random . '.' . $ext;
        move_uploaded_file($sp, $folder);
    } else {
        $folderdb = "foto/nodata.png";
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
        $folder = "foto/" . $random . '.' . $ext;
        $folderdb = "foto/" . $random . '.' . $ext;
        move_uploaded_file($_FILES["gambar_keluhan"]["tmp_name"], $folder);

        // Update pengeluaran data with the new image details
        $query = "UPDATE keluhan SET gambar_keluhan = '$folderdb', isi_keluhan = '$isi_keluhan' WHERE id_keluhan = '$id_keluhan'";
    }

    mysqli_query($koneksi, $query);

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
        $folder = "foto/" . $random . '.' . $ext;
        $folderdb = "foto/" . $random . '.' . $ext;
        move_uploaded_file($sp, $folder);
    } else {
        $folderdb = "foto/nodata.png";
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
        $folder = "foto/" . $random . '.' . $ext;
        $folderdb = "foto/" . $random . '.' . $ext;
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
    }

    $query = "SELECT MAX(RIGHT(no_tagihan, 3)) AS max_number FROM tagihan WHERE kategori_tagihan = '$kategoriTagihan'";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    $lastNumber = intval($row['max_number']);
    $nextNumber = $lastNumber + 1;
    $formattedNumber = sprintf("%03d", $nextNumber);
    $nomorTagihan = $kodetagihan . $formattedNumber;

    // Buat query untuk menyimpan data tagihan
    $query = "INSERT INTO tagihan (id_penghuni, no_tagihan, kategori_tagihan, deskripsi_tagihan, jumlah_tagihan, tanggal_tagihan, status_tagihan, keterangan_tagihan) 
              VALUES ('$idPenghuni', '$nomorTagihan', '$kategoriTagihan', '$deskripsiTagihan', '$jumlahTagihan', '$tanggalTagihan', 'Belum Bayar', '$keteranganTagihan')";

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
        $folder = "foto/" . $random . '.' . $ext;
        $folderdb = "foto/" . $random . '.' . $ext;
        move_uploaded_file($sp, $folder);
    } else {
        $folderdb = "foto/nodata.png";
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


// function tambahTagihanKamar($data)
// {
//     global $koneksi;

//     // Ambil nilai-nilai yang diperlukan dari data yang dikirimkan melalui formulir
//     $tanggalTagihan = $data['tanggal_tagihan'];
//     $idPenghuni = $data['id_penghuni'];
//     $deskripsiTagihan = $data['deskripsi_tagihan'];
//     $jumlahTagihan = $data['jumlah_tagihan'];
//     $keteranganTagihan = $data['keterangan_tagihan'];
//     $kodetagihan = '01';

//     $query = "SELECT MAX(RIGHT(no_tagihan, 3)) AS max_number FROM tagihan";
//     $result = mysqli_query($koneksi, $query);
//     $row = mysqli_fetch_assoc($result);
//     $lastNumber = intval($row['max_number']);
//     $nextNumber = $lastNumber + 1;
//     $formattedNumber = sprintf("%03d", $nextNumber);
//     $nomorTagihan = $kodetagihan . $formattedNumber;

//     // Buat query untuk menyimpan data tagihan
//     $query = "INSERT INTO tagihan (id_penghuni, no_tagihan, kategori_tagihan, deskripsi_tagihan, jumlah_tagihan, tanggal_tagihan, status_tagihan, keterangan_tagihan) 
//               VALUES ('$idPenghuni', '$nomorTagihan', 'sewa_kamar', '$deskripsiTagihan', '$jumlahTagihan', '$tanggalTagihan', 'Belum Bayar', '$keteranganTagihan')";

//     // Eksekusi query
//     mysqli_query($koneksi, $query);

//     return mysqli_affected_rows($koneksi);
// }
