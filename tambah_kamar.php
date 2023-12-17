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
    $result = tambahKamar($_POST);
    if ($result > 0) {
        echo '
       <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
       <script>
           Swal.fire({
               icon: "success",
               title: "Data Berhasil Ditambahkan",
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
                    window.location.href = "index.php?page=tambah_kamar";
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
                    window.location.href = "index.php?page=tambah_kamar";
                });
            </script>';
    }
}

?>

<div class="container-fluid">
    <div class="row">

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Tambah Data Kamar</h3>

                    <form action="" method="POST">
                        <h5 class="card-title mt-4">Lokasi Kamar</h5>
                        <div class="col-sm">
                            <select class="select2 form-select shadow-none" style="width: 100%; height:36px;" id="lokasi_kamar" name="lokasi_kamar">
                                <option value="">Pilih Lokasi Kamar</option>
                                <?php
                                $lokasi_kamar = query("SELECT * FROM lokasi_kamar");
                                foreach ($lokasi_kamar as $lokasi) :
                                    echo "<option value='" . $lokasi['nama_lokasi'] . "'>" . $lokasi['nama_lokasi'] . "</option>";
                                endforeach;
                                ?>
                            </select>
                        </div>


                        <h5 class="card-title mt-4">Nomor Kamar</h5>
                        <div class="col-sm">
                            <input type="text" class="form-control " placeholder="Masukkan Nomor Kamar (Contoh: 123)" name="nomor_kamar" required>
                        </div>

                        <h5 class="card-title mt-4">Harga</h5>
                        <div class="col-sm">
                            <input type="number" class="form-control " placeholder="Masukkan Harga Kamar (Contoh: 1500000)" name="harga_sewa" required>
                        </div>

                        <h5 class="card-title mt-4">Status Kamar</h5>
                        <div class="col-sm">
                            <select class="select2 form-select shadow-none" style="width: 100%; height:36px;" name="status_kamar" required>
                                <option value="">Pilih Status Kamar</option>
                                <option value="Tersedia">Tersedia</option>
                                <option value="Renovasi">Renovasi</option>
                                <option value="Perbaikan">Perbaikan</option>
                            </select>
                        </div>

                        <div class="col-sm mt-4">
                            <input type="submit" name="simpan" class="btn btn-success" value="Simpan">
                            <a href="index.php?page=daftar_kamar" class="btn btn-primary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>