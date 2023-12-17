<?php
session_start();

// Fungsi untuk melakukan koneksi ke database
function connectDB()
{
    date_default_timezone_set("Asia/Jakarta");

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

        $kontak = $_POST['kontak'];

        // Menghindari serangan SQL injection
        $kontak = mysqli_real_escape_string($koneksi, $kontak);

        // Cek apakah email ada di database
        $query = "SELECT * FROM users WHERE kontak = '$kontak' LIMIT 1";
        $result = mysqli_query($koneksi, $query);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            // Kirim pesan WhatsApp dengan link reset password
            $resetToken = bin2hex(random_bytes(11)); // Token untuk link reset password

            // Simpan token reset password dan waktu kadaluarsa token di database (misalnya, dalam tabel reset_password)
            // Di sini saya asumsikan ada kolom reset_token dan reset_token_expiry di tabel users
            $expiryTime = date('Y-m-d H:i:s', strtotime('+5 minute'));  // Token akan kadaluarsa dalam 1 jam
            $updateQuery = "UPDATE users SET reset_token = '$resetToken', reset_token_expiry = '$expiryTime' WHERE id_users = " . $user['id_users'];
            mysqli_query($koneksi, $updateQuery);

            $resetLink = "http://localhost/bismillah/reset_password.php?token=" . $resetToken;

            // Kirim pesan WhatsApp ke nomor yang dimasukkan dengan link reset password
            $nomor_whatsapp = str_replace('0', '62', $kontak);
            $formatted_message =
                "Wisma Mutiara Selaras" . "\n" .
                "Jl. Banteng Raya 3 Gg. Sadewa No. 5 Ngabean Kulon, Sinduharjo, Kec. Ngaglik, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55581" . "\n\n" .
                "Berikut Tautan untuk Mereset Password Anda: " . "\n" . $resetLink .
                "\n\n" . "Tautan Akan Kadaluarsa dalam 5 Menit";

            $link = "https://wa.me/" . $nomor_whatsapp . "?text=" . urlencode($formatted_message);
            mysqli_query($koneksi, $updateQuery);
            header("Location: " . $link);
            exit();
        } else {
            $_SESSION['reset_error'] = 'Kontak Tidak Terdaftar.';
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
                                        <label for="kontak" class="sr-only">Kontak</label>
                                        <input type="kontak" name="kontak" id="kontak" class="form-control" placeholder="Masukkan Nomor Telepon Anda dengan Format 08xxx" required>
                                    </div>
                                    <input name="reset_password" id="reset_password" class="btn btn-block login-btn mb-4" type="submit" value="Reset Password" onclick="sendWhatsApp()">
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