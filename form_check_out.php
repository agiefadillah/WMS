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
    $result = WMSCheckOut($_POST);
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
                    window.location.href = "index.php?page=check_out";
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
$ci = query("SELECT k.*, u.nama AS nama_penghuni, p.*
             FROM kamar k
             INNER JOIN penghuni p ON k.id_kamar = p.id_kamar
             INNER JOIN users u ON p.id_users = u.id_users
            --  INNER JOIN tagihan t ON p.id_penghuni = t.id_penghuni
             WHERE k.id_kamar='$id_kamar'")[0];

?>

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title text-truncate text-dark font-weight-medium mb-1">Check-Out</h3>
                    <h5 class="card-title text-truncate text-dark font-weight-medium mb-1">Kamar <?php echo $ci["nomor_kamar"] ?></h5>
                    <h6><?= 'Rp. ' . number_format($ci['harga_kamar']) ?></h6>

                    <form action="" method="POST">
                        <input type="hidden" name="id_kamar" value="<?= $ci['id_kamar']; ?>">
                        <input type="hidden" name="id_penghuni" value="<?= $ci['id_penghuni']; ?>">

                        <h5 class="card-title mt-4">Nama Penghuni</h5>
                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="nama_penghuni" value="<?= $ci['nama_penghuni']; ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <h5 class="card-title mt-1">Tanggal Masuk | Check-In<span id="wajibdiisi">*</span></h5>
                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <input type="date" class="form-control" name="tanggal_masuk" value="<?= $ci['tanggal_masuk_penghuni']; ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <h5 class="card-title mt-1">Tanggal Keluar | Check-Out<span id="wajibdiisi">*</span></h5>
                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <input type="date" class="form-control" name="tanggal_checkout" value="<?= $ci['tanggal_keluar_penghuni']; ?>" required>
                                </div>
                            </div>
                        </div>

                        <h5 class="card-title mt-1">Tagihan</h5>
                        <div class="col-sm">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-center" scope="col">Nomor</th>
                                            <th class="text-center" scope="col">Tanggal</th>
                                            <th class="text-center" scope="col">Kategori</th>
                                            <th class="text-center" scope="col">Jumlah</th>
                                            <th class="text-center" scope="col">Keterangan</th>
                                            <th class="text-center" scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $tagihan = query("SELECT * FROM tagihan WHERE id_penghuni = '" . $ci['id_penghuni'] . "' ORDER BY 
                                        CASE 
                                            WHEN status_tagihan = 'Belum Bayar' THEN 1 
                                            WHEN status_tagihan = 'Belum Lunas' THEN 2 
                                            ELSE 3 
                                        END, tanggal_tagihan ASC");
                                        foreach ($tagihan as $t) :
                                        ?>
                                            <tr>
                                                <td class="text-center"><?= $t['no_tagihan'] ?></td>
                                                <td class="text-center">
                                                    <?php
                                                    $tanggal_tagihan = strtotime($t['tanggal_tagihan']);
                                                    $tanggal_tagihanFormatted = date('d-m-Y', $tanggal_tagihan);
                                                    echo $tanggal_tagihanFormatted;
                                                    ?></td>
                                                <td class="text-center">
                                                    <?php
                                                    $kettag = $t['kategori_tagihan'];
                                                    if ($kettag == 'sewa_kamar') {
                                                        echo '<span class="badge badge-dark">Tagihan Sewa Kamar</span>';
                                                    } else if ($kettag == 'sewa_tamu') {
                                                        echo '<span class="badge badge-secondary">Tagihan Tamu</span>';
                                                    } else if ($kettag == 'sewa_kendaraan') {
                                                        echo '<span class="badge alert-warning">Tagihan Kendaraan</span>';
                                                    } else if ($kettag == 'sewa_lainnya') {
                                                        echo '<span class="badge alert-light">Tagihan Lainnya</span>';
                                                    } else if ($kettag == 'Tanda Jadi') {
                                                        echo '<span class="badge alert-info">Tagihan Tanda Jadi</span>';
                                                    } else {
                                                        echo '<span class="badge badge-danger">ERROR</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-center">Rp. <?= number_format($t['jumlah_tagihan'], 0, ',', '.') ?></td>
                                                <td class="text-center"><?= $t['keterangan_tagihan'] ?></td>
                                                <td class="text-center">
                                                    <?php
                                                    $status_tagihan = $t['status_tagihan'];
                                                    if ($status_tagihan == 'Lunas') {
                                                        echo '<span class="badge badge-success">Lunas</span>';
                                                    } else if ($status_tagihan == 'Belum Lunas') {
                                                        echo '<span class="badge badge-warning">Belum Lunas</span>';
                                                    } else if ($status_tagihan == 'Belum Bayar') {
                                                        echo '<span class="badge badge-danger">Belum Bayar</span>';
                                                    } else {
                                                        echo '<span class="badge badge-danger">ERROR</span>';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php
                                        endforeach;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <h4 class="card-title mt-1">Catatan</h4>

                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <textarea class="form-control" rows="2" name="keterangan_checkout" id="keterangan_checkout"></textarea>
                                </div>
                            </div>
                        </div>



                        <div class="col-sm mt-4">
                            <input type="submit" name="simpan" class="btn btn-success" value="Simpan">
                            <a href="index.php?page=check_out" class="btn btn-primary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

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