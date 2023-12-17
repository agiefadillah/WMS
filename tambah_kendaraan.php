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
    $result = tambahKendaraan($_POST);

    if ($result > 0) {
        echo '
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
        <script>
            Swal.fire({
                icon: "success",
                title: "Data Berhasil Ditambahkan",
                showConfirmButton: false,
                timer: 1500
            }).then(function () {
                window.location.href = "index.php?page=daftar_kendaraan";
            });
        </script>';
    } elseif ($result === -1) {
        echo '
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
        <script>
            Swal.fire({
                icon: "warning",
                title: "Data Gagal Ditambahkan Karena Data Kendaraan Sudah Ada",
                showConfirmButton: false,
                timer: 2500
            }).then(function () {
                window.location.href = "index.php?page=tambah_kendaraan";
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
                    window.location.href = "index.php?page=daftar_kendaraan";
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
                    <h3 class="card-title">Tambah Data Kendaraan</h3>
                    <form action="" method="POST">
                        <h5 class="card-title mt-4">Nama Pemilik</h5>
                        <div class="col-sm">
                            <select class="form-control" name="id_penghuni" required>
                                <option value="">Pilih Penghuni Pemilik Kendaraan</option>
                                <?php
                                $query = "SELECT p.id_penghuni, u.nama, k.nomor_kamar
                                    FROM penghuni p
                                    INNER JOIN users u ON p.id_users = u.id_users
                                    INNER JOIN kamar k ON p.id_kamar = k.id_kamar";
                                $result = query($query);

                                foreach ($result as $row) :
                                ?>
                                    <option value="<?= $row['id_penghuni']; ?>"><?= $row['nama'] . ' - Kamar ' . $row['nomor_kamar']; ?></option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                        </div>

                        <h5 class="card-title mt-4">Nomor Kendaraan</h5>
                        <div class="col-sm">
                            <input type="text" class="form-control" placeholder="Masukkan Nomor Kendaraan | Contoh: (AB 0000 XXX)" name="nomor_kendaraan" required>
                        </div>

                        <h5 class="card-title mt-4">Jenis Kendaraan</h5>
                        <div class="col-sm">
                            <select class="form-control" id="jenis_kendaraan" name="jenis_kendaraan" required>
                                <option value="">Pilih Jenis Kendaraan</option>
                                <option value="Motor">Motor</option>
                                <option value="Mobil">Mobil</option>
                                <option value="Sepeda">Sepeda</option>
                                <option value="Motor Listrik">Motor Listrik</option>
                                <option value="Mobil Listrik">Mobil Listrik</option>
                            </select>
                        </div>

                        <h5 class="card-title mt-4">Model Kendaraan</h5>
                        <div class="col-sm">
                            <input type="text" class="form-control" placeholder="Masukkan Model Kendaraan" name="model_kendaraan">
                        </div>

                        <div class="col-sm mt-4">
                            <input type="submit" name="simpan" class="btn btn-success" value="Simpan">
                            <a href="index.php?page=daftar_kendaraan" class="btn btn-primary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>