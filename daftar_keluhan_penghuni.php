<?php
// Memeriksa apakah pengguna memiliki peran pemilik atau penjaga
if ($_SESSION['tipe'] != 'penghuni') {
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

                    <div class="d-flex align-items-center mb-4">
                        <h3 class="card-title">Data Keluhan</h3>
                        <div class="ml-auto">
                            <div class="dropdown sub-dropdown">
                                <a href="index.php?page=tambah_keluhan_penghuni" class="btn btn-primary float-right">Tambah Keluhan</a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="multi_col_order" class="table table-striped table-bordered display no-wrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th class="text-center" width="70%">Foto Keluhan</th>
                                    <th class="text-center">Tanggal Keluhan</th>
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
                                INNER JOIN kamar ka ON p.id_kamar = ka.id_kamar
                                WHERE p.id_penghuni = '" . $_SESSION['id_penghuni'] . "'");

                                $i = 1;
                                foreach ($keluhan as $k) :

                                ?>

                                    <tr>
                                        <td class="text-center"><?= $i; ?></td>
                                        <td class="text-center">
                                            <img src="<?php echo $k['gambar_keluhan'] ?>" width="30%">
                                            <br><a href="<?php echo $k['gambar_keluhan'] ?>">Lihat</a>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $tanggal_keluhan = strtotime($k['tanggal_keluhan']);
                                            $tanggal_keluhanFormatted = date('d-m-Y H:i:s', $tanggal_keluhan);
                                            echo $tanggal_keluhanFormatted;
                                            ?>
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
                                            <button type="button" class="btn btn-warning" onclick="ubahkeluhan_penghuni('<?= $k['id_keluhan']; ?>')">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-danger" onclick="hapusKeluhan('<?= $k['id_keluhan']; ?>')">
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