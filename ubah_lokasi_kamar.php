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
    $result = ubahLokasiKamar($_POST);
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
                    window.location.href = "index.php?page=daftar_lokasi_kamar";
                });
            </script>';
    } else if ($result == 0) {
        echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
            <script>
                Swal.fire({
                    icon: "warning",
                    title: "Lokasi Kamar Sudah Ada",
                    showConfirmButton: false,
                    timer: 2500
                }).then(function () {
                    window.location.href = "index.php?page=ubah_lokasi_kamar&id_lokasi=" + data;
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
                    window.location.href = "index.php?page=daftar_lokasi_kamar";
                });
            </script>';
    }
}

$id = $_GET['id_lokasi'];
$uk = query("SELECT * FROM lokasi_kamar WHERE id_lokasi='$id'")[0];

?>

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Ubah Lokasi Kamar: <?php echo $uk["nama_lokasi"] ?></h3>
                    <form action="" method="POST">
                        <input type="hidden" name="id_lokasi" value="<?= $uk['id_lokasi']; ?>">

                        <h5 class="card-title mt-4">Nama Lokasi</h5>
                        <div class="col-sm">
                            <input type="text" class="form-control " placeholder="Masukkan Nama Lokasi" name="nama_lokasi" value="<?= $uk['nama_lokasi']; ?>" required>
                        </div>

                        <div class="col-sm mt-4">
                            <input type="submit" name="simpan" class="btn btn-success" value="Simpan">
                            <a href="index.php?page=daftar_lokasi_kamar" class="btn btn-primary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>