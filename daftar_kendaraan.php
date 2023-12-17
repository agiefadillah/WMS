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
                            <a href="index.php?page=tambah_kendaraan" class="btn btn-primary mb-2 mb-md-0 mr-md-2">Tambah Data Kendaraan</a>
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
                        <h3 class="card-title">Data Kendaraan</h3>
                    </div>

                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered display no-wrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th class="text-center">Data Kendaraan</th>
                                    <th class="text-center">Pemilik</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $kendaraan = query("SELECT k.id_kendaraan, k.nomor_kendaraan, k.jenis_kendaraan, k.model_kendaraan, p.id_penghuni, u.nama, ka.nomor_kamar
                                FROM kendaraan k
                                INNER JOIN penghuni p ON k.id_penghuni = p.id_penghuni
                                INNER JOIN users u ON p.id_users = u.id_users
                                INNER JOIN kamar ka ON p.id_kamar = ka.id_kamar");
                                $i = 1;
                                foreach ($kendaraan as $k) :
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $i; ?></td>
                                        <!-- <td class="text-center"><?= $k['nomor_kendaraan']; ?></td>
                                        <td class="text-center"><?= $k['jenis_kendaraan']; ?></td>
                                        <td class="text-center"><?= $k['model_kendaraan']; ?></td> -->

                                        <td class="text-center">
                                            <h3 class="font-weight-bold"><?= $k['nomor_kendaraan']; ?></h3>
                                            <h5><?= $k['model_kendaraan']; ?></h5>
                                            <h6 class="text-muted"><?= $k['jenis_kendaraan']; ?></h6>
                                        </td>

                                        <td class="text-center"><?= $k['nama']; ?> <br>
                                            <h6 class="text-muted">Kamar <?= $k['nomor_kamar']; ?></h6>
                                        </td>
                                        <td align="center">
                                            <button type="button" class="btn btn-primary" onclick="ubahkendaraan(<?= $k['id_kendaraan']; ?>)">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>

                                            <button type="button" class="btn btn-danger" onclick="hapusKendaraan(<?= $k['id_kendaraan']; ?>)">
                                                <i class="fas fa-trash"></i> Hapus
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
        printContent += '<h3>Data Kendaraan</h3>';
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