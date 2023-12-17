<?php
session_start();

// Ambil id user dari session
$id_users = $_SESSION['id_users'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['simpan'])) {
        $result = ubahAkun($_POST);
        if ($result === true) {
            echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
            <script>
                Swal.fire({
                    icon: "success",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = "index.php?page=akun";
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
        } elseif ($result === 'passwordlama') {
            echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
            <script>
                Swal.fire({
                    icon: "warning",
                    title: "Password Lama yang Anda Masukkan Salah",
                    showConfirmButton: false,
                    timer: 1500
                });
            </script>';
        } elseif ($result === 'konfirmasipass') {
            echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
            <script>
                Swal.fire({
                    icon: "warning",
                    title: "Konfirmasi Password Tidak Sesuai dengan Password Baru",
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
    <div class="row">
        <div class="col-md-6">
            <div class="card border-dark">
                <div class="card-header bg-dark">
                    <h4 class="mb-0 text-white">Pengaturan Akun</h4>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div id="logins-part" class="content" role="tabpanel" aria-labelledby="logins-part-trigger">
                            <div class="form-group">
                                <label for="username">Username</label><br>
                                <span class="badge badge-danger" id="username_message"></span>
                                <input type="text" class="form-control" id="username" name="username" value="<?= $_SESSION['username']; ?>" onkeyup="checkUsernameUbah(this.value)">
                            </div>
                            <div class="form-group">
                                <label for="username">Email</label><br>
                                <span class="badge badge-danger" id="email_message"></span>
                                <input type="email" class="form-control" id="email" name="email" value="<?= $_SESSION['email']; ?>" onkeyup="checkEmailUbah(this.value)">
                            </div>
                            <div class="form-group">
                                <label for="username">Nomor Telepon</label><br>
                                <span class="badge badge-danger" id="kontak_message"></span>
                                <input type="number" class="form-control" id="kontak" name="kontak" value="<?= $_SESSION['kontak']; ?>" onkeyup="checkKontakUbah(this.value)">
                            </div>
                            <div class="form-group">
                                <label for="password_lama">Password Lama</label>
                                <input type="password" class="form-control" id="password_lama" name="password_lama">
                            </div>
                            <div class="form-group">
                                <label for="password">Password Baru</label>
                                <input type="password" class="form-control" id="password_baru" name="password_baru">
                            </div>
                            <div class="form-group">
                                <label for="konfirmasi_password">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Check Data -->
<script>
    var existingKontak = '<?php echo $_SESSION['kontak']; ?>'; // Simpan kontak penghuni yang ada di database ke dalam variabel JavaScript
    var existingEmail = '<?php echo $_SESSION['email']; ?>';
    var existingUsername = '<?php echo $_SESSION['username']; ?>';

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

    function checkKontakUbah() {
        var kontak = document.getElementById('kontak').value;
        if (kontak !== existingKontak) {
            checkData('kontak', kontak);
        } else {
            document.getElementById("kontak_message").innerText = '';
        }
    }

    function checkEmailUbah() {
        var email = document.getElementById('email').value;
        if (email !== existingEmail) {
            checkData('email', email);
        } else {
            document.getElementById("email_message").innerHTML = '';
        }
    }

    function checkUsernameUbah() {
        var username = document.getElementById('username').value;
        if (username !== existingUsername) {
            checkData('username', username);
        } else {
            document.getElementById("username_message").innerHTML = '';
        }
    }
</script>