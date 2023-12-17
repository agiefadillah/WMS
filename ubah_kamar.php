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

if (isset($_POST['ubah'])) {
    $result = ubahKamar($_POST, $_POST['id_kamar']);
    if ($result > 0) {
        echo '
       <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
       <script>
           Swal.fire({
               icon: "success",
               title: "Data Berhasil Diubah",
               showConfirmButton: false,
               timer: 1000
           }).then(function () {
               window.location.href = "index.php?page=daftar_kamar";
           });
       </script>';
    } else if ($result == 0) {
        echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
            <script>
                Swal.fire({
                    icon: "warning",
                    title: "Data dengan Nomor Kamar Tersebut Sudah Ada",
                    showConfirmButton: false,
                    timer: 2000
                }).then(function () {
                    window.location.href = "index.php?page=ubah_kamar&id_kamar=" + data;
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
                    window.location.href = "index.php?page=daftar_kamar";
                });
            </script>';
    }
}

$id = $_GET['id_kamar'];
$uk = query("SELECT * FROM kamar WHERE id_kamar='$id'")[0];
$lokasi_kamar = query("SELECT * FROM lokasi_kamar");

?>

<div class="container-fluid">
    <div class="row">

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Ubah Data Kamar: Kamar <?php echo $uk["nomor_kamar"] ?></h3>

                    <form action="" method="POST">
                        <input type="hidden" name="id_kamar" value="<?= $uk['id_kamar']; ?>">
                        <input type="hidden" name="status_kamar" value="<?= $uk['status_kamar']; ?>">

                        <h5 class="card-title mt-4">Lokasi Kamar</h5>
                        <div class="col-sm">
                            <select class="select2 form-select shadow-none" style="width: 100%; height:36px;" id="lokasi_kamar" name="lokasi_kamar" required>
                                <?php foreach ($lokasi_kamar as $lokasi) : ?>
                                    <option value="<?= $lokasi['nama_lokasi']; ?>" <?php if ($uk['id_lokasi'] == $lokasi['id_lokasi']) {
                                                                                        echo "selected";
                                                                                    } ?>><?= $lokasi['nama_lokasi']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <h5 class="card-title mt-4">Nomor Kamar</h5>
                        <div class="col-sm">
                            <input type="text" class="form-control " placeholder="Masukkan Nomor Kamar (Contoh: 123)" name="nomor_kamar" value="<?= $uk['nomor_kamar']; ?>" required>
                        </div>

                        <h5 class="card-title mt-4">Harga</h5>
                        <div class="col-sm">
                            <input type="text" class="form-control " placeholder="Masukkan Harga Kamar (Contoh: 1500000)" name="harga_sewa" value="<?= $uk['harga_kamar']; ?>" required>
                        </div>

                        <h5 class="card-title mt-4">Status Kamar</h5>
                        <div class="col-sm">
                            <select class="select2 form-select shadow-none" style="width: 100%; height:36px;" name="status_kamar">
                                <option value="Tersedia" <?php
                                                            if ($uk['status_kamar'] == "Tersedia") {
                                                                echo "selected";
                                                            }
                                                            ?>>
                                    Tersedia
                                </option>
                                <option value="Renovasi" <?php
                                                            if ($uk['status_kamar'] == "Renovasi") {
                                                                echo "selected";
                                                            }
                                                            ?>>
                                    Renovasi
                                </option>
                                <option value="Perbaikan" <?php
                                                            if ($uk['status_kamar'] == "Perbaikan") {
                                                                echo "selected";
                                                            }
                                                            ?>>
                                    Perbaikan
                                </option>

                            </select>
                        </div>


                        <div class="col-sm mt-4">
                            <input type="submit" name="ubah" class="btn btn-success" value="Ubah">
                            <a href="index.php?page=daftar_kamar" class="btn btn-primary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>