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

                    <div class="d-flex align-items-center mb-4">
                        <h3 class="card-title">Kamar</h3>
                    </div>

                    <div class="d-flex flex-column flex-md-row align-items-center justify-content-center mb-4">
                        <button id="print-button" class="btn btn-primary mb-2 mb-md-0 mr-md-2" onclick="cetakData()">Cetak</button>
                        <a href="index.php?page=tambah_kamar" class="btn btn-primary mb-2 mb-md-0 mr-md-2">Tambah Kamar</a>
                        <a href="index.php?page=lokasi_kamar" class="btn btn-primary mb-2 mb-md-0 mr-md-2">Tambah Lokasi</a>
                    </div>

                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered display no-wrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th class="text-center">Lokasi</th>
                                    <th class="text-center">Nomor Kamar</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Status Kamar</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $kamar = query("SELECT k.*, l.nama_lokasi FROM kamar k JOIN lokasi_kamar l ON k.id_lokasi = l.id_lokasi ORDER BY k.nomor_kamar ASC");
                                $i = 1;
                                foreach ($kamar as $k) :
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $i; ?></td>
                                        <td class="text-center"><?= $k['nama_lokasi']; ?></td>
                                        <td class="text-center"><?= $k['nomor_kamar']; ?></td>
                                        <td class="text-center"><?= 'Rp. ' . number_format($k['harga_kamar']) ?></td>
                                        <td class="text-center">
                                            <?php
                                            $stat = $k['status_kamar'];
                                            if ($stat == 'Tersedia') {
                                                echo '<span class="badge badge-success">Tersedia</span>';
                                            } else if ($stat == 'Renovasi') {
                                                echo '<span class="badge badge-danger">Renovasi</span>';
                                            } else if ($stat == 'Perbaikan') {
                                                echo '<span class="badge badge-warning">Perbaikan</span>';
                                            } else if ($stat == 'Terisi') {
                                                echo '<span class="badge badge-dark">Terisi</span>';
                                            } else {
                                                echo '<span class="badge badge-danger">Error</span>';
                                            }
                                            ?>
                                        </td>
                                        <td align="center">
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#detailModal<?= $k['id_kamar']; ?>">
                                                <i class="fas fa-eye"></i>
                                                Detail
                                            </button>
                                            <button type="button" class="btn btn-warning" onclick="ubahkamar('<?= $k['id_kamar']; ?>')">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-danger" onclick="hapuskamar('<?= $k['id_kamar']; ?>')">
                                                <i class="fas fa-trash"></i>
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                <?php $i++;
                                endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php
                    foreach ($kamar as $k) :
                    ?>
                        <div class="modal fade" id="detailModal<?= $k['id_kamar']; ?>" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel<?= $k['id_kamar']; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="detailModalLabel<?= $k['id_kamar']; ?>">Detail Data Kamar</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>Nomor Kamar</th>
                                                    <td><?= $k['nomor_kamar']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Lokasi Kamar</th>
                                                    <td><?= $k['nama_lokasi']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Harga Kamar</th>
                                                    <td><?= 'Rp. ' . number_format($k['harga_kamar']) ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Status Kamar</th>
                                                    <td>
                                                        <?php
                                                        $stat = $k['status_kamar'];
                                                        if ($stat == 'Tersedia') {
                                                            echo '<span class="badge badge-success">Tersedia</span>';
                                                        } else if ($stat == 'Renovasi') {
                                                            echo '<span class="badge badge-danger">Renovasi</span>';
                                                        } else if ($stat == 'Perbaikan') {
                                                            echo '<span class="badge badge-warning">Perbaikan</span>';
                                                        } else if ($stat == 'Terisi') {
                                                            echo '<span class="badge badge-dark">Terisi</span>';
                                                        } else {
                                                            echo '<span class="badge badge-danger">Error</span>';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Nama Penghuni</th>
                                                    <td></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-warning" onclick="ubahlokasikamar('<?= $lk['id_lokasi']; ?>')">
                                            <i class="fas fa-edit"></i>
                                            Edit
                                        </button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    endforeach;
                    ?>

                </div>
            </div>
        </div>
    </div>

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
                    padding: 5px;
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
        printContent += '<h3>Kamar</h3>';
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