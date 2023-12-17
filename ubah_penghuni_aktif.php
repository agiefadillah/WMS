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
        $id_penghuni = $_POST['id_penghuni'];
        $result = ubahPenghuniAktif($_POST, $id_penghuni);
        if ($result === true) {
            echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
            <script>
                Swal.fire({
                    icon: "success",
                    title: "Data Berhasil Diubah",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function () {
                    window.location.href = "index.php?page=daftar_penghuni";
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
                    title: "Gagal Mengubah Data",
                    showConfirmButton: false,
                    timer: 2500
                }).then(function () {
                    window.location.href = "index.php?page=daftar_penghuni";
                });
            </script>';
        }
    }
}

$id_penghuni = $_GET['id_penghuni'];
$up = query("SELECT penghuni.id_penghuni, users.nama, kamar.nomor_kamar, users.kontak, users.email, users.alamat, 
penghuni.tanggal_masuk_penghuni, penghuni.tanggal_keluar_penghuni, penghuni.identitas_penghuni, penghuni.id_kamar,
penghuni.keterangan_penghuni, users.username, penghuni.tanggal_registrasi_penghuni
FROM users 
INNER JOIN penghuni ON users.id_users = penghuni.id_users 
INNER JOIN kamar ON penghuni.id_kamar = kamar.id_kamar 
WHERE penghuni.id_penghuni = $id_penghuni")[0];

// Mengambil data jumlah tagihan dengan kategori "Tanda Jadi" dari tabel tagihan
$id_penghuni = $up['id_penghuni'];
$query_tagihan = query("SELECT jumlah_tagihan FROM tagihan WHERE id_penghuni = $id_penghuni AND kategori_tagihan = 'Tanda Jadi'");

// Menghitung total jumlah tagihan "Tanda Jadi"
$total_tagihan_tanda_jadi = 0;
foreach ($query_tagihan as $tagihan) {
    $total_tagihan_tanda_jadi += $tagihan['jumlah_tagihan'];
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

                        <input type="hidden" name="id_penghuni" value="<?= $up['id_penghuni']; ?>">

                        <h5 class="card-title mt-4">Tanggal Registrasi<span id="wajibdiisi">*</span></h5>
                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <input type="date" class="form-control" id="tanggal_registrasi" name="tanggal_registrasi" value="<?= $up['tanggal_registrasi_penghuni']; ?>" required>
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
                                <input type="text" class="form-control " placeholder="Masukkan Nama Penghuni" name="nama_penghuni" value="<?= $up['nama']; ?>" required>
                            </div>
                        </div>
                    </div>

                    <h5 class="card-title mt-1">Alamat</h5>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <input type="text" class="form-control " placeholder="Masukkan Alamat Asal Penghuni" name="alamat_penghuni" value="<?= $up['alamat']; ?>">
                            </div>
                        </div>
                    </div>

                    <h5 class="card-title mt-1">Kontak<span id="wajibdiisi">*</span></h5>
                    <h6>Harap Gunakan Format: 08 | Contoh Data: 081234567890</h6>
                    <span class="badge badge-danger" id="kontak_message"></span>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <input type="number" class="form-control" placeholder="Masukkan Kontak Penghuni" name="kontak_penghuni" id="kontak_penghuni" value="<?= $up['kontak']; ?>" onkeyup="checkKontakUbah()" required>
                            </div>
                        </div>
                    </div>

                    <h5 class="card-title mt-1">Email</h5>
                    <span class="badge badge-danger" id="email_message"></span>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <input type="email" class="form-control" placeholder="Masukkan Email Penghuni" name="email_penghuni" id="email_penghuni" value="<?= $up['email']; ?>" onkeyup="checkEmailUbah()">
                            </div>
                        </div>
                    </div>

                    <h5 class="card-title mt-1">Foto Identitas Penghuni</h5>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <input name="identitas_penghuni" id="identitas_penghuni" type="file" class="form-control" onchange="previewImage(event)">
                                <?php if (!empty($up['identitas_penghuni'])) : ?>
                                    <img id="identitas_penghuni_preview" src="<?php echo $up['identitas_penghuni']; ?>" alt="identitas_penghuni" style="max-width: 200px; margin-top: 10px;">
                                    <br><a href="<?php echo $up['identitas_penghuni'] ?>">Lihat Identitas Penghuni</a>
                                <?php endif; ?>
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
                    <h4 class="card-title mt-1">Nomor Kamar dan Durasi Sewa Penghuni</h4>

                    <h5 class="card-title mt-4">Nomor Kamar<span id="wajibdiisi">*</span></h5>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <select class="form-control" id="id_kamar" name="id_kamar" disabled>
                                    <?php
                                    // Ambil ID Kamar dari tabel penghuni
                                    $id_kamar_terpilih = $up['id_kamar'];

                                    // Ambil data kamar dari tabel kamar
                                    $kamar = query("SELECT * FROM kamar ORDER BY nomor_kamar ASC");

                                    foreach ($kamar as $kmr) {
                                        // Tandai option yang sesuai dengan ID Kamar yang dipilih sebelumnya sebagai "selected"
                                        $selected = ($kmr['id_kamar'] == $id_kamar_terpilih) ? 'selected' : '';
                                        // Tampilkan data kamar beserta harganya
                                        echo '<option value="' . $kmr['id_kamar'] . '" ' . $selected . '>' . $kmr['nomor_kamar'] . ' - Rp. ' . number_format($kmr['harga_kamar']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <h5 class="card-title mt-1">Tanggal Masuk | Check-In<span id="wajibdiisi">*</span></h5>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="<?= $up['tanggal_masuk_penghuni']; ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <h5 class="card-title mt-1">Tanggal Keluar | Check-Out<span id="wajibdiisi">*</span></h5>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <input type="date" class="form-control" id="tanggal_keluar" name="tanggal_keluar" value="<?= $up['tanggal_keluar_penghuni']; ?>" readonly>
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
                                <textarea class="form-control" rows="3" name="keterangan_penghuni" id="keterangan_penghuni"><?= $up['keterangan_penghuni']; ?></textarea>
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

<script>
    // Fungsi untuk menampilkan gambar pratinjau saat memilih file
    function previewImage(event) {
        var reader = new FileReader();
        var imageField = document.getElementById("identitas_penghuni_preview");

        reader.onload = function() {
            if (reader.readyState == 2) {
                imageField.src = reader.result;
            }
        }

        reader.readAsDataURL(event.target.files[0]);
    }

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

<script>
    var existingKontak = '<?php echo $up['kontak']; ?>'; // Simpan kontak penghuni yang ada di database ke dalam variabel JavaScript
    var existingEmail = '<?php echo $up['email']; ?>';

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
        var kontak = document.getElementById('kontak_penghuni').value;
        if (kontak !== existingKontak) {
            checkData('kontak', kontak);
        } else {
            document.getElementById("kontak_message").innerText = '';
        }
    }

    function checkEmailUbah() {
        var email = document.getElementById('email_penghuni').value;
        if (email !== existingEmail) {
            checkData('email', email);
        } else {
            document.getElementById("email_message").innerHTML = '';
        }
    }
</script>

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