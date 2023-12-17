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

function resetPassword()
{
    $koneksi = connectDB();

    if (isset($_GET['token'])) {
        $resetToken = $_GET['token'];

        // Menghindari serangan SQL injection
        $resetToken = mysqli_real_escape_string($koneksi, $resetToken);

        // Cek apakah token ada di database dan belum kadaluarsa
        $query = "SELECT * FROM users WHERE reset_token = '$resetToken' AND reset_token_expiry >= NOW() LIMIT 1";
        $result = mysqli_query($koneksi, $query);

        if (mysqli_num_rows($result) == 1) {
            // Reset password form
            echo '<h2>Reset Password</h2>';
            echo '<form action="" method="POST">';
            echo '<input type="hidden" name="reset_token" value="' . $resetToken . '">';
            echo '<div class="form-group">';
            echo '<label for="new_password">New Password</label>';
            echo '<input type="password" name="new_password" id="new_password" class="form-control" required>';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label for="confirm_password">Confirm Password</label>';
            echo '<input type="password" name="confirm_password" id="confirm_password" class="form-control" required>';
            echo '</div>';
            echo '<button type="submit" name="submit_reset" class="btn btn-primary">Reset Password</button>';
            echo '</form>';
        } else {
            echo '<div class="alert alert-danger text-center" role="alert">Token Reset Tidak Valid atau Sudah Kadaluarsa</div>';
        }

        mysqli_free_result($result);
    }

    mysqli_close($koneksi);
}

// Fungsi untuk mengubah password
function changePassword()
{
    $koneksi = connectDB();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_reset'])) {
        $resetToken = $_POST['reset_token'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($newPassword === $confirmPassword) {
            // Menghindari serangan SQL injection
            $resetToken = mysqli_real_escape_string($koneksi, $resetToken);
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update password di database dan hapus token reset
            $updateQuery = "UPDATE users SET password = '$hashedPassword', reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = '$resetToken'";
            mysqli_query($koneksi, $updateQuery);

            echo '<div class="alert alert-success text-center" role="alert">Password Berhasil Direset <br>
            Silakan Masuk dengan Password Baru Anda</div>';
        } else {
            echo '<div class="alert alert-danger text-center" role="alert">Password dan Konfirmasi Password Tidak Cocok</div>';
        }
    }

    mysqli_close($koneksi);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Wisma Mutiara Selaras - Reset Password</title>
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
                                <?php
                                // Jika token belum kadaluarsa, tampilkan form reset password
                                if (isset($_GET['token'])) {
                                    changePassword();
                                    resetPassword();
                                }
                                ?>
                                <a href="login.php" class="back-to-login-link" style="display: block; text-align: center;">Back to Login</a>
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