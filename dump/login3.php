<?php
session_start();
require 'config/function.php';

function login($username, $password, $koneksi)
{
    $loginQuery = mysqli_query($koneksi, "SELECT * FROM users WHERE (username = '$username' OR email = '$username' OR kontak = '$username')");

    if (mysqli_num_rows($loginQuery) > 0) {
        $user = mysqli_fetch_assoc($loginQuery);
        if (password_verify($password, $user["password"])) {
            // Session
            $_SESSION["login"] = true;
            $_SESSION["user_id"] = $user["id_users"];
            $_SESSION["role"] = $user["role"];
            header("Location: index.php");
            exit;
        } else {
            $salahPass = true;
        }
    } else {
        $salahUser = true;
    }
}

if (isset($_POST['masuk'])) {
    $username = $_POST['username_email_kontak'];
    $password = $_POST['password'];

    login($username, $password, $koneksi);
}
?>

<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Wisma Mutiara Selaras</title>
    <link rel="icon" type="image/png" sizes="16x16" href="img/icon1.png">

    <link href="app/dist/css/style.min.css" rel="stylesheet">
</head>

<body>
    <div class="main-wrapper">

        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>

        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative" style="background:url(img/login-bg.png) no-repeat center center;">
            <div class="auth-box row">
                <div class="col-lg-12 col-md-14 bg-white">
                    <div class="p-3">
                        <div class="text-center">
                            <img src="img/logo2.png" alt="wrapkit" style="max-width: 30%;">
                        </div>

                        <h4 class="mt-2 text-center text-dark">Sign In</h4>

                        <?php if (isset($salahUser)) : ?>
                            <div class="alert alert-danger" role="alert">
                                Username tidak ditemukan
                            </div>
                        <?php endif; ?>
                        <?php if (isset($salahPass)) : ?>
                            <div class="alert alert-danger" role="alert">
                                Password yang anda masukan salah
                            </div>
                        <?php endif; ?>

                        <form class="mt-4" method="POST">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="email">Username, Email or Phone Number</label>
                                        <input class="form-control" id="username_email_kontak" type="text" name="username_email_kontak" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="password">Password</label>
                                        <input class="form-control" id="password" type="password" name="password" required>
                                    </div>
                                </div>
                                <div class="col-lg-12 text-center">
                                    <button type="submit" class="btn btn-block btn-dark">Sign In</button>
                                </div>
                                <div class="col-lg-12 text-center mt-2">
                                    Don't have an account? <a href="#" class="text-danger">Sign Up</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="app/assets/libs/jquery/dist/jquery.min.js "></script>
    <script src="app/assets/libs/popper.js/dist/umd/popper.min.js "></script>
    <script src="app/assets/libs/bootstrap/dist/js/bootstrap.min.js "></script>
    <script>
        $(".preloader ").fadeOut();
    </script>
</body>

</html>