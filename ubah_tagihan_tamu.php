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
    if (ubahTagihan($_POST) > 0) {
        echo '
       <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
       <script>
           Swal.fire({
               icon: "success",
               title: "Data Berhasil Diubah",
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
            title: "Gagal Mengubah Data",
            showConfirmButton: false,
            timer: 1500
        }).then(function () {
            window.location.href = "index.php?page=daftar_tagihan";
        });
    </script>';
    }
}

$id_tagihan = $_GET['id_tagihan'];
$ubah_tagihan = query("SELECT * FROM tagihan WHERE id_tagihan='$id_tagihan'")[0];
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Ubah Tagihan Tamu</h3>
                    <form action="" method="POST">
                        <input type="hidden" name="id_tagihan" value="<?= $ubah_tagihan['id_tagihan']; ?>">

                        <h5 class="card-title mt-4">Nomor Tagihan</h5>
                        <div class="col-sm">
                            <input type="number" class="form-control" id="no_tagihan" name="no_tagihan" value="<?= $ubah_tagihan['no_tagihan']; ?>" readonly>
                        </div>

                        <h5 class="card-title mt-4">Kategori Tagihan</h5>
                        <div class="col-sm">
                            <?php
                            $kategoriTagihan = $ubah_tagihan['kategori_tagihan'];

                            if ($kategoriTagihan == 'sewa_kamar') {
                                echo '<input type="text" class="form-control" id="kategori_tagihan" name="kategori_tagihan" value="Tagihan Sewa Kamar" readonly>';
                            } else if ($kategoriTagihan == 'sewa_kendaraan') {
                                echo '<input type="text" class="form-control" id="kategori_tagihan" name="kategori_tagihan" value="Tagihan Kendaraan" readonly>';
                            } else if ($kategoriTagihan == 'sewa_tamu') {
                                echo '<input type="text" class="form-control" id="kategori_tagihan" name="kategori_tagihan" value="Tagihan Tamu" readonly>';
                            } else if ($kategoriTagihan == 'sewa_lainnya') {
                                echo '<input type="text" class="form-control" id="kategori_tagihan" name="kategori_tagihan" value="Tagihan Lainnya" readonly>';
                            } else {
                                echo '<input type="text" class="form-control" id="kategori_tagihan" name="kategori_tagihan" value="ERROR" readonly>';
                            }
                            ?>
                        </div>

                        <h5 class="card-title mt-4">Tanggal Tagihan Tamu</h5>
                        <div class="col-sm">
                            <input type="date" class="form-control" id="tanggal_tagihan" name="tanggal_tagihan" value="<?= $ubah_tagihan['tanggal_tagihan']; ?>" readonly>
                        </div>

                        <h5 class="card-title mt-4">Nama Penghuni</h5>
                        <div class="col-sm">
                            <select class="form-control" name="id_penghuni" id="id_penghuni" disabled>
                                <option value="">Pilih Penghuni</option>
                                <?php
                                $query = "SELECT p.id_penghuni, u.nama, k.nomor_kamar, k.harga_kamar
                                    FROM penghuni p
                                    INNER JOIN users u ON p.id_users = u.id_users
                                    INNER JOIN kamar k ON p.id_kamar = k.id_kamar";
                                $result = query($query);

                                foreach ($result as $row) :
                                    $selected = ($row['id_penghuni'] == $ubah_tagihan['id_penghuni']) ? 'selected' : '';
                                ?>
                                    <option value="<?= $row['id_penghuni']; ?>" data-harga-kamar="<?= $row['harga_kamar']; ?>" <?= $selected; ?>><?= $row['nama'] . ' - Kamar ' . $row['nomor_kamar']; ?></option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                        </div>

                        <h5 class="card-title mt-4">Deskripsi Tagihan Tamu</h5>
                        <div class="col-sm">
                            <input type="text" class="form-control" name="deskripsi_tagihan" placeholder="Masukkan Deskripsi Tagihan Tamu | Contoh: Sewa Kamar Januari 2023" value="<?= $ubah_tagihan['deskripsi_tagihan']; ?>">
                        </div>

                        <h5 class="card-title mt-4">Jumlah Tagihan Tamu</h5>
                        <h6>Apabila Ingin Mengganti Data Jumlah Tagihan Tamu Gunakan Menu <a href="index.php?page=daftar_tamu">Tamu</a></h6>
                        <div class="col-sm">
                            <input type="text" class="form-control" name="jumlah_tagihan" id="jumlah_tagihan" placeholder="Masukkan Jumlah Tagihan Tamu | Contoh: 1500000" value="<?= $ubah_tagihan['jumlah_tagihan']; ?>" readonly>
                        </div>

                        <h5 class="card-title mt-4">Keterangan Tagihan Tamu</h5>
                        <div class="col-sm">
                            <input type="text" class="form-control" name="keterangan_tagihan" placeholder="Masukkan Keterangan Tagihan Tamu" value="<?= $ubah_tagihan['keterangan_tagihan']; ?>">
                        </div>

                        <div class="col-sm mt-4">
                            <input type="submit" name="simpan" class="btn btn-success mb-2 mb-md-0 mr-md-2" value="Simpan">
                            <a href="index.php?page=daftar_tagihan" class="btn btn-primary mb-2 mb-md-0 mr-md-2">Kembali</a>
                            <button type="button" class="btn btn-danger mb-2 mb-md-0 mr-md-2" onclick="hapustagihan('<?= $ubah_tagihan['id_tagihan']; ?>')">Hapus
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('id_penghuni').setAttribute('readonly', true);
</script>