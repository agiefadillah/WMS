<?php

if ($_SESSION['tipe'] != 'pemilik' && $_SESSION['tipe'] != 'staff') {
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

if (isset($_POST['simpan'])) {
    $result = WMSCheckIn($_POST);
    if ($result > 0) {
        echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
            <script>
                Swal.fire({
                    icon: "success",
                    title: "Data Berhasil Diubah",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function () {
                    window.location.href = "index.php?page=check_in";
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
                    timer: 1500
                }).then(function () {
                    window.location.href = "index.php?page=form_check_in";
                });
            </script>';
    }
}

$id_kamar = $_GET['id_kamar'];
$ci = query("SELECT * FROM kamar WHERE id_kamar='$id_kamar'")[0];

?>

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title text-truncate text-dark font-weight-medium mb-1">Check-In</h3>
                    <h5 class="card-title text-truncate text-dark font-weight-medium mb-1">Kamar <?php echo $ci["nomor_kamar"] ?></h5>
                    <h6 class="card-title text-truncate text-dark font-weight-medium mb-1"><?= 'Rp. ' . number_format($ci['harga_kamar']) ?></h6>

                    <form action="" method="POST">
                        <input type="hidden" name="id_kamar" value="<?= $ci['id_kamar']; ?>">

                        <h5 class="card-title mt-4">Nama Penghuni</h5>
                        <h6><a href="index.php?page=daftar_penghuni">Klik Disini</a> Jika Nama Penghuni yang Dimaksud Tidak Ditemukan untuk Ditambah atau Diaktifkan pada Daftar Penghuni.</h6>
                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <select class="form-control" name="id_penghuni" required>
                                        <option value="">Pilih Penghuni</option>
                                        <?php
                                        $query = "SELECT p.id_penghuni, u.nama
                                        FROM penghuni p
                                        INNER JOIN users u ON p.id_users = u.id_users
                                        INNER JOIN kamar k ON p.id_kamar = k.id_kamar
                                        WHERE p.status_penghuni = 'aktif'"; // Tambahkan kondisi WHERE untuk penghuni yang statusnya aktif
                                        $result = query($query);

                                        foreach ($result as $row) :
                                        ?>
                                            <option value="<?= $row['id_penghuni']; ?>"><?= $row['nama']; ?></option>
                                        <?php
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
                                    <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="<?php echo date('Y-m-d'); ?>" readonly>
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

                        <div class="col-sm mt-4">
                            <input type="submit" name="simpan" class="btn btn-success" value="Simpan">
                            <a href="index.php?page=check_in" class="btn btn-primary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

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