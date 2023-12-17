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
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <div class="d-flex flex-column flex-md-row justify-content-center">
                            <button id="print-button" class="btn btn-primary mb-2 mb-md-0 mr-md-2">Cetak</button>
                            <a href="index.php?page=laporan_keuangan" class="btn btn-primary mb-2 mb-md-0 mr-md-2">Laporan Keuangan</a>
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
                        <h3 class="card-title">Data Pemasukan</h3>
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
                                    ORDER BY t.tanggal_tagihan DESC");

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

<script>
    // Mengambil referensi tombol cetak
    var printButton = document.getElementById('print-button');

    // Menambahkan event listener untuk klik pada tombol cetak
    printButton.addEventListener('click', function() {
        // Mengambil referensi tabel
        var table = document.getElementById('zero_config');

        // Membuka jendela cetak baru
        var printWindow = window.open('', '_blank');

        // Membuat CSS untuk tampilan cetak
        var css = `
        <style>
            table {
                border-collapse: collapse;
            }

            table, th, td {
                border: 1px solid black;
                padding: 15px;
                text-align: center;
            }

            h3 {
                padding-top: 15px;
                text-align: center;
            }

            .logo {
                position: absolute;
                top: 10px;
                right: 10px;
                width: 8%;
            }
        </style>
    `;

        // Membuat HTML baru dengan judul, CSS, dan isi tabel
        var printContent = '<html><head><title>Wisma Mutiara Selaras</title>';
        printContent += css;
        printContent += '</head><body>';
        printContent += '<img class="logo" src="img/logo1.png">';
        printContent += '<h3>Data Pemasukan</h3>';
        printContent += table.outerHTML;
        printContent += '</body></html>';

        // Menulis HTML ke dalam jendela cetak
        printWindow.document.open();
        printWindow.document.write(printContent);
        printWindow.document.close();

        // Mencetak jendela cetak
        printWindow.print();
    });
</script>