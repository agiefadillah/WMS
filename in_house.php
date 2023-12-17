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
                            <a href="index.php?page=check_in" class="btn btn-primary mb-2 mb-md-0 mr-md-2">Check-In</a>
                            <a href="index.php?page=check_out" class="btn btn-primary mb-2 mb-md-0 mr-md-2">Check-Out</a>
                            <button type="button" class="btn btn-primary mb-2 mb-md-0 mr-md-2" onclick="RiwayatCheckOut()">Riwayat Check-Out</button>
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
                        <h3 class="card-title">Penghuni In-House</h3>
                    </div>

                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered display no-wrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th class="text-center">Kamar</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Tanggal Masuk</th>
                                    <th class="text-center">Tanggal Keluar</th>
                                    <th class="text-center">Durasi Sewa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Query untuk mendapatkan data dari tabel penghuni_in_house dengan data yang dibutuhkan
                                $query = "SELECT k.nomor_kamar, u.nama, pih.tanggal_masuk_pih, pih.tanggal_keluar_pih, pih.durasi_sewa_pih
                                FROM penghuni_in_house pih
                                INNER JOIN penghuni p ON pih.id_penghuni = p.id_penghuni
                                INNER JOIN users u ON p.id_users = u.id_users
                                INNER JOIN kamar k ON p.id_kamar = k.id_kamar";

                                $penghuniinhouse = query($query);

                                $i = 1;
                                foreach ($penghuniinhouse as $pih) :
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $i; ?></td>
                                        <td class="text-center"><?= $pih['nomor_kamar']; ?></td>
                                        <td class="text-center"><?= $pih['nama']; ?></td>
                                        <td class="text-center"><?= $pih['tanggal_masuk_pih']; ?></td>
                                        <td class="text-center"><?= $pih['tanggal_keluar_pih']; ?></td>
                                        <td class="text-center" id="durasiSewa_<?php echo $pih['id_pih']; ?>"></td>
                                    </tr>
                                <?php $i++;
                                endforeach; ?>
                            </tbody>
                        </table>

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
        printContent += '<h3>Data Rekening</h3>';
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
    function formatDurasiSewa(durasi_sewa_pih) {
        let bulan = durasi_sewa_pih % 12;
        let tahun = (durasi_sewa_pih - bulan) / 12;

        if (tahun > 0 && bulan > 0) {
            return tahun + ' Tahun ' + bulan + ' Bulan';
        } else if (tahun > 0) {
            return tahun + ' Tahun';
        } else {
            return bulan + ' Bulan';
        }
    }

    // Get the duration element by ID and set its text content
    const durationElement = document.getElementById('durasiSewa_<?php echo $pih['id_pih']; ?>');
    durationElement.textContent = formatDurasiSewa(<?php echo $pih['durasi_sewa_pih']; ?>);
</script>