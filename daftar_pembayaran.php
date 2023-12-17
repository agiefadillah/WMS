<div class="container-fluid">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="d-flex flex-column flex-md-row justify-content-center">
                                <button id="print-button" class="btn btn-primary mb-2 mb-md-0 mr-md-2">Cetak</button>
                                <!-- <a href="index.php?page=bayar_tagihan_penghuni" class="btn btn-primary mb-2 mb-md-0 mr-md-2">Bayar Tagihan</a> -->
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
                            <h3 class="card-title">Data Pembayaran</h3>
                        </div>
                        <div class="table-responsive">
                            <table id="zero_config" class="table table-striped table-bordered display no-wrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="5%">No</th>
                                        <th class="text-center">Tanggal Bayar & Nama</th>
                                        <th class="text-center">Bukti Bayar</th>
                                        <th class="text-center">Jumlah Pembayaran</th>
                                        <th class="text-center">Tanggal & No. Tagihan</th>
                                        <th class="text-center">Kategori dan Deskripsi Tagihan</th>
                                        <th class="text-center">Jumlah Tagihan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $pembayaran = query("SELECT pb.id_pembayaran, pb.tanggal_pembayaran, pb.bukti_pembayaran, pb.jumlah_pembayaran,
                                    t.*, p.id_penghuni, p.id_kamar, u.*, ka.*
                                    FROM pembayaran pb
                                    INNER JOIN pembayaran_tagihan pt ON pb.id_pembayaran = pt.id_pembayaran
                                    INNER JOIN tagihan t ON pt.id_tagihan = t.id_tagihan
                                    
                                    INNER JOIN penghuni p ON pb.id_penghuni = p.id_penghuni
                                    INNER JOIN users u ON p.id_users = u.id_users
                                    INNER JOIN kamar ka ON p.id_kamar = ka.id_kamar ");

                                    $i = 1;
                                    foreach ($pembayaran as $p) :
                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $i; ?>
                                                <div class="dropdown-divider"></div>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#detailModal<?= $p['id_pembayaran']; ?>">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                $tanggal_pembayaran = strtotime($p['tanggal_pembayaran']);
                                                $tanggal_pembayaranFormatted = date('d-m-Y', $tanggal_pembayaran);
                                                echo $tanggal_pembayaranFormatted;
                                                ?>
                                                <div class="dropdown-divider"></div>
                                                <?= $p['nama']; ?> <br>
                                                <h6 class="text-muted">Kamar <?= $p['nomor_kamar']; ?></h6>
                                            </td>
                                            <td class="text-center"><img src="<?php echo $p['bukti_pembayaran'] ?>" width="50%">
                                                <br><a href="<?php echo $p['bukti_pembayaran'] ?>">Lihat</a>
                                            </td>
                                            <td class="text-center">Rp. <?= number_format($p['jumlah_pembayaran'], 0, ',', '.'); ?></td>
                                            <td class="text-center">
                                                <?php
                                                $tanggal_tagihan = strtotime($p['tanggal_tagihan']);
                                                $tanggal_tagihanFormatted = date('d-m-Y', $tanggal_tagihan);
                                                echo $tanggal_tagihanFormatted;
                                                ?>
                                                <div class="dropdown-divider"></div>
                                                <h6><?= $p['no_tagihan']; ?></h6>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                $kettag = $p['kategori_tagihan'];
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
                                                <h6><?= $p['deskripsi_tagihan']; ?></h6>
                                            </td>
                                            <td class="text-center">Rp. <?= number_format($p['jumlah_tagihan'], 0, ',', '.'); ?></td>
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
    </div>

    <?php
    foreach ($pembayaran as $p) :
    ?>
        <div class="modal fade" id="detailModal<?= $p['id_pembayaran']; ?>" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel<?= $p['id_pembayaran']; ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel<?= $p['id_pembayaran']; ?>">Detail Data Pembayaran</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="zero_config" class="table table-striped table-bordered no-wrap">
                                <tr>
                                    <th>Nama Penghuni</th>
                                    <td><?= $p['nama']; ?></td>
                                </tr>
                                <tr>
                                    <th>Kamar</th>
                                    <td>Kamar <?= $p['nomor_kamar']; ?></td>
                                </tr>
                                <tr>
                                    <th>Tanggal Bayar</th>
                                    <td>
                                        <?php
                                        $tanggal_pembayaran = strtotime($p['tanggal_pembayaran']);
                                        $tanggal_pembayaranFormatted = date('d-m-Y', $tanggal_pembayaran);
                                        echo $tanggal_pembayaranFormatted;
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Identitas Penghuni</th>
                                    <td><img src="<?php echo $p['bukti_pembayaran'] ?>" width="75%">
                                        <br><a href="<?php echo $p['bukti_pembayaran'] ?>">Lihat</a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Jumlah Pembayaran</th>
                                    <td>Rp. <?= number_format($p['jumlah_pembayaran'], 0, ',', '.'); ?></td>
                                </tr>
                                <tr>
                                    <th>Tanggal Tagihan</th>
                                    <td> <?php
                                            $tanggal_tagihan = strtotime($p['tanggal_tagihan']);
                                            $tanggal_tagihanFormatted = date('d-m-Y', $tanggal_tagihan);
                                            echo $tanggal_tagihanFormatted;
                                            ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Nomor Tagihan</th>
                                    <td><?= $p['no_tagihan']; ?></td>
                                </tr>
                                <tr>
                                    <th>Kategori Tagihan</th>
                                    <td>
                                        <?php
                                        $kettag = $p['kategori_tagihan'];
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
                                    <td><?= $p['deskripsi_tagihan']; ?></td>
                                </tr>
                                <tr>
                                    <th>Total Tagihan</th>
                                    <td>Rp. <?= number_format($p['jumlah_tagihan'], 0, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Jumlah Tagihan Dibayar</th>
                                    <td>Rp. <?= number_format($p['jumlah_bayar_tagihan'], 0, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Jumlah Sisa Tagihan</th>
                                    <td>Rp. <?= number_format($p['jumlah_sisa_tagihan'], 0, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status Tagihan</th>
                                    <td>
                                        <?php
                                        $status_tagihan = $p['status_tagihan'];
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
                                    <td><?= $p['keterangan_tagihan']; ?></td>
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