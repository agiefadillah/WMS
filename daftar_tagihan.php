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
                            <a href="index.php?page=tambah_tagihan" class="btn btn-primary mb-2 mb-md-0 mr-md-2">Buat Tagihan</a>
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
                        <h3 class="card-title">Data Tagihan</h3>
                    </div>

                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered display no-wrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th class="text-center">Tanggal dan No. Tagihan</th>
                                    <th class="text-center">Kategori dan Deskripsi Tagihan</th>
                                    <th class="text-center">Penghuni</th>
                                    <th class="text-center">Jumlah Tagihan</th>
                                    <th class="text-center">Status Tagihan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $tagihan = query("SELECT t.id_tagihan, u.nama AS nama_penghuni, k.nomor_kamar, u.kontak AS nomorWhatsApp,
                                    t.no_tagihan, t.kategori_tagihan, t.deskripsi_tagihan, t.jumlah_tagihan, t.tanggal_tagihan,
                                    t.status_tagihan, t.keterangan_tagihan, p.status_penghuni, t.jumlah_bayar_tagihan, t.jumlah_sisa_tagihan
                                    FROM tagihan t
                                    JOIN penghuni p ON t.id_penghuni = p.id_penghuni
                                    JOIN users u ON p.id_users = u.id_users
                                    JOIN kamar k ON p.id_kamar = k.id_kamar
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
                        <div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="btn-label"><i class="nav-icon fas fa-eye"></i></span> Aksi
                            </button>
                            <div class="dropdown-menu">
                                <button type="button" class="btn btn-warning dropdown-item" onclick="ubahtagihan('<?= $t['id_tagihan']; ?>', '<?= $t['kategori_tagihan']; ?>')">
                                    <span class="btn-label"><i class="fas fa-edit"></i></span> Edit
                                </button>

                                <button type="button" class="btn btn-danger dropdown-item" onclick="hapustagihan('<?= $t['id_tagihan']; ?>')">
                                    <span class="btn-label"><i class="nav-icon fas fa-trash"></i></span> Hapus
                                </button>

                                <button type="button" class="btn btn-success dropdown-item" onclick="kirimTagihanWhatsApp('<?= $t['nomorWhatsApp']; ?>', {nama_penghuni: '<?= $t['nama_penghuni']; ?>', nomor_kamar: '<?= $t['nomor_kamar']; ?>', nomor_tagihan: '<?= $t['no_tagihan']; ?>', tanggal_tagihan: '<?= $tanggal_tagihanFormatted; ?>', kategori_tagihan: '<?= $t['kategori_tagihan']; ?>', deskripsi_tagihan: '<?= $t['deskripsi_tagihan']; ?>', keterangan_tagihan: '<?= $t['keterangan_tagihan']; ?>', total_tagihan: <?= $t['jumlah_tagihan']; ?>, status_tagihan: '<?= $t['status_tagihan']; ?>'})">
                                    <span class="btn-label"><i class="fa-brands fa-whatsapp"></i></span> Kirim Tagihan ke WhatsApp Penghuni
                                </button>


                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">Ubah Status Tagihan</h6>
                                <div class="dropdown-divider"></div>

                                <button type="button" class="btn btn-dark dropdown-item" onclick="updatestatustagihan('<?= $t['id_tagihan']; ?>', 'Belum Bayar')" data-id="<?= $t['id_tagihan']; ?>">
                                    <span class="btn-label"><i class="fa fa-x"></i></span> Belum Bayar
                                </button>

                                <button type="button" class="btn btn-warning dropdown-item" onclick="statusbelumlunas('<?= $t['id_tagihan']; ?>')">
                                    <span class="btn-label"><i class="fa fa-slash"></i></span> Belum Lunas
                                </button>

                                <button type="button" class="btn btn-success dropdown-item" onclick="updatestatustagihan('<?= $t['id_tagihan']; ?>', 'Lunas', '<?= $t['jumlah_tagihan']; ?>')" data-id="<?= $t['id_tagihan']; ?>">
                                    <span class="btn-label"><i class="nav-icon fas fa-check"></i></span> Lunas
                                </button>
                            </div>
                        </div>

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
    function updatestatustagihan(id_tagihan, status_tagihan, jumlah_tagihan) {
        $.ajax({
            url: "config/status_tagihan.php",
            type: "POST",
            data: {
                id_tagihan: id_tagihan,
                jumlah_tagihan: jumlah_tagihan,
                status_tagihan: status_tagihan
            },
            success: function(data) {
                if (data == "success") {
                    // Reload halaman tagihan jika update sukses
                    location.reload();
                } else {
                    location.reload();
                }
            },
            error: function() {
                location.reload();
            }
        });
    }
</script>

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
        printContent += '<h3>Data Tagihan</h3>';
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

<script>
    function kirimTagihanWhatsApp(nomor_whatsapp, data_tagihan) {
        var nama_penghuni = data_tagihan.nama_penghuni;
        var nomor_kamar = data_tagihan.nomor_kamar;
        var nomor_tagihan = data_tagihan.nomor_tagihan;
        var tanggal_tagihan = data_tagihan.tanggal_tagihan;
        var kategori_tagihan = data_tagihan.kategori_tagihan;
        var deskripsi_tagihan = data_tagihan.deskripsi_tagihan;
        var keterangan_tagihan = data_tagihan.keterangan_tagihan;
        var total_tagihan = data_tagihan.total_tagihan;
        var status_tagihan = data_tagihan.status_tagihan;

        // Mengganti angka 0 dengan 62 pada nomor WhatsApp
        nomor_whatsapp = nomor_whatsapp.replace(/^0/, "62");

        // Mengubah kategori tagihan menjadi teks yang diinginkan
        var kategori_text = getKategoriText(kategori_tagihan);

        // Format total tagihan menjadi format mata uang
        var formatted_total_tagihan = formatCurrency(total_tagihan);

        var formatted_message =
            "Wisma Mutiara Selaras" + "\n" +
            "Jl. Banteng Raya 3 Gg. Sadewa No. 5 Ngabean Kulon, Sinduharjo, Kec. Ngaglik, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55581" + "\n\n" +
            "Halo " + nama_penghuni + " - Kamar " + nomor_kamar + ", Berikut Adalah Rincian Tagihan Anda:\n\n" +
            "Nomor Tagihan: " + nomor_tagihan + "\n" +
            "Tanggal Tagihan: " + tanggal_tagihan + "\n" +
            "Kategori Tagihan: " + kategori_text + "\n" +
            "Deskripsi Tagihan: " + deskripsi_tagihan + "\n" +
            "Keterangan Tagihan: " + keterangan_tagihan + "\n" +
            "Total Tagihan: " + formatted_total_tagihan + "\n" +
            "Status Tagihan: " + status_tagihan;

        var link = "https://wa.me/" + nomor_whatsapp + "?text=" + encodeURIComponent(formatted_message);

        window.open(link);
    }

    // Fungsi untuk mengubah kategori tagihan menjadi teks yang diinginkan
    function getKategoriText(kategori_tagihan) {
        switch (kategori_tagihan) {
            case 'sewa_kamar':
                return 'Tagihan Sewa Kamar';
            case 'sewa_kendaraan':
                return 'Tagihan Kendaraan';
            case 'sewa_lainnya':
                return 'Tagihan Lainnya';
            case 'sewa_tamu':
                return 'Tagihan Tamu';
            case 'Tanda Jadi':
                return 'Tagihan Tanda Jadi';
            default:
                return 'ERROR';
        }
    }

    // Fungsi untuk memformat angka menjadi format mata uang dengan pemisah ribuan dan tanda desimal
    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    }
</script>