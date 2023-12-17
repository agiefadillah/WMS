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
    $result = tambahLokasiKamar($_POST);
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
                    window.location.href = "index.php?page=daftar_lokasi_kamar";
                });
            </script>';
    } else if ($result == 0) {
        echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
            <script>
                Swal.fire({
                    icon: "warning",
                    title: "Lokasi Kamar Sudah Ada",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function () {
                    window.location.href = "index.php?page=daftar_lokasi_kamar";
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
                    window.location.href = "index.php?page=daftar_lokasi_kamar";
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
                    <h3 class="card-title">Tambah Lokasi Kamar</h3>
                    <form action="" method="POST">
                        <h5 class="card-title mt-4">Nama Lokasi</h5>
                        <div class="col-sm">
                            <input type="text" class="form-control" placeholder="Masukkan Nama Lokasi" name="nama_lokasi" required>
                        </div>
                        <div class="col-sm mt-4">
                            <input type="submit" name="simpan" class="btn btn-success" value="Simpan">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <h3 class="card-title">Lokasi Kamar</h3>
                    </div>
                    <div class="d-flex flex-column flex-md-row align-items-center justify-content-center mb-4">
                        <button id="print-button" class="btn btn-primary mb-2 mb-md-0 mr-md-2" onclick="cetakData()">Cetak</button>
                    </div>
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered no-wrap">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th class="text-center">Nama Lokasi</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $lokasi_kamar = query("SELECT * FROM lokasi_kamar ");
                                $i = 1;
                                foreach ($lokasi_kamar as $lk) :
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $i; ?></td>
                                        <td><?= tampildata($lk['nama_lokasi']); ?></td>
                                        <td align="center">
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#detailModal<?= $lk['id_lokasi']; ?>">
                                                <i class="fas fa-eye"></i>
                                                Detail
                                            </button>
                                            <button type="button" class="btn btn-warning" onclick="ubahlokasikamar('<?= $lk['id_lokasi']; ?>')">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-danger" onclick="hapuslokasikamar('<?= $lk['id_lokasi']; ?>')">
                                                <i class="fas fa-trash"></i>
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                <?php
                                    $i++;
                                endforeach;
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                    foreach ($lokasi_kamar as $lk) :
                    ?>
                        <div class="modal fade" id="detailModal<?= $lk['id_lokasi']; ?>" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel<?= $lk['id_lokasi']; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="detailModalLabel<?= $lk['id_lokasi']; ?>">Detail Data Lokasi</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered no-wrap">
                                                <tr>
                                                    <th>Jumlah Kamar <?= $lk['nama_lokasi']; ?></th>
                                                    <td>
                                                        <?php
                                                        // Query untuk mengambil jumlah kamar berdasarkan id_lokasi
                                                        $jumlah_kamar = query("SELECT COUNT(*) AS jumlah FROM kamar WHERE id_lokasi = '" . $lk['id_lokasi'] . "'")[0]['jumlah'];
                                                        echo $jumlah_kamar;
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Kamar</th>
                                                    <td>
                                                        <?php
                                                        // Query untuk mengambil daftar nomor kamar berdasarkan id_lokasi
                                                        $daftar_kamar = query("SELECT nomor_kamar FROM kamar WHERE id_lokasi = '" . $lk['id_lokasi'] . "'");
                                                        $i = 1;
                                                        foreach ($daftar_kamar as $kamar) {
                                                            echo $i . ") " . $kamar['nomor_kamar'] . "<br>";
                                                            $i++;
                                                        }
                                                        ?>
                                                    </td>
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
        printContent += '<h3>Lokasi Kamar</h3>';
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