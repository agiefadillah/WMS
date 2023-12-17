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
    $result = ubahRekening($_POST);
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
                window.location.href = "index.php?page=daftar_rekening";
            });
        </script>';
    } elseif ($result === -1) {
        echo '
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
        <script>
            Swal.fire({
                icon: "warning",
                title: "Data Gagal Diubah Karena Data Rekening Pembayaran Sudah Ada",
                showConfirmButton: false,
                timer: 2500
            }).then(function () {
                window.location.href = "index.php?page=ubah_rekening&id_rekening=" + data;
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
                    window.location.href = "index.php?page=daftar_rekening";
                });
            </script>';
    }
}

$id = $_GET['id_rekening'];
$u_rek = query("SELECT * FROM rekening WHERE id_rekening='$id'")[0];

?>

<!-- Main content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Ubah Data Rekening Pembayaran</h3>
                    <form action="" method="POST">
                        <input type="hidden" name="id_rekening" value="<?= $u_rek['id_rekening']; ?>">

                        <h5 class="card-title mt-4">Jenis Pembayaran</h5>
                        <div class="col-sm">
                            <input type="text" class="form-control" placeholder="Masukkan Jenis Pembayaran" name="jenis_pembayaran" value="<?= $u_rek['jenis_pembayaran']; ?>" required>
                        </div>

                        <h5 class="card-title mt-4">Nomor Rekening</h5>
                        <div class="col-sm">
                            <input type="number" class="form-control" placeholder="Masukkan Nomor Rekening" name="nomor_rekening" value="<?= $u_rek['nomor_rekening']; ?>" required>
                        </div>

                        <h5 class="card-title mt-4">Nama Pemilik Rekening</h5>
                        <div class="col-sm">
                            <input type="text" class="form-control" placeholder="Masukkan Nama Pemilik Rekening" name="pemilik_rekening" value="<?= $u_rek['pemilik_rekening']; ?>" required>
                        </div>

                        <div class="col-sm mt-4">
                            <input type="submit" name="simpan" class="btn btn-success" value="Simpan">
                            <a href="index.php?page=daftar_rekening" class="btn btn-primary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>