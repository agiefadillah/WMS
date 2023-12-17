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
                    <h3 class="card-title">Buat Tagihan Tanda Jadi</h3>
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
                        <div class="col-sm">
                            <input type="text" class="form-control" name="kategori_tagihan" id="kategori_tagihan" value="Tanda Jadi" readonly>
                            </select>
                        </div>

                        <h5 class="card-title mt-4">Deskripsi Tagihan Tanda Jadi</h5>
                        <div class="col-sm">
                            <input type="text" class="form-control" name="deskripsi_tanda_jadi" placeholder="Masukkan Deskripsi Tagihan Tanda Jadi | Contoh: Tagihan Tanda Jadi">
                        </div>

                        <h5 class="card-title mt-4">Jumlah Tagihan Tanda Jadi</h5>
                        <div class="col-sm">
                            <input type="number" class="form-control" name="jumlah_tanda_jadi" placeholder="Masukkan Jumlah Tagihan Tanda Jadi | Contoh: 50000" required>
                        </div>

                        <h5 class="card-title mt-4">Keterangan Tagihan Tanda Jadi</h5>
                        <div class="col-sm">
                            <input type="text" class="form-control" name="keterangan_tanda_jadi" placeholder="Masukkan Keterangan Tagihan Tanda Jadi">
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