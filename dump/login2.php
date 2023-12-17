<?php
session_start();

// Fungsi untuk melakukan koneksi ke database
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

// Fungsi untuk melakukan login
function login()
{
    $koneksi = connectDB();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
        $usernameEmailKontak = $_POST['username_email_kontak'];
        $password = $_POST['password'];

        // Mengecek apakah pengguna menggunakan email, username, atau nomor HP
        $identifierType = filter_var($usernameEmailKontak, FILTER_VALIDATE_EMAIL) ? 'email' : (is_numeric($usernameEmailKontak) ? 'kontak' : 'username');

        // Menghindari serangan SQL injection
        $identifier = mysqli_real_escape_string($koneksi, $usernameEmailKontak);
        $password = mysqli_real_escape_string($koneksi, $password);

        // Query untuk mendapatkan data pengguna berdasarkan email, username, atau nomor HP
        $query = "SELECT * FROM users WHERE $identifierType = '$identifier' LIMIT 1";
        $result = mysqli_query($koneksi, $query);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            // Memverifikasi password yang dimasukkan dengan password yang ada di database
            if (password_verify($password, $user['password'])) {
                // Mengatur nilai session 'id_users' setelah login berhasil
                $_SESSION['id_users'] = $user['id_users'];
                $_SESSION['tipe'] = $user['tipe'];

                $_SESSION['username'] = $user['username'];
                $_SESSION['nama'] = $user['nama'];
                $_SESSION['kontak'] = $user['kontak'];
                $_SESSION['email'] = $user['email'];

                // Jika tipe pengguna adalah 'penghuni', ambil juga session 'id_penghuni'
                if ($user['tipe'] == 'penghuni') {
                    $id_users = $user['id_users'];
                    $queryPenghuni = "SELECT id_penghuni FROM penghuni WHERE id_users = '$id_users' LIMIT 1";
                    $resultPenghuni = mysqli_query($koneksi, $queryPenghuni);

                    if (mysqli_num_rows($resultPenghuni) == 1) {
                        $penghuni = mysqli_fetch_assoc($resultPenghuni);
                        $_SESSION['id_penghuni'] = $penghuni['id_penghuni'];
                    }

                    mysqli_free_result($resultPenghuni);
                }

                // Mengarahkan pengguna ke halaman berdasarkan tipe role
                switch ($user['tipe']) {
                    case 'pemilik':
                        header("Location: dashboard/index.php");
                        exit();
                        break;
                    case 'penjaga':
                        header("Location: dashboard/index.php");
                        exit();
                        break;
                    case 'penghuni':
                        header("Location: dashboard/index.php");
                        exit();
                        break;
                }
            } else {
                echo "Password Salah";
            }
        } else {
            echo "Akun tidak ditemukan";
        }

        mysqli_free_result($result);
    }

    mysqli_close($koneksi);
}

// Memanggil fungsi login
login();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Wisma Mutiara Selaras</title>
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
                        <div class="col-md-5">
                            <img src="app/img/rooms/room-4.jpg" alt="login" class="login-card-img">
                        </div>
                        <div class="col-md-7">
                            <div class="card-body">
                                <div class="brand-wrapper text-center">
                                    <img src="img/BlackWMS.png" alt="logo" width="35%">
                                </div>
                                <p class="login-card-description text-center">Sign Into Your Account</p>
                                <form action="" method="POST" style="max-width: 400px; margin: 0 auto;">
                                    <div class="form-group">
                                        <label for="email" class="sr-only">Email</label>
                                        <input type="text" name="username_email_kontak" id="username_email_kontak" class="form-control" placeholder="Email, Username atau Nomor HP" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="sr-only">Password</label>
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                                    </div>
                                    <input name="login" id="login" class="btn btn-block login-btn mb-4" type="submit" value="Login">
                                </form>
                                <a href="forgot_password.php" class="forgot-password-link" style="display: block; text-align: center;">Forgot Password?</a>
                                <a href="index.php" class="back-to-web-link" style="display: block; text-align: center; color: #777;">Kembali</a>
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