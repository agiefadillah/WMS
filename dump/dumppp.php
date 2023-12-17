<?php
session_start();

// Fungsi untuk melakukan koneksi ke database
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

// Fungsi untuk mereset password
function resetPassword()
{
    $koneksi = connectDB();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_password'])) {
        // Memverifikasi CSRF Token
        if (!verifyCSRFToken($_POST['csrf_token'])) {
            $_SESSION['reset_error'] = 'CSRF Token tidak valid.';
            header("Location: forgot_password.php");
            exit();
        }

        $email = $_POST['email'];

        // Menghindari serangan SQL injection
        $email = mysqli_real_escape_string($koneksi, $email);

        // Cek apakah email ada di database
        $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
        $result = mysqli_query($koneksi, $query);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            // Kirim email dengan link reset password
            $resetToken = bin2hex(random_bytes(32)); // Token untuk link reset password
            $resetLink = "http://localhost/bismillah/reset_password.php?token=" . $resetToken;

            // Simpan token reset password dan waktu kadaluarsa token di database (misalnya, dalam tabel reset_password)
            // Di sini saya asumsikan ada kolom reset_token dan reset_token_expiry di tabel users
            $expiryTime = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token akan kadaluarsa dalam 1 jam
            $updateQuery = "UPDATE users SET reset_token = '$resetToken', reset_token_expiry = '$expiryTime' WHERE id_users = " . $user['id_users'];
            mysqli_query($koneksi, $updateQuery);

            // Kirim email ke alamat yang dimasukkan dengan link reset password
            $to = $email;
            $subject = 'Reset Password - Wisma Mutiara Selaras';
            $message = 'Klik tautan berikut untuk mereset password Anda: ' . $resetLink;
            $headers = 'From: wms.jog@gmail.com';

            if (mail($to, $subject, $message, $headers)) {
                $_SESSION['reset_success'] = 'Email untuk reset password telah dikirim. Silakan cek email Anda.';
            } else {
                $_SESSION['reset_error'] = 'Gagal mengirim email. Silakan coba lagi.';
            }
        } else {
            $_SESSION['reset_error'] = 'Email tidak terdaftar.';
        }

        mysqli_free_result($result);
    }

    mysqli_close($koneksi);
}

// Fungsi untuk membuat dan memeriksa token CSRF
function generateCSRFToken()
{
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;
    return $token;
}

function verifyCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token;
}

// Memanggil fungsi resetPassword
resetPassword();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Wisma Mutiara Selaras - Forgot Password</title>
    <link rel="icon" type="image/png" sizes="16x16" href="img/icon1.png">

    <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="login/assets/css/login.css">
</head>

<body>
    <main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
        <div class="container">
            <div class="container" style="padding-top: 50px; padding-bottom: 50px;">
                <div class="card login-card">
                    <div class="row no-gutters">
                        <div class="col-md-12">
                            <div class="card-body">
                                <div class="brand-wrapper text-center">
                                    <img src="img/BlackWMS.png" alt="logo" width="25%">
                                </div>
                                <form action="" method="POST" style="max-width: 400px; margin: 0 auto;">
                                    <!-- Input untuk CSRF Token -->
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                    <div class="form-group">
                                        <label for="email" class="sr-only">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan alamat email Anda" required>
                                    </div>
                                    <input name="reset_password" id="reset_password" class="btn btn-block login-btn mb-4" type="submit" value="Reset Password">
                                </form>
                                <!-- Pesan kesalahan dan sukses -->
                                <?php
                                if (isset($_SESSION['reset_error'])) {
                                    echo '<div class="alert alert-danger" role="alert">' . $_SESSION['reset_error'] . '</div>';
                                    unset($_SESSION['reset_error']);
                                }
                                if (isset($_SESSION['reset_success'])) {
                                    echo '<div class="alert alert-success text-center" role="alert">' . $_SESSION['reset_success'] . '</div>';
                                    unset($_SESSION['reset_success']);
                                }
                                ?>
                                <a href="login.php" class="forgot-password-link" style="display: block; text-align: center;">Kembali ke Halaman Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>

</html>


<h5 class="card-title mt-4">Status Tagihan Kamar</h5>
<div class="col-sm">
    <select class="select2 form-select shadow-none" style="width: 100%; height:36px;" id="status_tagihan" name="status_tagihan" required>
        <option value="">Pilih Status Tagihan Kamar</option>
        <option value="Belum Bayar" <?= $ubah_tagihan['status_tagihan'] == 'Belum Bayar' ? 'selected' : ''; ?>>Belum Bayar</option>
        <option value="Belum Lunas" <?= $ubah_tagihan['status_tagihan'] == 'Belum Lunas' ? 'selected' : ''; ?>>Belum Lunas</option>
        <option value="Lunas" <?= $ubah_tagihan['status_tagihan'] == 'Lunas' ? 'selected' : ''; ?>>Lunas</option>
    </select>
</div>

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
$tanda_jadi = $data['tanda_jadi'];
$kodetagihan = '05';

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

$query = "SELECT MAX(RIGHT(no_tagihan, 3)) AS max_number FROM tagihan";
$result = mysqli_query($koneksi, $query);
$row = mysqli_fetch_assoc($result);
$lastNumber = intval($row['max_number']);
$nextNumber = $lastNumber + 1;
$formattedNumber = sprintf("%03d", $nextNumber);
$nomorTagihan = $kodetagihan . $formattedNumber;

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

$id_penghuni = mysqli_insert_id($koneksi);

// Check if "Tanda Jadi" is empty or 0
if (empty($tanda_jadi) || intval($tanda_jadi) === 0) {
// "Tanda Jadi" is empty or 0, do not create a tagihan
} else {
// Insert data ke tabel tagihan
$query_tagihan = "INSERT INTO tagihan (id_penghuni, no_tagihan, kategori_tagihan, deskripsi_tagihan, jumlah_tagihan, tanggal_tagihan, status_tagihan, keterangan_tagihan)
VALUES ('$id_penghuni', '$nomorTagihan', 'Tanda Jadi', 'Tanda Jadi', '$tanda_jadi', '$tanggalRegistrasi', 'Belum Bayar', '')";
$result_tagihan = mysqli_query($koneksi, $query_tagihan);

if (!$result_tagihan) {
mysqli_rollback($koneksi);
echo "Error: " . mysqli_error($koneksi);
return false;
}
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

// Update tanda jadi jika berubah
$tandaJadi = (int)$data['tanda_jadi'];
$query_cek_tagihan = "SELECT jumlah_tagihan FROM tagihan WHERE id_penghuni = '$id_penghuni' AND kategori_tagihan = 'Tanda Jadi'";
$result_cek_tagihan = mysqli_query($koneksi, $query_cek_tagihan);

if (!$result_cek_tagihan) {
echo "Error: " . mysqli_error($koneksi);
return false;
}

$row_cek_tagihan = mysqli_fetch_assoc($result_cek_tagihan);
$jumlahTagihanLama = (int)$row_cek_tagihan['jumlah_tagihan'];

if ($tandaJadi !== $jumlahTagihanLama) {
// Mulai transaksi
mysqli_autocommit($koneksi, false);

if ($tandaJadi === 0) {
// Jika tanda jadi diubah menjadi 0, hapus data dari tabel tagihan
$query_hapus_tagihan = "DELETE FROM tagihan WHERE id_penghuni = '$id_penghuni' AND kategori_tagihan = 'Tanda Jadi'";
$result_hapus_tagihan = mysqli_query($koneksi, $query_hapus_tagihan);

if (!$result_hapus_tagihan) {
mysqli_rollback($koneksi); // Batalkan transaksi jika terjadi kesalahan
echo "Error: " . mysqli_error($koneksi);
return false;
}
} else {
// Jika tanda jadi berubah menjadi nilai selain 0, perbarui data di tabel tagihan
if ($jumlahTagihanLama === 0) {
// Jika sebelumnya tidak ada data tagihan untuk "Tanda Jadi", masukkan data baru
$query_insert_tagihan = "INSERT INTO tagihan (id_penghuni, kategori_tagihan, jumlah_tagihan) VALUES ('$id_penghuni', 'Tanda Jadi', '$tandaJadi')";
$result_insert_tagihan = mysqli_query($koneksi, $query_insert_tagihan);

if (!$result_insert_tagihan) {
mysqli_rollback($koneksi); // Batalkan transaksi jika terjadi kesalahan
echo "Error: " . mysqli_error($koneksi);
return false;
}
} else {
// Jika sebelumnya sudah ada data tagihan untuk "Tanda Jadi", perbarui data tersebut
$query_update_tagihan = "UPDATE tagihan SET jumlah_tagihan = '$tandaJadi' WHERE id_penghuni = '$id_penghuni' AND kategori_tagihan = 'Tanda Jadi'";
$result_update_tagihan = mysqli_query($koneksi, $query_update_tagihan);

if (!$result_update_tagihan) {
mysqli_rollback($koneksi); // Batalkan transaksi jika terjadi kesalahan
echo "Error: " . mysqli_error($koneksi);
return false;
}
}
}

// Commit transaksi
mysqli_commit($koneksi);
mysqli_autocommit($koneksi, true);
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
$tanggalMasuk = $data['tanggal_masuk'];
$durasiSewa = $data['durasi_sewa'];
$periodeSewa = $data['periode_sewa'];
$tanda_jadi = $data['tanda_jadi'];
$kodetagihan = '05';

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

$query = "SELECT MAX(RIGHT(no_tagihan, 3)) AS max_number FROM tagihan";
$result = mysqli_query($koneksi, $query);
$row = mysqli_fetch_assoc($result);
$lastNumber = intval($row['max_number']);
$nextNumber = $lastNumber + 1;
$formattedNumber = sprintf("%03d", $nextNumber);
$nomorTagihan = $kodetagihan . $formattedNumber;

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
VALUES ('$idUsers', '$idKamar', '$tanggalRegistrasi', '$tanggalMasuk', '$tanggalKeluar', '$folderdb', '$keterangan', 'aktif')";
$result_penghuni = mysqli_query($koneksi, $query_penghuni);

if (!$result_penghuni) {
mysqli_rollback($koneksi);
echo "Error: " . mysqli_error($koneksi);
return false;
}

$id_penghuni = mysqli_insert_id($koneksi);

// Check if "Tanda Jadi" is empty or 0
if (empty($tanda_jadi) || intval($tanda_jadi) === 0) {
// "Tanda Jadi" is empty or 0, do not create a tagihan
} else {
// Insert data ke tabel tagihan
$query_tagihan = "INSERT INTO tagihan (id_penghuni, no_tagihan, kategori_tagihan, deskripsi_tagihan, jumlah_tagihan, tanggal_tagihan, status_tagihan, keterangan_tagihan)
VALUES ('$id_penghuni', '$nomorTagihan', 'Tanda Jadi', 'Tanda Jadi', '$tanda_jadi', '$tanggalRegistrasi', 'Belum Bayar', '')";
$result_tagihan = mysqli_query($koneksi, $query_tagihan);

if (!$result_tagihan) {
mysqli_rollback($koneksi);
echo "Error: " . mysqli_error($koneksi);
return false;
}
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

<h5 class="card-title mt-1">Tanda Jadi / Uang Muka<span id="wajibdiisi">*</span></h5>
<div class="row">
    <div class="col-sm">
        <div class="form-group">
            <input type="text" class="form-control" name="tanda_jadi" id="tanda_jadi">
        </div>
    </div>
</div>


// Pengecekan apakah tanggal keluar pada form berbeda dengan tanggal keluar pada database
$tanggal_keluar = date("Y-m-d", strtotime($data['tanggal_keluar']));
$queryCekDua = "SELECT tanggal_keluar_penghuni FROM penghuni WHERE id_penghuni = '$id_penghuni'";
$result = mysqli_query($koneksi, $queryCekDua);
$row = mysqli_fetch_assoc($result);
$tanggal_keluar_penghuni_db = $row['tanggal_keluar_penghuni'];

if ($tanggal_keluar != $tanggal_keluar_penghuni_db) {
$queryDua = "UPDATE penghuni SET
tanggal_keluar_penghuni = '$tanggal_keluar'
WHERE id_penghuni = '$id_penghuni'";
mysqli_query($koneksi, $queryDua);
}


<?php

if ($_SESSION['tipe'] != 'pemilik' && $_SESSION['tipe'] != 'penjaga') {
    echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
    <script>
        Swal.fire({
            icon: "error",
            title: "Anda Tidak Memiliki Akses ke Halaman Ini!",
            showConfirmButton: false,
            timer: 2000
        }).then(function () {
            window.location.href = "index.php";
        });
    </script>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['simpan'])) {
        $result = tambahPenghuniTidakAktif($_POST);
        if ($result === true) {
            echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
            <script>
                Swal.fire({
                    icon: "success",
                    title: "Data Berhasil Ditambahkan",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    // Show the second SweetAlert
                    Swal.fire({
                        icon: "question",
                        title: "Kirim Data ke WhatsApp ' . $_POST['nama_penghuni'] . ' ?",
                        showCancelButton: true,
                        confirmButtonText: "Ya",
                        cancelButtonText: "Tidak"
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            kirimDataPenghuniTidakAktifWA("' . $_POST['nama_penghuni'] . '", "' . $_POST['alamat_penghuni'] . '", "' . $_POST['kontak_penghuni'] . '", "' . $_POST['email_penghuni'] . '", "' . $_POST['id_kamar'] . '", "' . $_POST['tanggal_masuk'] . '", "' . $_POST['tanggal_keluar'] . '", "' . $_POST['tanda_jadi'] . '", "' . $_POST['username'] . '");
                        }
                        // Return to the daftar_penghuni page
                        window.location.href = "index.php?page=daftar_penghuni";
                    });
                });
            </script>';
        } elseif ($result === 'username') {
            echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
            <script>
                Swal.fire({
                    icon: "warning",
                    title: "Data dengan Username Tersebut Sudah Ada.",
                    showConfirmButton: false,
                    timer: 1500
                });
            </script>';
        } elseif ($result === 'email') {
            echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
            <script>
                Swal.fire({
                    icon: "warning",
                    title: "Data dengan Email Tersebut Sudah Ada.",
                    showConfirmButton: false,
                    timer: 1500
                });
            </script>';
        } elseif ($result === 'kontak') {
            echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
            <script>
                Swal.fire({
                    icon: "warning",
                    title: "Data dengan Kontak Tersebut Sudah Ada.",
                    showConfirmButton: false,
                    timer: 1500
                });
            </script>';
        } else {
            echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
            <script>
                Swal.fire({
                    icon: "error",
                    title: "Gagal Menambahkan Data",
                    showConfirmButton: false,
                    timer: 2500
                }).then(function () {
                    window.location.href = "index.php?page=tambah_penghuni";
                });
            </script>';
        }
    }
}


?>

<div class="container-fluid">
    <!-- Tanggal Registrasi -->
    <div class="row">

        <div class="col-12">
            <div class="card">

                <div class="card-body">
                    <h3 class="card-title">Tambah Data Penghuni</h3>
                    <h6>
                        <span id="wajibdiisi">Form Dengan Tanda * Wajib Diisi</span>
                    </h6>


                    <form action="" method="POST" enctype="multipart/form-data">

                        <h5 class="card-title mt-4">Tanggal Registrasi<span id="wajibdiisi">*</span></h5>
                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <input type="date" class="form-control" id="tanggal_registrasi" name="tanggal_registrasi" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Identitas Penghuni -->
    <div class="row">

        <div class="col-12">
            <div class="card">

                <div class="card-body">
                    <h4 class="card-title mt-1">Identitas Penghuni</h4>

                    <h5 class="card-title mt-4">Nama<span id="wajibdiisi">*</span></h5>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <input type="text" class="form-control " placeholder="Masukkan Nama Penghuni" name="nama_penghuni" required>
                            </div>
                        </div>
                    </div>

                    <h5 class="card-title mt-1">Alamat</h5>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <input type="text" class="form-control " placeholder="Masukkan Alamat Asal Penghuni" name="alamat_penghuni">
                            </div>
                        </div>
                    </div>

                    <h5 class="card-title mt-1">Kontak<span id="wajibdiisi">*</span></h5>
                    <h6>Harap Gunakan Format: 08 | Contoh Data: 081234567890</h6>
                    <span class="badge badge-danger" id="kontak_message"></span>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <input type="number" class="form-control" placeholder="Masukkan Kontak Penghuni" name="kontak_penghuni" id="kontak_penghuni" onkeyup="checkKontak()" required>
                            </div>
                        </div>
                    </div>

                    <h5 class="card-title mt-1">Email</h5>
                    <span class="badge badge-danger" id="email_message"></span>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <input type="email" class="form-control" placeholder="Masukkan Email Penghuni" name="email_penghuni" id="email_penghuni" onkeyup="checkEmail()">
                            </div>
                        </div>
                    </div>

                    <h5 class="card-title mt-1">Foto Identitas Penghuni</h5>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <input name="identitas_penghuni" id="identitas_penghuni" type="file" class="form-control" onchange="previewImage(event)">
                                <img id="preview" src="img/nodata.jpg" alt="Preview" style="max-width: 100%; max-height: 200px; margin-top: 10px;">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Rencana Check-In dan Durasi Sewa -->
    <div class="row">

        <div class="col-12">
            <div class="card">

                <div class="card-body">
                    <h4 class="card-title mt-1">Rencana Check-In dan Durasi Sewa Penghuni</h4>

                    <h5 class="card-title mt-4">Nomor Kamar<span id="wajibdiisi">*</span></h5>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <select class="form-control" style="width: 100%; height:36px;" id="id_kamar" name="id_kamar" required>

                                    <?php
                                    $kamar = query("SELECT * FROM kamar ORDER BY nomor_kamar ASC");
                                    foreach ($kamar as $kmr) :
                                        echo '<option value="' . $kmr['id_kamar'] . '">' . $kmr['nomor_kamar'] . ' - Rp. ' . number_format($kmr['harga_kamar']) . '</option>';
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <h5 class="card-title mt-1">Tanggal Masuk | Check-In<span id="wajibdiisi">*</span></h5>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                    </div>

                    <h5 class="card-title mt-1">Tanggal Keluar | Check-Out<span id="wajibdiisi">*</span></h5>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <input type="date" class="form-control" id="tanggal_keluar" name="tanggal_keluar" value="<?= $penghuni['tanggal_keluar_penghuni']; ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <h5 class="card-title mt-1">Durasi Sewa<span id="wajibdiisi">*</span></h5>
                                <input type="number" class="form-control" id="durasi_sewa" name="durasi_sewa" placeholder="Masukkan Durasi Sewa" onchange="hitungTanggalKeluar()">
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <h5 class="card-title mt-1">Periode Sewa</h5>
                                <select class="select2 form-select shadow-none" style="width: 100%; height:36px;" id="periode_sewa" name="periode_sewa" onchange="hitungTanggalKeluar()">
                                    <option value="bulan">Bulan</option>
                                    <option value="tahun">Tahun</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <h5 class="card-title mt-1">Tanda Jadi / Uang Muka<span id="wajibdiisi">*</span></h5>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <input type="text" class="form-control" name="tanda_jadi" id="tanda_jadi">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Buat Akun -->
    <div class="row">

        <div class="col-12">
            <div class="card">

                <div class="card-body">
                    <h4 class="card-title mt-1">Buat Akun Penghuni</h4>

                    <h5 class="card-title mt-4">Username<span id="wajibdiisi">*</span></h5>
                    <span class="badge badge-danger" id="username_message"></span>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Masukkan Username Untuk Penghuni" name="username" id="username" onkeyup="checkUsername()" required>
                            </div>
                        </div>
                    </div>
                    <h5 class="card-title mt-1">Password<span id="wajibdiisi">*</span></h5>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <select class="select2 form-select shadow-none" style="width: 100%; height:36px;" name="password" required>
                                    <option value="wms">Password Penghuni: WMS</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Keterangan -->
    <div class="row">

        <div class="col-12">
            <div class="card">

                <div class="card-body">
                    <h4 class="card-title mt-1">Catatan Keterangan Penghuni</h4>

                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <textarea class="form-control" rows="3" name="keterangan_penghuni" id="keterangan_penghuni"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm mt-1">
                            <input type="submit" name="simpan" class="btn btn-success" value="Simpan">
                            <a href="index.php?page=daftar_penghuni" class="btn btn-primary">Kembali</a>
                        </div>
                    </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Image -->
<script>
    // Fungsi untuk menampilkan gambar pratinjau saat memilih file
    function previewImage(event) {
        var input = event.target;
        var preview = document.getElementById('preview');

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    // Mengatur gambar default saat halaman dimuat
    window.addEventListener('DOMContentLoaded', function() {
        var defaultImage = 'img/nodata.jpg';
        var preview = document.getElementById('preview');
        preview.src = defaultImage;
        preview.style.display = 'block';
    });
</script>

<!-- Hitung Tanggal Keluar -->
<script>
    function hitungTanggalKeluar() {
        var tanggalMasuk = document.getElementById('tanggal_masuk').value;
        var durasiSewa = document.getElementById('durasi_sewa').value;
        var periodeSewa = document.getElementById('periode_sewa').value;

        if (tanggalMasuk !== '' && durasiSewa !== '' && periodeSewa !== '') {
            var tanggalKeluar = new Date(tanggalMasuk);
            if (periodeSewa === 'bulan') {
                tanggalKeluar.setMonth(tanggalKeluar.getMonth() + parseInt(durasiSewa));
            } else if (periodeSewa === 'tahun') {
                tanggalKeluar.setFullYear(tanggalKeluar.getFullYear() + parseInt(durasiSewa));
            }
            var tanggalKeluarFormatted = tanggalKeluar.toISOString().substring(0, 10);
            document.getElementById('tanggal_keluar').value = tanggalKeluarFormatted;
        } else {
            document.getElementById('tanggal_keluar').value = '';
        }
    }
</script>

<!-- Check Data -->
<script>
    function checkData(checkType, dataValue) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if (checkType === 'kontak') {
                    document.getElementById("kontak_message").innerHTML = this.responseText;
                } else if (checkType === 'email') {
                    document.getElementById("email_message").innerHTML = this.responseText;
                } else if (checkType === 'username') {
                    document.getElementById("username_message").innerHTML = this.responseText;
                }
            }
        };
        xhttp.open("GET", "check_data.php?check=" + checkType + "&" + checkType + "=" + dataValue, true);
        xhttp.send();
    }

    function checkKontak() {
        var kontak = document.getElementById('kontak_penghuni').value;
        if (kontak !== '') {
            checkData('kontak', kontak);
        } else {
            document.getElementById("kontak_message").innerHTML = '';
        }
    }

    function checkEmail() {
        var email = document.getElementById('email_penghuni').value;
        if (email !== '') {
            checkData('email', email);
        } else {
            document.getElementById("email_message").innerHTML = '';
        }
    }

    function checkUsername() {
        var username = document.getElementById('username').value;
        if (username !== '') {
            checkData('username', username);
        } else {
            document.getElementById("username_message").innerHTML = '';
        }
    }
</script>

<!-- Format Rupiah -->
<script>
    /* Dengan Rupiah */
    var dengan_rupiah = document.getElementById('dengan-rupiah');
    dengan_rupiah.addEventListener('keyup', function(e) {
        dengan_rupiah.value = formatRupiah(this.value, 'Rp. ');
    });

    /* Fungsi */
    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }
</script>

<!-- Bismillah WA BISA -->
<script>
    // Fungsi untuk mengirim data ke WhatsApp
    function kirimDataPenghuniTidakAktifWA(nama_penghuni, alamat_penghuni, kontak_penghuni, email_penghuni, id_kamar, tanggal_masuk, tanggal_keluar, tanda_jadi, username) {
        // Ambil nilai dari input form
        var namaPenghuni = document.getElementById('nama_penghuni').value;
        var alamatPenghuni = document.getElementById('alamat_penghuni').value;
        var kontakPenghuni = document.getElementById('kontak_penghuni').value;
        var emailPenghuni = document.getElementById('email_penghuni').value;
        var nomorKamarPenghuni = document.getElementById('id_kamar').value;
        var tanggalMasukPenghuni = document.getElementById('tanggal_masuk').value;
        var tanggalKeluarPenghuni = document.getElementById('tanggal_keluar').value;
        var tandaJadiPenghuni = document.getElementById('tanda_jadi').value;
        var usernamePenghuni = document.getElementById('username').value;

        // Ganti awalan 0 pada kontak penghuni dengan 62
        kontakPenghuni = kontakPenghuni.replace(/^0/, '62');

        // Persiapkan pesan untuk dikirim melalui WhatsApp
        var pesan = `
Wisma Mutiara Selaras
Jl. Banteng Raya 3 Gg. Sadewa No. 5 Ngabean Kulon, Sinduharjo, Kec. Ngaglik, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55581

Halo, ${namaPenghuni}. Berikut adalah Rincian Pendaftaran Anda:
Nama: ${namaPenghuni}
Alamat: ${alamatPenghuni}
Kontak: ${kontakPenghuni}
Email: ${emailPenghuni}

Nomor Kamar: ${nomorKamarPenghuni}
Tanggal Masuk/Check-In: ${tanggalMasukPenghuni}
Tanggal Keluar/Check-Out: ${tanggalKeluarPenghuni}
Tanda Jadi: ${tandaJadiPenghuni}

Username: ${usernamePenghuni}
Password: wms
`;

        // Kirim pesan via WhatsApp menggunakan link wa.me
        var whatsappLink = "https://wa.me/" + kontakPenghuni + "?text=" + encodeURIComponent(pesan);
        window.open(whatsappLink, "_blank");
    }
</script>


<!-- Style -->
<style>
    #kontak_message,
    #email_message,
    #username_message {
        margin-bottom: 10px;
    }

    #wajibdiisi {
        color: #FF0000;
    }
</style>





































<!-- WA -->
<script>
    function kirimDataWhatsApp(nomor_whatsapp, data_penghuni) {
        var nama_penghuni = data_penghuni.nama_penghuni;
        var kontak_penghuni = data_penghuni.kontak_penghuni;
        var alamat_penghuni = data_penghuni.alamat_penghuni;
        var email_penghuni = data_penghuni.email_penghuni;
        var nomor_kamar = data_penghuni.nomor_kamar;
        var tanggal_masuk = data_penghuni.tanggal_masuk;
        var tanggal_keluar = data_penghuni.tanggal_keluar;
        var tanda_jadi = data_penghuni.tanda_jadi;
        var username = data_penghuni.username;

        nomor_whatsapp = nomor_whatsapp.replace(/^0/, "62");

        // Format total tanda jadi menjadi format mata uang
        var formatted_tanda_jadi = formatCurrency(tanda_jadi);

        var formatted_message = "Halo, " + nama_penghuni + ". Berikut adalah rincian pendaftaran Anda:\n" +
            "Nama: " + nama_penghuni + "\n" +
            "Alamat: " + alamat_penghuni + "\n" +
            "Kontak: " + kontak_penghuni + "\n" +
            "Email: " + email_penghuni + "\n\n" +
            "Nomor Kamar: " + nomor_kamar + "\n" +
            "Tanggal Masuk/Check-In: " + tanggal_masuk + "\n" +
            "Tanggal Keluar/Check-Out: " + tanggal_keluar + "\n" +
            "Tanda Jadi: " + formatted_tanda_jadi + "\n\n" +
            "Username: " + username + "\n" +
            "Password: wms";

        var link = "https://wa.me/" + nomor_whatsapp + "?text=" + encodeURIComponent(formatted_message);

        window.open(link);
    }



    // Fungsi yang akan dijalankan jika pengguna memilih "Ya"
    // function kirimDataWhatsApp() {
    //     // Mengambil data dari form
    //     var nama = document.getElementById("nama_penghuni").value;
    //     var kontak = document.getElementById("kontak_penghuni").value;
    //     var alamat = "Wisma Mutiara Selaras\nJl. Banteng Raya 3 Gg. Sadewa No. 5 Ngabean Kulon, Sinduharjo, Kec. Ngaglik, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55581";
    //     var email = document.getElementById("email_penghuni").value;
    //     var nomorKamar = document.getElementById("id_kamar").value;
    //     var tanggalMasuk = document.getElementById("tanggal_masuk").value;
    //     var tanggalKeluar = document.getElementById("tanggal_keluar").value;
    //     var tandaJadi = document.getElementById("tanda_jadi").value;
    //     var username = document.getElementById("username").value;

    //     // Mengganti angka 0 dengan 62 pada nomor WhatsApp
    //     kontak = kontak.replace(/^0/, "62");

    //     // Format total tagihan menjadi format mata uang
    //     var formatted_tanda_jadi = formatCurrency(tandaJadi);

    //     // Buat pesan yang berisi data penghuni
    //     var pesan = "Halo, " + nama + ". Berikut adalah rincian pendaftaran Anda:\n" +
    //         "Nama: " + nama + "\n" +
    //         "Alamat: " + alamat + "\n" +
    //         "Kontak: " + kontak + "\n" +
    //         "Email: " + email + "\n\n" +
    //         "Nomor Kamar: " + nomorKamar + "\n" +
    //         "Tanggal Masuk/Check-In: " + tanggalMasuk + "\n" +
    //         "Tanggal Keluar/Check-Out: " + tanggalKeluar + "\n" +
    //         "Tanda Jadi: " + formatted_tanda_jadi + "\n\n" +
    //         "Username: " + username + "\n" +
    //         "Password: wms";

    //     // Ganti URL_API_WHATSAPP dengan URL API WhatsApp (gunakan nomor dan pesan yang sesuai)
    //     var link = "https://wa.me/" + kontak + "?text=" + encodeURIComponent(pesan);

    //     // Buka aplikasi WhatsApp atau URL API WhatsApp
    //     window.open(link);
    // }
</script>

if ($result === true) {
echo '
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
<script>
    Swal.fire({
        icon: "success",
        title: "Data Berhasil Ditambahkan",
        showConfirmButton: false,
        timer: 1500
    }).then(function() {
        window.location.href = "index.php?page=daftar_penghuni";
    });
</script>';
}

<!-- Kirim ke WhatsApp -->
<!-- <script>
    function showSuccessPopup(namaPenghuni) {
        Swal.fire({
            icon: 'success',
            title: 'Data Berhasil Ditambahkan',
            text: 'Kirim Data ke WhatsApp ' + namaPenghuni + '?',
            showCancelButton: true,
            confirmButtonText: 'Kirim',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                sendToWhatsApp(namaPenghuni);
            } else {
                window.location.href = 'index.php?page=daftar_penghuni';
            }
        });
    }

    function sendToWhatsApp(namaPenghuni, kontak) {
        var formatted_message = "Wisma Mutiara Selaras\n" +
            "Jl. Banteng Raya 3 Gg. Sadewa No. 5 Ngabean Kulon, Sinduharjo, Kec. Ngaglik, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55581\n\n" +
            "Halo, " + nama + " Berikut Adalah Rincian Pendaftaran Anda:\n" +
            "Nama: " + nama + "\n" +
            "Alamat: " + alamat + "\n" +
            "Kontak: " + kontak + "\n" +
            "Email: " + email + "\n\n" +
            "Nomor Kamar: " + id_kamar + "\n" +
            "Tanggal Masuk/Check-In: " + tanggal_masuk + "\n" +
            "Tanggal Keluar/Check-Out: " + tanggal_keluar + "\n" +
            "Tanda Jadi: " + tanda_jadi + "\n\n" +
            "Username: " + username + "\n" +
            "Password: wms";

        // Mengganti angka 0 dengan 62 pada nomor WhatsApp
        nomor_whatsapp = kontak.replace(/^0/, "62");

        var link = "https://wa.me/" + nomor_whatsapp + "?text=" + encodeURIComponent(formatted_message);
        window.open(link);
    }

    <?php if ($result === true) : ?>
        // Panggil fungsi untuk menampilkan popup jika data berhasil disimpan
        showSuccessPopup('<?php echo $data['nama']; ?>');
    <?php endif; ?>
</script> -->

<!-- coba -->
<!-- <script>
    function showSuccessPopup(namaPenghuni) {
        Swal.fire({
            icon: 'success',
            title: 'Data Berhasil Ditambahkan',
            text: 'Kirim Data ke WhatsApp ' + namaPenghuni + '?',
            showCancelButton: true,
            confirmButtonText: 'Kirim',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                kirimDataWhatsApp(namaPenghuni);
            } else {
                window.location.href = 'index.php?page=daftar_penghuni';
            }
        });
    }

    // Fungsi untuk kirim data ke WhatsApp
    function kirimDataWhatsApp(nama, alamat, kontak, email, id_kamar, tanggal_masuk, tanggal_keluar, tanda_jadi, username) {
        var formatted_message = "Wisma Mutiara Selaras\n" +
            "Jl. Banteng Raya 3 Gg. Sadewa No. 5 Ngabean Kulon, Sinduharjo, Kec. Ngaglik, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55581\n\n" +
            "Halo, " + nama + " Berikut Adalah Rincian Pendaftaran Anda:\n" +
            "Nama: " + nama + "\n" +
            "Alamat: " + alamat + "\n" +
            "Kontak: " + kontak + "\n" +
            "Email: " + email + "\n\n" +
            "Nomor Kamar: " + id_kamar + "\n" +
            "Tanggal Masuk/Check-In: " + tanggal_masuk + "\n" +
            "Tanggal Keluar/Check-Out: " + tanggal_keluar + "\n" +
            "Tanda Jadi: " + tanda_jadi + "\n\n" +
            "Username: " + username + "\n" +
            "Password: wms";

        // Mengganti angka 0 dengan 62 pada nomor WhatsApp
        nomor_whatsapp = kontak.replace(/^0/, "62");

        var link = "https://wa.me/" + nomor_whatsapp + "?text=" + encodeURIComponent(formatted_message);
        window.open(link);


        // var url = "https://api.whatsapp.com/send?phone=6281234567890&text=" + encodeURIComponent(text);
        // window.open(url, "_blank");
    }
</script> -->