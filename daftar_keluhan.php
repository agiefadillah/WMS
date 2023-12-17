<?php
// Memeriksa apakah pengguna memiliki peran pemilik atau penjaga
if ($_SESSION['tipe'] != 'pemilik' && $_SESSION['tipe'] != 'penjaga') {
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
                            <button id="print-button" class="btn btn-primary mb-2 mb-md-0 mr-md-2">Cetak</button>
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
                        <h3 class="card-title">Data Keluhan</h3>
                    </div>

                    <div class="table-responsive">
                        <table id="multi_col_order" class="table table-striped table-bordered display no-wrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th class="text-center">Foto Keluhan</th>
                                    <th class="text-center">Tanggal Keluhan</th>
                                    <th class="text-center">Nama Penghuni</th>
                                    <th class="text-center">Keluhan</th>
                                    <th class="text-center">Status Keluhan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $keluhan = query("SELECT k.id_keluhan, k.gambar_keluhan, k.tanggal_keluhan, u.nama, ka.nomor_kamar, k.isi_keluhan, k.status_keluhan
                                FROM keluhan k
                                INNER JOIN penghuni p ON k.id_penghuni = p.id_penghuni
                                INNER JOIN users u ON p.id_users = u.id_users
                                INNER JOIN kamar ka ON p.id_kamar = ka.id_kamar");

                                $i = 1;
                                foreach ($keluhan as $k) :

                                ?>

                                    <tr>
                                        <td class="text-center"><?= $i; ?></td>
                                        <td class="text-center">
                                            <img src="<?php echo $k['gambar_keluhan'] ?>" width="75%">
                                            <br><a href="<?php echo $k['gambar_keluhan'] ?>">Lihat Gambar Keluhan</a>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $tanggal_keluhan = strtotime($k['tanggal_keluhan']);
                                            $tanggal_keluhanFormatted = date('d-m-Y H:i', $tanggal_keluhan);
                                            echo $tanggal_keluhanFormatted;
                                            ?>
                                        </td>

                                        <td class="text-center"><?= $k['nama']; ?> <br>
                                            <h6 class="text-muted">Kamar <?= $k['nomor_kamar']; ?></h6>
                                        </td>
                                        <td class="text-center"><?= $k['isi_keluhan']; ?></td>
                                        <td class="text-center">
                                            <?php
                                            $status = $k['status_keluhan'];
                                            if ($status == 'Sudah Ditanggapi') {
                                                echo '<span class="badge badge-success">Sudah Ditanggapi</span>';
                                            } else if ($status == 'Sedang Ditanggapi') {
                                                echo '<span class="badge badge-warning">Sedang Ditanggapi</span>';
                                            } else {
                                                echo '<span class="badge badge-danger">Belum Ditanggapi</span>';
                                            }
                                            ?>
                                        </td>
                                        <td align="center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="nav-icon fas fa-eye"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <button type="button" class="btn btn-danger dropdown-item" onclick="updatestatuskeluhan('<?= $k['id_keluhan']; ?>', 'belum ditanggapi')" data-id="<?= $k['id_keluhan']; ?>">
                                                        <i class="nav-icon fas fa-x"></i>
                                                        Belum Ditanggapi
                                                    </button>
                                                    <button type="button" class="btn btn-info dropdown-item" onclick="updatestatuskeluhan('<?= $k['id_keluhan']; ?>', 'sedang ditanggapi')" data-id="<?= $k['id_keluhan']; ?>">
                                                        <i class="fas fa-slash"></i>
                                                        Sedang Ditanggapi
                                                    </button>
                                                    <button type="button" class="btn btn-success dropdown-item" onclick="updatestatuskeluhan('<?= $k['id_keluhan']; ?>', 'sudah ditanggapi')" data-id="<?= $k['id_keluhan']; ?>">
                                                        <i class="nav-icon fas fa-check"></i>
                                                        Sudah Ditanggapi
                                                    </button>
                                                    <!-- <div class="dropdown-divider"></div>
                                                    <button type="button" class="btn btn-warning dropdown-item" onclick="ubahkeluhan('<?= $k['id_keluhan']; ?>')">
                                                        <i class="fas fa-edit"></i>
                                                        Edit
                                                    </button>
                                                    <div class="dropdown-divider"></div>
                                                    <button type="button" class="btn btn-danger dropdown-item" onclick="hapuskeluhan('<?= $k['id_keluhan']; ?>')">
                                                        <i class="fas fa-trash"></i>
                                                        Hapus
                                                    </button> -->
                                                </div>
                                            </div>
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
    function updatestatuskeluhan(id_keluhan, status_keluhan) {
        $.ajax({
            url: "config/status_keluhan.php",
            type: "POST",
            data: {
                id_keluhan: id_keluhan,
                status_keluhan: status_keluhan
            },
            success: function(data) {
                if (data == "success") {
                    // Reload halaman tagihan jika update sukses
                    location.reload();
                } else {
                    alert("Update status gagal!");
                }
            },
            error: function() {
                alert("Update status gagal!");
            }
        });
    }

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
        printContent += '<h3>Daftar Keluhan</h3>';
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