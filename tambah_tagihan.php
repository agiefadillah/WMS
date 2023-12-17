<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    if (tambahTagihan($_POST) > 0) {
        echo '
       <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
       <script>
           Swal.fire({
               icon: "success",
               title: "Data Berhasil Ditambahkan",
               showConfirmButton: false,
               timer: 1500
           }).then(function () {
               window.location.href = "index.php?page=daftar_tagihan";
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
            window.location.href = "index.php?page=daftar_tagihan";
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
                    <h3 class="card-title">Buat Tagihan</h3>
                    <form action="" method="POST">

                        <h5 class="card-title mt-4">Tanggal Tagihan</h5>
                        <div class="col-sm">
                            <input type="datetime-local" class="form-control" id="tanggal_tagihan" name="tanggal_tagihan" value="<?php echo date('Y-m-d H:i'); ?>">
                        </div>

                        <h5 class="card-title mt-4">Nama Penghuni</h5>
                        <div class="col-sm">
                            <select class="form-control" name="id_penghuni" id="id_penghuni" required>
                                <option value="">Pilih Penghuni</option>
                                <?php
                                $query = "SELECT p.id_penghuni, u.nama, k.nomor_kamar, k.harga_kamar, p.status_penghuni
                                FROM penghuni p
                                INNER JOIN users u ON p.id_users = u.id_users
                                INNER JOIN kamar k ON p.id_kamar = k.id_kamar
                                ORDER BY p.status_penghuni ASC, u.nama ASC";
                                $result = query($query);

                                foreach ($result as $row) :
                                    $status = ($row['status_penghuni'] === 'aktif') ? 'Aktif' : 'Tidak Aktif';
                                    $option_text = $row['nama'] . ' (' . $status . ')' . ' - Kamar ' . $row['nomor_kamar'];
                                ?>
                                    <option value="<?= $row['id_penghuni']; ?>" data-harga-kamar="<?= $row['harga_kamar']; ?>"><?= $option_text; ?></option>
                                <?php
                                endforeach;
                                ?>
                            </select>

                        </div>

                        <h5 class="card-title mt-4">Kategori Tagihan</h5>
                        <h6>Apabila Ingin Membuat Tagihan Tamu Gunakan Menu <a href="index.php?page=daftar_tamu">Tamu</a></h6>
                        <div class="col-sm">
                            <select class="form-control" name="kategori_tagihan" id="kategori_tagihan" required>
                                <option value="">Pilih Kategori Tagihan</option>
                                <option value="sewa_kamar">Tagihan Sewa Kamar</option>
                                <option value="sewa_kendaraan">Tagihan Kendaraan</option>
                                <option value="Tanda Jadi">Tagihan Tanda Jadi</option>
                                <option value="sewa_lainnya">Tagihan Lainnya</option>
                            </select>
                        </div>

                        <div id="form_sewa_kamar" style="display: none;">
                            <h5 class="card-title mt-4">Deskripsi Tagihan Kamar</h5>
                            <div class="col-sm">
                                <input type="text" class="form-control" name="deskripsi_sewa_kamar" placeholder="Masukkan Deskripsi Tagihan Kamar | Contoh: Sewa Kamar Januari 2023">
                            </div>

                            <h5 class="card-title mt-4">Jumlah Tagihan Kamar</h5>
                            <div class="col-sm">
                                <input type="number" class="form-control" name="jumlah_sewa_kamar" id="jumlah_sewa_kamar">
                            </div>

                            <h5 class="card-title mt-4">Keterangan Tagihan Kamar</h5>
                            <div class="col-sm">
                                <input type="text" class="form-control" name="keterangan_sewa_kamar" placeholder="Masukkan Keterangan Tagihan Kamar">
                            </div>
                        </div>


                        <div id="form_sewa_kendaraan" style="display: none;">
                            <h5 class="card-title mt-4">Deskripsi Tagihan Kendaraan</h5>
                            <div class="col-sm">
                                <input type="text" class="form-control" name="deskripsi_sewa_kendaraan" placeholder="Masukkan Deskripsi Tagihan Kendaraan | Contoh: Tagihan Parkir Kendaraan Januari 2023">
                            </div>

                            <h5 class="card-title mt-4">Jumlah Tagihan Kendaraan</h5>
                            <div class="col-sm">
                                <input type="number" class="form-control" name="jumlah_sewa_kendaraan" placeholder="Masukkan Jumlah Tagihan Kendaraan | Contoh: 50000">
                            </div>

                            <h5 class="card-title mt-4">Keterangan Tagihan Kendaraan</h5>
                            <div class="col-sm">
                                <input type="text" class="form-control" name="keterangan_sewa_kendaraan" placeholder="Masukkan Keterangan Tagihan Kendaraan">
                            </div>
                        </div>

                        <div id="form_sewa_lainnya" style="display: none;">
                            <h5 class="card-title mt-4">Deskripsi Tagihan Lainnya</h5>
                            <div class="col-sm">
                                <input type="text" class="form-control" name="deskripsi_sewa_lainnya" placeholder="Masukkan Deskripsi Tagihan Lainnya | Contoh: Tagihan Kunci Hilang">
                            </div>

                            <h5 class="card-title mt-4">Jumlah Tagihan Lainnya</h5>
                            <div class="col-sm">
                                <input type="number" class="form-control" name="jumlah_sewa_lainnya" placeholder="Masukkan Jumlah Tagihan Lainnya | Contoh: 50000">
                            </div>

                            <h5 class="card-title mt-4">Keterangan Tagihan Lainnya</h5>
                            <div class="col-sm">
                                <input type="text" class="form-control" name="keterangan_sewa_lainnya" placeholder="Masukkan Keterangan Tagihan Lainnya">
                            </div>
                        </div>

                        <div id="form_tanda_jadi" style="display: none;">
                            <h5 class="card-title mt-4">Deskripsi Tagihan Tanda Jadi</h5>
                            <div class="col-sm">
                                <input type="text" class="form-control" name="deskripsi_tanda_jadi" placeholder="Masukkan Deskripsi Tagihan Tanda Jadi | Contoh: Tagihan Tanda Jadi">
                            </div>

                            <h5 class="card-title mt-4">Jumlah Tagihan Tanda Jadi</h5>
                            <div class="col-sm">
                                <input type="number" class="form-control" name="jumlah_tanda_jadi" placeholder="Masukkan Jumlah Tagihan Tanda Jadi | Contoh: 50000">
                            </div>

                            <h5 class="card-title mt-4">Keterangan Tagihan Tanda Jadi</h5>
                            <div class="col-sm">
                                <input type="text" class="form-control" name="keterangan_tanda_jadi" placeholder="Masukkan Keterangan Tagihan Tanda Jadi">
                            </div>
                        </div>

                        <div class="col-sm mt-4">
                            <input type="submit" name="simpan" class="btn btn-success" value="Simpan">
                            <a href="index.php?page=daftar_tagihan" class="btn btn-primary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById("kategori_tagihan").addEventListener("change", function() {
        var kategoriTagihan = this.value;

        // Reset semua form
        document.getElementById("form_sewa_kamar").style.display = "none";
        document.getElementById("form_sewa_kendaraan").style.display = "none";
        document.getElementById("form_sewa_lainnya").style.display = "none";
        document.getElementById("form_tanda_jadi").style.display = "none";

        // Tampilkan form sesuai dengan kategori tagihan yang dipilih
        if (kategoriTagihan === "sewa_kamar") {
            document.getElementById("form_sewa_kamar").style.display = "block";
            // Lakukan penanganan harga kamar otomatis di sini (sesuaikan dengan logika bisnis Anda)
            var idPenghuni = document.getElementById("id_penghuni").value;
            if (idPenghuni) {
                // Ambil harga kamar berdasarkan id_penghuni
                var hargaKamar = document.querySelector("select[name='id_penghuni'] option[value='" + idPenghuni + "']").dataset.hargaKamar;
                document.getElementById("jumlah_sewa_kamar").value = hargaKamar;
            }
        } else if (kategoriTagihan === "sewa_kendaraan") {
            document.getElementById("form_sewa_kendaraan").style.display = "block";
        } else if (kategoriTagihan === "sewa_lainnya") {
            document.getElementById("form_sewa_lainnya").style.display = "block";
        } else if (kategoriTagihan === "Tanda Jadi") {
            document.getElementById("form_tanda_jadi").style.display = "block";
        }
    });

    document.getElementById("id_penghuni").addEventListener("change", function() {
        var idPenghuni = this.value;
        var kategoriTagihan = document.getElementById("kategori_tagihan").value;

        if (idPenghuni && kategoriTagihan === "sewa_kamar") {
            var hargaKamar = document.querySelector("select[name='id_penghuni'] option[value='" + idPenghuni + "']").dataset.hargaKamar;
            document.getElementById("jumlah_sewa_kamar").value = hargaKamar;
        }
    });
</script>