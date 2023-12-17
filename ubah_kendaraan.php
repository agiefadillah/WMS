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

// Ambil data kendaraan yang akan diubah
$id_kendaraan = $_GET['id_kendaraan'];
$queryKendaraan = "SELECT * FROM kendaraan WHERE id_kendaraan = $id_kendaraan";
$dataKendaraan = query($queryKendaraan)[0];

if (isset($_POST['simpan'])) {
    $result = ubahKendaraan($id_kendaraan, $_POST);

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
                window.location.href = "index.php?page=daftar_kendaraan";
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
                window.location.href = "index.php?page=ubah_kendaraan=" + data;
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
                    <h3 class="card-title">Ubah Data Kendaraan</h3>
                    <form action="" method="POST">
                        <h5 class="card-title mt-4">Nama Pemilik</h5>
                        <div class="col-sm">
                            <select class="form-control" name="id_penghuni" required>
                                <?php
                                $query = "SELECT p.id_penghuni, u.nama, k.nomor_kamar
                                    FROM penghuni p
                                    INNER JOIN users u ON p.id_users = u.id_users
                                    INNER JOIN kamar k ON p.id_kamar = k.id_kamar";
                                $result = query($query);

                                foreach ($result as $row) :
                                    $selected = $row['id_penghuni'] == $dataKendaraan['id_penghuni'] ? 'selected' : '';
                                ?>
                                    <option value="<?= $row['id_penghuni']; ?>" <?= $selected; ?>><?= $row['nama'] . ' - Kamar ' . $row['nomor_kamar']; ?></option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                        </div>

                        <h5 class="card-title mt-4">Nomor Kendaraan</h5>
                        <div class="col-sm">
                            <input type="text" class="form-control" placeholder="Masukkan Nomor Kendaraan | Contoh: (AB 0000 XXX)" name="nomor_kendaraan" value="<?= $dataKendaraan['nomor_kendaraan']; ?>" required>
                        </div>

                        <h5 class="card-title mt-4">Jenis Kendaraan</h5>
                        <div class="col-sm">
                            <select class="select2 form-select shadow-none" style="width: 100%; height:36px;" id="jenis_kendaraan" name="jenis_kendaraan" required>
                                <option value="">Pilih Jenis Kendaraan</option>
                                <option value="Motor" <?= $dataKendaraan['jenis_kendaraan'] == 'Motor' ? 'selected' : ''; ?>>Motor</option>
                                <option value="Mobil" <?= $dataKendaraan['jenis_kendaraan'] == 'Mobil' ? 'selected' : ''; ?>>Mobil</option>
                                <option value="Sepeda" <?= $dataKendaraan['jenis_kendaraan'] == 'Sepeda' ? 'selected' : ''; ?>>Sepeda</option>
                                <option value="Motor Listrik" <?= $dataKendaraan['jenis_kendaraan'] == 'Motor Listrik' ? 'selected' : ''; ?>>Motor Listrik</option>
                                <option value="Mobil Listrik" <?= $dataKendaraan['jenis_kendaraan'] == 'Mobil Listrik' ? 'selected' : ''; ?>>Mobil Listrik</option>
                            </select>
                        </div>

                        <h5 class="card-title mt-4">Model Kendaraan</h5>
                        <div class="col-sm">
                            <input type="text" class="form-control" placeholder="Masukkan Model Kendaraan" name="model_kendaraan" value="<?= $dataKendaraan['model_kendaraan']; ?>">
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