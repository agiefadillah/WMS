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
                            <a href="index.php?page=tambah_pengeluaran" class="btn btn-primary mb-2 mb-md-0 mr-md-2">Tambah Data Pengeluaran</a>
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
                        <h3 class="card-title">Data Pengeluaran</h3>
                    </div>
                    <div class="table-responsive">
                        <table id="multi_col_order" class="table table-striped table-bordered display no-wrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th class="text-center" width="15%">Tanggal</th>
                                    <th class="text-center">Bukti Pembayaran</th>
                                    <th class="text-center">Keterangan Pengeluaran</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $pengeluaran = query("SELECT * FROM pengeluaran");

                                $i = 1;
                                foreach ($pengeluaran as $p) :
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $i; ?></td>
                                        <td class="text-center">
                                            <?php
                                            $tanggal = date('Y-m-d', strtotime($p['tanggal_pengeluaran']));
                                            $tanggalFormatted = date('d-m-Y', strtotime($p['tanggal_pengeluaran']));
                                            echo $tanggalFormatted;
                                            ?>
                                        </td>
                                        <td class="text-center"><img src="<?php echo $p['bukti_bayar'] ?>" width="70%">
                                            <br><a href="<?php echo $p['bukti_bayar'] ?>">Lihat</a>
                                        </td>
                                        <td><?= $p['keterangan_pengeluaran']; ?></td>
                                        <td class="text-center">Rp. <?= number_format($p['nominal_pengeluaran'], 0, ',', '.'); ?></td>


                                        <td align="center">
                                            <button type="button" class="btn btn-primary" onclick="ubahpengeluaran('<?= $p['id_pengeluaran']; ?>')">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </button>

                                            <button type="button" class="btn btn-danger" onclick="hapuspengeluaran('<?= $p['id_pengeluaran']; ?>')">
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
</div>

<script>
    // Mengambil referensi tombol cetak
    var printButton = document.getElementById('print-button');

    // Menambahkan event listener untuk klik pada tombol cetak
    printButton.addEventListener('click', function() {
        // Mengambil referensi tabel
        var table = document.getElementById('multi_col_order');

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
        printContent += '<h3>Data Pengeluaran</h3>';
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