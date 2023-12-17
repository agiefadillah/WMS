<?php
// Memeriksa apakah pengguna memiliki peran pemilik atau penjaga
if ($_SESSION['tipe'] != 'penghuni') {
    echo "<script>
        alert('Anda tidak memiliki akses ke halaman ini!');
        window.location.href = 'index.php'; // Ganti dengan halaman tujuan jika pengguna tidak memiliki akses
    </script>";
    exit;
}

?>

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <div class="d-flex flex-column flex-md-row justify-content-center">
                            <a href="index.php?page=bayar_tagihan_penghuni" class="btn btn-primary mb-2 mb-md-0 mr-md-2">Bayar Tagihan</a>
                            <a href="index.php?page=daftar_pembayaran_penghuni" class="btn btn-primary mb-2 mb-md-0 mr-md-2">Riwayat Pembayaran Tagihan</a>
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
                        <h3 class="card-title">Data Tagihan <?php echo $_SESSION["nama"] ?></h3>
                    </div>

                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered display no-wrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th class="text-center">Tanggal dan No. Tagihan</th>
                                    <th class="text-center">Kategori dan Deskripsi Tagihan</th>
                                    <th class="text-center">Jumlah Tagihan</th>
                                    <th class="text-center">Status Tagihan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $tagihan = query("SELECT t.id_tagihan, u.nama AS nama_penghuni, k.nomor_kamar,
                                t.no_tagihan, t.kategori_tagihan, t.deskripsi_tagihan, t.jumlah_tagihan, t.tanggal_tagihan,
                                t.status_tagihan, t.keterangan_tagihan, t.jumlah_bayar_tagihan, t.jumlah_sisa_tagihan
                                FROM tagihan t
                                JOIN penghuni p ON t.id_penghuni = p.id_penghuni
                                JOIN users u ON p.id_users = u.id_users
                                JOIN kamar k ON p.id_kamar = k.id_kamar
                                WHERE p.id_penghuni = '" . $_SESSION['id_penghuni'] . "'");


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

                                        <td class="text-center">Rp. <?= number_format($t['jumlah_tagihan'], 0, ',', '.'); ?></td>

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
                                            ?></td>
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
                        <h3 class="card-title">Data Pembayaran Tagihan</h3>
                    </div>
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered display no-wrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th class="text-center" width="30%">Bukti Bayar</th>
                                    <th class="text-center">Tanggal Bayar</th>
                                    <th class="text-center">Jumlah Bayar</th>
                                    <th class="text-center">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $id_penghuni = $_SESSION['id_penghuni'];
                                $query = "SELECT * FROM pembayaran WHERE id_penghuni = '$id_penghuni'";
                                $result = mysqli_query($koneksi, $query);
                                $no = 1;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $tanggal_bayar = date('d-m-Y', strtotime($row['tanggal_pembayaran']));
                                    $jumlah_bayar = $row['jumlah_pembayaran'];
                                    $keterangan = $row['keterangan_pembayaran'];
                                    $bukti_bayar = $row['bukti_pembayaran'];
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $no++; ?></td>
                                        <td class="text-center"><img src="<?php echo $bukti_bayar; ?>" alt="Bukti Bayar" style="max-width: 100px; max-height: 100px;"></td>
                                        <td class="text-center"><?php echo $tanggal_bayar; ?></td>
                                        <td class="text-center"><?php echo $jumlah_bayar; ?></td>
                                        <td class="text-center"><?php echo $keterangan; ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
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
                                </tr>
                                <th>Nama Penghuni</th>
                                <td><?= $t['nama_penghuni']; ?></td>
                                </tr>
                                <tr>
                                    <th>Nomor Kamar</th>
                                    <td><?= $t['nomor_kamar']; ?></td>
                                </tr>
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
                                <tr>
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
                                        ?></td>
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