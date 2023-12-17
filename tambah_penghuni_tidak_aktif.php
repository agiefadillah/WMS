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
                    text: "Apakah Ingin Membuat Tanda Jadi?",
                    showCancelButton: true,
                    confirmButtonText: "Ya",
                    cancelButtonText: "Tidak",
                }).then(function(result) {
                    if (result.isConfirmed) {
                        window.location.href = "index.php?page=tambah_tagihan_tanda_jadi";
                    } else {
                        window.location.href = "index.php?page=daftar_penghuni";
                    }
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