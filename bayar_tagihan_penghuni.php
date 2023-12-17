<?php

if (isset($_POST['simpan'])) {
    if (tambahBayarTagihan($_POST) > 0) {
        echo '
       <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
       <script>
           Swal.fire({
               icon: "success",
               title: "Data Berhasil Ditambahkan",
               showConfirmButton: false,
               timer: 1500
           }).then(function() {
               window.location.href = "index.php?page=daftar_tagihan_penghuni";
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
            timer: 2500
        }).then(function () {
            window.location.href = "index.php?page=daftar_tagihan_penghuni";
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
                    <h3 class="card-title">Form Bayar Tagihan</h3>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <h5 class="card-title mt-4">Tanggal Bayar</h5>
                        <div class="col-sm">
                            <input type="date" class="form-control" id="tanggal_pembayaran" name="tanggal_pembayaran" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>

                        <h5 class="card-title mt-4">Tagihan</h5>
                        <div class="col-sm">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-center" scope="col">Pilih Tagihan</th>
                                            <th class="text-center" scope="col">Nomor Tagihan</th>
                                            <th class="text-center" scope="col">Tanggal Tagihan</th>
                                            <th class="text-center" scope="col">Kategori Tagihan</th>
                                            <th class="text-center" scope="col">Jumlah Tagihan</th>
                                            <th class="text-center" scope="col">Status Tagihan</th>
                                            <th class="text-center" scope="col">Keterangan Tagihan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $tagihan = query("SELECT * FROM tagihan WHERE id_penghuni = '" . $_SESSION['id_penghuni'] . "' AND status_tagihan IN ('Belum Bayar', 'Belum Lunas')");
                                        foreach ($tagihan as $t) :
                                        ?>
                                            <tr>
                                                <td class="text-center">
                                                    <div class="form-check form-check-inline">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" name="id_tagihan[]" id="tagihan<?= $t['id_tagihan'] ?>" value="<?= $t['id_tagihan'] ?>" onchange="hitungTotalTagihan()">
                                                            <label class="custom-control-label" for="tagihan<?= $t['id_tagihan'] ?>"></label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center"><?= $t['no_tagihan'] ?></td>
                                                <td class="text-center">
                                                    <?php
                                                    $tanggal_tagihan = strtotime($t['tanggal_tagihan']);
                                                    $tanggal_tagihanFormatted = date('d-m-Y', $tanggal_tagihan);
                                                    echo $tanggal_tagihanFormatted;
                                                    ?></td>
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
                                                </td>
                                                <td class="text-center">Rp. <?= number_format($t['jumlah_tagihan'], 0, ',', '.') ?></td>
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
                                                <td class="text-center"><?= $t['keterangan_tagihan'] ?></td>
                                            </tr>
                                        <?php
                                        endforeach;
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>

                        <h5 class="card-title mt-4">Jumlah Tagihan</h5>
                        <div class="col-sm">
                            <input type="text" class="form-control" id="tagihan_penghuni" name="tagihan_penghuni" oninput="hitungSisaTagihan()" readonly>
                        </div>

                        <h5 class="card-title mt-4">Jumlah Sisa Tagihan</h5>
                        <div class="col-sm">
                            <input type="number" class="form-control" name="jumlah_sisa_tagihan" id="jumlah_sisa_tagihan" readonly>
                        </div>

                        <h5 class="card-title mt-4">Jumlah Bayar</h5>
                        <div class="col-sm">
                            <input type="number" class="form-control" id="jumlah_bayar" name="jumlah_bayar" oninput="hitungSisaTagihan()" required>
                        </div>


                        <h5 class="card-title mt-4">Keterangan Pembayaran</h5>
                        <div class="col-sm">
                            <select class="select2 form-select shadow-none" style="width: 100%; height:36px;" name="keterangan" id="keterangan" required>
                                <option value="">Pilih Keterangan Bayar</option>
                                <?php
                                $rekening = query("SELECT * FROM rekening");
                                foreach ($rekening as $r) {
                                    echo '<option value="' . $r['jenis_pembayaran'] . '">' . $r['jenis_pembayaran'] . " - " .  $r['nomor_rekening'] . " - " .  $r['pemilik_rekening'] . '</option>';
                                }
                                ?>
                                <option value="EDC">EDC</option>
                                <option value="QRIS">QRIS</option>
                            </select>
                        </div>

                        <h5 class="card-title mt-4">Bukti Pembayaran</h5>
                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <input name="bukti_pembayaran" id="bukti_pembayaran" type="file" class="form-control" onchange="previewImage(event)">
                                    <img id="preview" src="img/nodata.jpg" alt="Preview" style="max-width: 100%; max-height: 200px; margin-top: 10px;">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm mt-4">
                            <input type="submit" name="simpan" class="btn btn-success" value="Simpan">
                            <a href="index.php?page=daftar_tagihan_penghuni" class="btn btn-primary">Kembali</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function hitungTotalTagihan() {
        var checkboxes = document.getElementsByName('id_tagihan[]');
        var total = 0;
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked) {
                var row = checkboxes[i].closest('tr');
                var tagihanElement = row.querySelector('.text-center:nth-child(5)');
                if (tagihanElement && tagihanElement.textContent) {
                    var tagihan = parseInt(tagihanElement.textContent.replace(/\D/g, ''));
                    total += tagihan;
                }
            }
        }
        document.getElementById('tagihan_penghuni').value = total;
    }
</script>

<script>
    // Fungsi untuk menampilkan gambar pratinjau saat memilih file
    function previewImage(event) {
        var input = event.target;
        var preview = document.getElementById('preview');

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    // Mengatur gambar default saat halaman dimuat
    window.addEventListener('DOMContentLoaded', function() {
        var defaultImage = 'img/nodata.jpg';
        var preview = document.getElementById('preview');
        preview.src = defaultImage;
        preview.style.display = 'block';
    });
</script>

<script>
    function hitungSisaTagihan() {
        var jumlahTagihan = parseInt(document.getElementById('tagihan_penghuni').value) || 0;
        var jumlahBayarTagihan = parseInt(document.getElementById('jumlah_bayar').value) || 0;
        var jumlahSisaTagihan = jumlahTagihan - jumlahBayarTagihan;

        document.getElementById('jumlah_sisa_tagihan').value = jumlahSisaTagihan;
    }
</script>