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
                            <button type="button" class="btn btn-primary mb-2 mb-md-0 mr-md-2" onclick="KonfirmasiTambahPenghuni()">Tambah Penghuni</button>
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
                        <h3 class="card-title">Data Penghuni</h3>
                    </div>
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered no-wrap">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center" width="15%">Kamar</th>
                                    <th class="text-center">Kontak</th>
                                    <th class="text-center">Tanggal Masuk</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $penghuni = query("SELECT penghuni.id_penghuni, users.nama, kamar.nomor_kamar, users.kontak AS nomorWhatsApp, users.email, users.alamat, 
                                penghuni.tanggal_masuk_penghuni, penghuni.tanggal_keluar_penghuni, penghuni.identitas_penghuni, penghuni.status_penghuni,
                                penghuni.tanggal_registrasi_penghuni, penghuni.keterangan_penghuni, users.username
                                FROM users 
                                INNER JOIN penghuni ON users.id_users = penghuni.id_users 
                                INNER JOIN kamar ON penghuni.id_kamar = kamar.id_kamar 
                                WHERE users.tipe = 'penghuni'
                                ORDER BY 
                                        CASE 
                                            WHEN status_penghuni = 'aktif' THEN 1 
                                            ELSE 2 
                                        END, nomor_kamar ASC");
                                $i = 1;
                                foreach ($penghuni as $p) :
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $i; ?></td>
                                        <td class="text-center"><?= tampildata($p['nama']); ?></td>
                                        <td class="text-center"><?= tampildata($p['nomor_kamar']); ?></td>
                                        <td class="text-center">
                                            <?php
                                            // Mengganti awalan angka 0 dengan 62 pada nomor WhatsApp
                                            $nomor_whatsapp = $p['nomorWhatsApp'];
                                            $nomor_whatsapp = preg_replace('/^0/', '62', $nomor_whatsapp);
                                            ?>
                                            <a href="https://wa.me/<?= $nomor_whatsapp; ?>" target="_blank"><?= tampildata($p['nomorWhatsApp']); ?></a>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $tanggalMasuk = date('Y-m-d', strtotime($p['tanggal_masuk_penghuni']));
                                            $tanggalMasukFormatted = date('d-m-Y', strtotime(tampildata($p['tanggal_masuk_penghuni'])));
                                            echo $tanggalMasukFormatted;
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $stat = $p['status_penghuni'];
                                            if ($stat == 'aktif') {
                                                echo '<span class="badge badge-success">Aktif</span>';
                                            } else if ($stat == 'tidak aktif') {
                                                echo '<span class="badge badge-dark">Tidak Aktif</span>';
                                            } else {
                                                echo '<span class="badge badge-danger">Error</span>';
                                            }
                                            ?>
                                        </td>
                                        <td align="center">
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#detailModal<?= $p['id_penghuni']; ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger" onclick="hapuspenghuni('<?= $p['id_penghuni']; ?>')">
                                                <i class="fas fa-trash"></i>
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
    foreach ($penghuni as $p) :
    ?>
        <div class="modal fade" id="detailModal<?= $p['id_penghuni']; ?>" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel<?= $p['id_penghuni']; ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel<?= $p['id_penghuni']; ?>">Detail Data Penghuni</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="zero_config" class="table table-striped table-bordered no-wrap">
                                <tr>
                                    <th>Status Penghuni</th>
                                    <td> <?php
                                            $stat = $p['status_penghuni'];
                                            if ($stat == 'aktif') {
                                                echo '<span class="badge badge-success">Aktif</span>';
                                            } else if ($stat == 'tidak aktif') {
                                                echo '<span class="badge badge-dark">Tidak Aktif</span>';
                                            } else {
                                                echo '<span class="badge badge-danger">Error</span>';
                                            }
                                            ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal Registrasi</th>
                                    <td> <?php
                                            $tanggalRegistrasi = date('Y-m-d', strtotime($p['tanggal_registrasi_penghuni']));
                                            $tanggalRegistrasiFormatted = date('d-m-Y', strtotime(tampildata($p['tanggal_registrasi_penghuni'])));
                                            echo $tanggalRegistrasiFormatted;
                                            ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td><?= tampildata($p['nama']); ?></td>
                                </tr>
                                <tr>
                                    <th>Nomor Kamar</th>
                                    <td><?= tampildata($p['nomor_kamar']); ?></td>
                                </tr>
                                <tr>
                                    <th>Kontak</th>
                                    <td>
                                        <?php
                                        // Mengganti awalan angka 0 dengan 62 pada nomor WhatsApp
                                        $nomor_whatsapp = $p['nomorWhatsApp'];
                                        $nomor_whatsapp = preg_replace('/^0/', '62', $nomor_whatsapp);
                                        ?>
                                        <a href="https://wa.me/<?= $nomor_whatsapp; ?>" target="_blank"><?= tampildata($p['nomorWhatsApp']); ?></a>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Email</th>
                                    <td><?= tampildata($p['email']); ?></td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td><?= tampildata($p['alamat']); ?></td>
                                </tr>
                                <tr>
                                    <th>Identitas Penghuni</th>
                                    <td><img src="<?php echo $p['identitas_penghuni'] ?>" width="35%">
                                        <br><a href="<?php echo $p['identitas_penghuni'] ?>">Lihat Identitas Penghuni</a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal Masuk</th>
                                    <td> <?php
                                            $tanggalMasuk = date('Y-m-d', strtotime($p['tanggal_masuk_penghuni']));
                                            $tanggalMasukFormatted = date('d-m-Y', strtotime(tampildata($p['tanggal_masuk_penghuni'])));
                                            echo $tanggalMasukFormatted;
                                            ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal Keluar</th>
                                    <td> <?php
                                            $tanggalKeluar = date('Y-m-d', strtotime($p['tanggal_keluar_penghuni']));
                                            $tanggalKeluarFormatted = date('d-m-Y', strtotime(tampildata($p['tanggal_keluar_penghuni'])));
                                            echo $tanggalKeluarFormatted;
                                            ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Durasi Sewa</th>
                                    <td> <?php
                                            // Menghitung durasi sewa dalam bulan
                                            $tglMasuk = new DateTime($tanggalMasuk);
                                            $tglKeluar = new DateTime($tanggalKeluar);
                                            $durasi = $tglMasuk->diff($tglKeluar);
                                            $durasiBulan = $durasi->m + ($durasi->y * 12);
                                            echo $durasiBulan . ' Bulan';
                                            ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Username</th>
                                    <td><?= tampildata($p['username']); ?></td>
                                </tr>
                                <tr>
                                    <th>Tanda Jadi</th>
                                    <td>
                                        <?php
                                        // Ambil data Tanda Jadi dari tabel tagihan berdasarkan id_penghuni
                                        $id_penghuni = $p['id_penghuni'];
                                        $query_tanda_jadi = "SELECT * FROM tagihan WHERE id_penghuni = '$id_penghuni' AND kategori_tagihan = 'Tanda Jadi'";
                                        $result_tanda_jadi = mysqli_query($koneksi, $query_tanda_jadi);

                                        // Jika data Tanda Jadi ditemukan, tampilkan di tabel
                                        if (mysqli_num_rows($result_tanda_jadi) > 0) {
                                            while ($row_tanda_jadi = mysqli_fetch_assoc($result_tanda_jadi)) {
                                                // Mengambil data jumlah_tagihan dari database
                                                $jumlah_tagihan = $row_tanda_jadi['jumlah_tagihan'];

                                                // Tampilkan data dengan format rupiah (Rp. )
                                                echo 'Rp. ' . number_format($jumlah_tagihan, 0, ',', '.');
                                                echo '<br>';
                                            }
                                        }
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Keterangan</th>
                                    <td><?= tampildata($p['keterangan_penghuni']); ?></td>
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
                                <button type="button" class="btn btn-warning dropdown-item" onclick="ubahpenghuni('<?= $p['id_penghuni']; ?>', '<?= $p['status_penghuni']; ?>')">
                                    <i class="fas fa-edit"></i>
                                    Edit
                                </button>

                                <button type="button" class="btn btn-danger dropdown-item" onclick="hapuspenghuni('<?= $p['id_penghuni']; ?>')">
                                    <span class="btn-label"><i class="nav-icon fas fa-trash"></i></span> Hapus
                                </button>

                                <button type="button" class="btn btn-success dropdown-item" onclick="kirimDataWhatsAppPenghuni('<?= $p['nomorWhatsApp']; ?>', {nama: '<?= $p['nama']; ?>', alamat: '<?= $p['alamat']; ?>', nomorWhatsApp: '<?= $p['nomorWhatsApp']; ?>', email: '<?= $p['email']; ?>', nomor_kamar: '<?= $p['nomor_kamar']; ?>', tanggal_masuk_penghuni: '<?= $tanggalMasukFormatted; ?>', tanggal_keluar_penghuni: '<?= $tanggalKeluarFormatted; ?>', durasiBulan: '<?= $durasiBulan; ?>', tanda_jadi: '<?= $jumlah_tagihan; ?>', username: '<?= $p['username']; ?>'})">
                                    <span class="btn-label"><i class="fa-brands fa-whatsapp"></i></span> Kirim Data ke WhatsApp Penghuni
                                </button>


                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">Ubah Status Penghuni</h6>
                                <div class="dropdown-divider"></div>

                                <button type="button" class="btn btn-dark dropdown-item" onclick="updatestatuspenghuni('<?= $p['id_penghuni']; ?>', 'tidak Aktif')" data-id="<?= $p['id_penghuni']; ?>">
                                    <span class="btn-label"><i class="fa fa-x"></i></span> Tidak Aktif
                                </button>

                                <button type="button" class="btn btn-success dropdown-item" onclick="updatestatuspenghuni('<?= $p['id_penghuni']; ?>', 'aktif')" data-id="<?= $p['id_penghuni']; ?>">
                                    <span class="btn-label"><i class="nav-icon fas fa-check"></i></span> Aktif
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

<!-- Print -->
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
        printContent += '<h3>Data Penghuni</h3>';
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

<!-- WA -->
<script>
    // Function to format number as currency (added this function)
    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    }

    function kirimDataWhatsAppPenghuni(nomor_whatsapp, data_penghuni) {
        var nama = data_penghuni.nama;
        var alamat = data_penghuni.alamat;
        var nomorWhatsApp = data_penghuni.nomorWhatsApp;
        var email = data_penghuni.email;
        var nomor_kamar = data_penghuni.nomor_kamar;
        var tanggal_masuk_penghuni = data_penghuni.tanggal_masuk_penghuni;
        var tanggal_keluar_penghuni = data_penghuni.tanggal_keluar_penghuni;
        var durasiBulan = data_penghuni.durasiBulan;
        var tanda_jadi = data_penghuni.tanda_jadi;
        var username = data_penghuni.username;

        // Mengganti angka 0 dengan 62 pada nomor WhatsApp
        nomor_whatsapp = nomor_whatsapp.replace(/^0/, "62");

        // Check if WhatsApp number is not empty
        if (nomor_whatsapp) {
            // Format tanda_jadi menjadi format mata uang
            var formatted_tanda_jadi = formatCurrency(tanda_jadi);

            var formatted_message =
                "Wisma Mutiara Selaras" + "\n" +
                "Jl. Banteng Raya 3 Gg. Sadewa No. 5 Ngabean Kulon, Sinduharjo, Kec. Ngaglik, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55581" + "\n\n" +
                "Halo " + nama + " - Kamar " + nomor_kamar + ", Berikut Adalah Rincian Data Anda:\n" +
                "Nama: " + nama + "\n" +
                "Alamat: " + alamat + "\n" +
                "Kontak: " + nomor_whatsapp + "\n" +
                "Email: " + email + "\n\n" +

                "Nomor Kamar: " + nomor_kamar + "\n" +
                "Tanggal Masuk/Check-In: " + tanggal_masuk_penghuni + "\n" +
                "Tanggal Keluar/Check-Out: " + tanggal_keluar_penghuni + "\n" +
                "Durasi Sewa: " + durasiBulan + " Bulan" + "\n" +
                "Tanda Jadi: " + formatted_tanda_jadi + "\n\n" +

                "Username: " + username + "\n" +
                "Password (default): wms" + "\n" +
                "Harap Segera Ubah Password";

            var link = "https://wa.me/" + nomor_whatsapp + "?text=" + encodeURIComponent(formatted_message);

            window.open(link);
        } else {
            // Handle the case when WhatsApp number is empty
            console.log("Nomor WhatsApp tidak tersedia.");
            // You can display an alert or any other handling here.
        }
    }
</script>

<!-- Status Penghuni -->
<script>
    function updatestatuspenghuni(id_penghuni, status_penghuni) {
        $.ajax({
            url: "config/status_penghuni.php",
            type: "POST",
            data: {
                id_penghuni: id_penghuni,
                status_penghuni: status_penghuni
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