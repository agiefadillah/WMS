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
    if (pindahKamar($_POST) > 0) {
        echo '
       <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
       <script>
           Swal.fire({
               icon: "success",
               showConfirmButton: false,
               timer: 1500
           }).then(function () {
               window.location.href = "index.php?page=pindah_kamar";
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
            window.location.href = "index.php?page=pindah_kamar";
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
                    <h3 class="card-title">Pindah Kamar</h3>
                    <form action="" method="POST">
                        <h5 class="card-title mt-4">Nama Penghuni</h5>
                        <div class="col-sm">
                            <select class="select2 form-select shadow-none" style="width: 100%; height:36px;" id="id_penghuni" name="id_penghuni" onchange="getKamarLama()">
                                <option value="">Pilih Penghuni</option>

                                <?php
                                $penghuni = query("SELECT penghuni.*, users.nama, kamar.nomor_kamar 
                                FROM penghuni 
                                INNER JOIN users ON penghuni.id_users = users.id_users
                                INNER JOIN kamar ON penghuni.id_kamar = kamar.id_kamar
                                WHERE penghuni.status_penghuni = 'Aktif'");

                                foreach ($penghuni as $p) :
                                    echo "<option value='" . $p['id_penghuni'] . "' data-nomorkamar='" . $p['nomor_kamar'] . "'>" . $p['nama'] . " - Kamar " . $p['nomor_kamar'] . "</option>";
                                endforeach;
                                ?>
                                a
                            </select>
                        </div>

                        <h5 class="card-title mt-4">Kamar Lama</h5>
                        <div class="col-sm">
                            <input type="text" class="form-control" id="kamar_lama" name="kamar_lama" readonly>
                        </div>

                        <h5 class="card-title mt-4">Kamar Baru</h5>
                        <div class="col-sm">
                            <select class="select2 form-select shadow-none" style="width: 100%; height:36px;" id="id_kamar" name="id_kamar">
                                <option value="">Pilih Kamar</option>
                                <?php
                                $kamar = query("SELECT * FROM kamar WHERE status_kamar='Tersedia' ORDER BY nomor_kamar");
                                foreach ($kamar as $k) :
                                    echo "<option value='" . $k['id_kamar'] . "'>" . "Kamar " . $k['nomor_kamar'] . " - Rp. " . number_format($k['harga_kamar'], 0, ',', '.') . "</option>";
                                endforeach;
                                ?>
                            </select>
                        </div>

                        <h5 class="card-title mt-4">Alasan Pindah</h5>
                        <div class="col-sm">
                            <input type="text" class="form-control" id="alasan_pindah" name="alasan_pindah">
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
                        <h3 class="card-title">Riwayat Pindah Kamar</h3>
                        <div class="ml-auto">

                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered display no-wrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th class="text-center" width="15%">Tanggal Pindah</th>
                                    <th class="text-center">Nama Penghuni</th>
                                    <th class="text-center">Kamar Lama</th>
                                    <th class="text-center">Kamar Baru</th>
                                    <th class="text-center">Alasan Pindah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT pk.waktu_pindah, u.nama, kamar_sebelum.nomor_kamar AS kamar_sebelum, kamar_sesudah.nomor_kamar AS kamar_sesudah,
                                pk.alasan_pindah
                                FROM pindah_kamar pk
                                INNER JOIN penghuni p ON pk.id_penghuni = p.id_penghuni
                                INNER JOIN users u ON p.id_users = u.id_users
                                INNER JOIN kamar kamar_sebelum ON pk.kamar_sebelum = kamar_sebelum.id_kamar
                                INNER JOIN kamar kamar_sesudah ON pk.kamar_sesudah = kamar_sesudah.id_kamar";

                                $riwayat = query($query);

                                $i = 1;
                                foreach ($riwayat as $r) :
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $i; ?></td>
                                        <td class="text-center">
                                            <?php
                                            $waktu_pindah = strtotime($r['waktu_pindah']);
                                            $waktu_pindahFormatted = date('d-m-Y H:i', $waktu_pindah);
                                            echo $waktu_pindahFormatted;
                                            ?>
                                        </td>
                                        <td><?= $r['nama']; ?></td>
                                        <td class="text-center"><?= $r['kamar_sebelum']; ?></td>
                                        <td class="text-center"><?= $r['kamar_sesudah']; ?></td>
                                        <td class="text-center"><?= $r['alasan_pindah']; ?></td>
                                    </tr>
                                <?php $i++;
                                endforeach;
                                ?>
                            </tbody>
                        </table>
                    </div>


                </div>

            </div>

        </div>

    </div>

</div>


<script>
    function getKamarLama() {
        // mendapatkan elemen select dengan id 'id_penghuni'
        var select = document.getElementById("id_penghuni");

        // mendapatkan nilai tagihan yang terpilih di option
        var nomorkamar = select.options[select.selectedIndex].getAttribute('data-nomorkamar');

        // menampilkan tagihan ke dalam elemen dengan id 'tagihan_penghuni'
        document.getElementById("kamar_lama").value = nomorkamar;
    }

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
        printContent += '<h3>Data Pindah Kamar</h3>';
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
</script>