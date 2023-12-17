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
                            <a href="index.php?page=tambah_tamu" class="btn btn-primary mb-2 mb-md-0 mr-md-2">Tambah Tamu Menginap</a>
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
                        <h3 class="card-title">Data Tamu Menginap</h3>
                    </div>

                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered display no-wrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th class="text-center">Tanggal Masuk Tamu</th>
                                    <th class="text-center">Nama Tamu</th>
                                    <th class="text-center">Nama Penghuni</th>
                                    <th class="text-center">Waktu Menginap Tamu</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $tamu = query("SELECT t.id_tamu, t.tanggal_tamu, t.nama_tamu, t.waktu_menginap_tamu, 
                                t.identitas_tamu, t.tarif_tamu,  u.nama AS nama_penghuni, k.nomor_kamar
                                FROM tamu t
                                INNER JOIN penghuni p ON t.id_penghuni = p.id_penghuni
                                INNER JOIN users u ON p.id_users = u.id_users
                                INNER JOIN kamar k ON p.id_kamar = k.id_kamar");

                                $i = 1;
                                foreach ($tamu as $t) :
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $i; ?></td>
                                        <td class="text-center">
                                            <?php
                                            $tanggal_tamu = strtotime($t['tanggal_tamu']);
                                            $tanggal_tamuFormatted = date('d-m-Y H:i', $tanggal_tamu);
                                            echo $tanggal_tamuFormatted;
                                            ?>
                                        </td>
                                        <td class="text-center"><?= $t['nama_tamu']; ?></td>
                                        <td class="text-center"><?= $t['nama_penghuni']; ?> <br>
                                            <h6 class="text-muted">Kamar <?= $t['nomor_kamar']; ?></h6>
                                        </td>
                                        <td class="text-center"><?= $t['waktu_menginap_tamu']; ?> Hari</td>

                                        <td align="center">
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#detailModal<?= $t['id_tamu']; ?>">
                                                <i class="fas fa-eye"></i>
                                                Detail
                                            </button>
                                            <button type="button" class="btn btn-warning" onclick="ubahtamu('<?= $t['id_tamu']; ?>')">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </button>

                                            <button type="button" class="btn btn-danger" onclick="hapustamu('<?= $t['id_tamu']; ?>')">
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

                </div>
            </div>
        </div>
    </div>

    <?php
    foreach ($tamu as $t) :
    ?>
        <div class="modal fade" id="detailModal<?= $t['id_tamu']; ?>" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel<?= $t['id_tamu']; ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel<?= $t['id_tamu']; ?>">Detail Data Tamu</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="zero_config" class="table table-striped table-bordered no-wrap">
                                <tr>
                                    <th>Nama Tamu</th>
                                    <td><?= $t['nama_tamu']; ?></td>
                                </tr>
                                <tr>
                                    <th>Nama Penghuni</th>
                                    <td><?= $t['nama_penghuni']; ?></td>
                                </tr>
                                <tr>
                                    <th>Nomor Kamar</th>
                                    <td><?= $t['nomor_kamar']; ?></td>
                                </tr>
                                <tr>
                                    <th>Tanggal Masuk Tamu</th>
                                    <td> <?php
                                            $tanggal_tamu = strtotime($t['tanggal_tamu']);
                                            $tanggal_tamuFormatted = date('d-m-Y H:i', $tanggal_tamu);
                                            echo $tanggal_tamuFormatted;
                                            ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Waktu Menginap Tamu</th>
                                    <td><?= $t['waktu_menginap_tamu']; ?> Hari</td>
                                </tr>
                                <tr>
                                    <th>Tarif Tamu</th>
                                    <td><?= 'Rp. ' . number_format($t['tarif_tamu']) ?></td>
                                </tr>
                                <tr>
                                    <th>Identitas Tamu</th>
                                    <td><img src="<?php echo $t['identitas_tamu'] ?>" width="75%">
                                        <br><a href="<?php echo $t['identitas_tamu'] ?>">Lihat Identitas Tamu</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" onclick="ubahpenghuni('<?= $t['id_tamu']; ?>')">
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
        var printContent = '<html><head><title>Cetak Data Tamu</title>';
        printContent += css;
        printContent += '</head><body>';
        printContent += '<img class="logo" src="img/logo1.png">';
        printContent += '<h3>Data Tamu</h3>';
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