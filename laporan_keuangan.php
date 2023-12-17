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

// query untuk mengambil total pemasukan dan pengeluaran dari database
$total_pemasukan = query("SELECT SUM(jumlah_bayar_tagihan) as total_pemasukan FROM tagihan WHERE status_tagihan = 'Lunas' OR status_tagihan = 'Belum Lunas'");
$total_pengeluaran = query("SELECT SUM(nominal_pengeluaran) as total_pengeluaran FROM pengeluaran");

?>

<div class="container-fluid">
    <div class="card-group">
        <div class="card border-right">
            <div class="card-body">
                <div class="d-flex d-lg-flex d-md-block align-items-center">
                    <div>
                        <div class="d-inline-flex align-items-center">
                            <h3 class="text-dark mb-1 font-weight-medium">Rp. <?= number_format($total_pemasukan[0]['total_pemasukan'], 0, ',', '.'); ?></h3>
                        </div>
                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Pemasukan</h6>
                    </div>
                    <div class="ml-auto mt-md-3 mt-lg-0">
                        <span><i class="fa fa-arrow-down"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card border-right">
            <div class="card-body">
                <div class="d-flex d-lg-flex d-md-block align-items-center">
                    <div>
                        <div class="d-inline-flex align-items-center">
                            <h3 class="text-dark mb-1 font-weight-medium">Rp. <?= number_format($total_pengeluaran[0]['total_pengeluaran'], 0, ',', '.'); ?></h3>
                        </div>
                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Pengeluaran</h6>
                    </div>
                    <div class="ml-auto mt-md-3 mt-lg-0">
                        <span><i class="fa fa-arrow-up"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <div class="d-flex flex-column flex-md-row justify-content-center">
                            <a href="index.php?page=daftar_pemasukan" class="btn btn-primary mb-2 mb-md-0 mr-md-2">Daftar Pemasukan</a>
                            <a href="index.php?page=daftar_pengeluaran" class="btn btn-primary mb-2 mb-md-0 mr-md-2">Daftar Pengeluaran</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex align-items-center mb-4">
                        <h3 class="card-title">Riwayat Pemasukan</h3>
                        <div class="ml-auto">

                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered display no-wrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th class="text-center">Tanggal & No. Tagihan</th>
                                    <th class="text-center">Kategori & Deskripsi Tagihan</th>
                                    <th class="text-center">Penghuni</th>
                                    <th class="text-center">Jumlah Pembayaran <div class="dropdown-divider"></div>Jumlah Tagihan</th>
                                    <th class="text-center">Status Tagihan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $tagihan = query("SELECT t.*, u.nama AS nama_penghuni, k.nomor_kamar, u.kontak AS nomorWhatsApp
                                    FROM tagihan t
                                    JOIN penghuni p ON t.id_penghuni = p.id_penghuni
                                    JOIN users u ON p.id_users = u.id_users
                                    JOIN kamar k ON p.id_kamar = k.id_kamar
                                    WHERE status_tagihan = 'Lunas' OR status_tagihan = 'Belum Lunas' 
                                    ORDER BY t.tanggal_tagihan DESC LIMIT 3");

                                $i = 1;
                                foreach ($tagihan as $t) :
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $i; ?>
                                            <div class="dropdown-divider"></div>
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#detailModal<?= $t['id_tagihan']; ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>

                                        <td class="text-center">
                                            <?php
                                            $tanggal_tagihan = strtotime($t['tanggal_tagihan']);
                                            $tanggal_tagihanFormatted = date('d-m-Y', $tanggal_tagihan);
                                            echo $tanggal_tagihanFormatted;
                                            ?>
                                            <div class="dropdown-divider"></div>
                                            <h6><?= $t['no_tagihan']; ?></h6>
                                        </td>

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
                                            <div class="dropdown-divider"></div>
                                            <h6><?= $t['deskripsi_tagihan']; ?></h6>
                                        </td>

                                        <td class="text-center"><?= $t['nama_penghuni']; ?> <br>
                                            <div class="dropdown-divider"></div>
                                            <h6>Kamar <?= $t['nomor_kamar']; ?></h6>
                                        </td>

                                        <td class="text-center">Rp. <?= number_format($t['jumlah_bayar_tagihan'], 0, ',', '.'); ?><br>
                                            <div class="dropdown-divider"></div>
                                            <h6>Rp. <?= number_format($t['jumlah_tagihan'], 0, ',', '.'); ?></h6>
                                        </td>

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
                                <?php $i++;
                                endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex align-items-center mb-4">
                        <h3 class="card-title">Riwayat Pengeluaran</h3>
                        <div class="ml-auto">

                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered display no-wrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th class="text-center" width="15%">Tanggal</th>
                                    <th class="text-center">Keterangan Pengeluaran</th>
                                    <th class="text-center">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $pengeluaran = query("SELECT * FROM pengeluaran ORDER BY id_pengeluaran DESC LIMIT 3");

                                $i = 1;
                                foreach ($pengeluaran as $p) :
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $i; ?></td>
                                        <td class="text-center">
                                            <?php
                                            $tanggal_pengeluaran = strtotime($p['tanggal_pengeluaran']);
                                            $tanggal_pengeluaranFormatted = date('d-m-Y', $tanggal_pengeluaran);
                                            echo $tanggal_pengeluaranFormatted;
                                            ?>
                                        </td>
                                        <td><?= $p['keterangan_pengeluaran']; ?></td>
                                        <td class="text-center">Rp. <?= number_format($p['nominal_pengeluaran'], 0, ',', '.'); ?></td>
                                    </tr>
                                <?php $i++;
                                endforeach; ?>
                            </tbody>
                        </table>
                    </div>


                </div>

            </div>

        </div>

    </div>
    <?php
    foreach ($tagihan as $t) :
    ?>
        <div class="modal fade" id="detailModal<?= $t['id_tagihan']; ?>" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel<?= $t['id_tagihan']; ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel<?= $t['id_tagihan']; ?>">Detail Data Tagihan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="zero_config" class="table table-striped table-bordered no-wrap">
                                <tr>
                                    <th>Nomor Tagihan</th>
                                    <td><?= $t['no_tagihan']; ?></td>
                                </tr>
                                <tr>
                                    <th>Tanggal Tagihan</th>
                                    <td> <?php
                                            $tanggal_tagihan = strtotime($t['tanggal_tagihan']);
                                            $tanggal_tagihanFormatted = date('d-m-Y', $tanggal_tagihan);
                                            echo $tanggal_tagihanFormatted;
                                            ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Kategori Tagihan</th>
                                    <td>
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
                                </tr>
                                <tr>
                                    <th>Deskripsi Tagihan</th>
                                    <td><?= $t['deskripsi_tagihan']; ?></td>
                                </tr>
                                <tr>
                                    <th>Nama Penghuni</th>
                                    <td><?= $t['nama_penghuni'] . " - (" . $t['status_penghuni'] . ")"; ?></td>
                                </tr>

                                <tr>
                                    <th>Nomor Kamar</th>
                                    <td><?= $t['nomor_kamar']; ?></td>
                                </tr>
                                <tr>
                                    <th>Total Tagihan</th>
                                    <td>Rp. <?= number_format($t['jumlah_tagihan'], 0, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Jumlah Tagihan Dibayar</th>
                                    <td>Rp. <?= number_format($t['jumlah_bayar_tagihan'], 0, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Jumlah Sisa Tagihan</th>
                                    <td>Rp. <?= number_format($t['jumlah_sisa_tagihan'], 0, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status Tagihan</th>
                                    <td>
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
                                <tr>
                                    <th>Keterangan Tagihan</th>
                                    <td><?= $t['keterangan_tagihan']; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    <?php
    endforeach;
    ?>

</div>