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
    if (ubahTamu($_POST) > 0) {
        echo '
       <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
       <script>
           Swal.fire({
               icon: "success",
               title: "Data Berhasil Diubah",
               showConfirmButton: false,
               timer: 1500
           }).then(function () {
               window.location.href = "index.php?page=daftar_tamu";
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
            window.location.href = "index.php?page=daftar_tamu";
        });
    </script>';
    }
}

// Mendapatkan data tamu yang akan diubah
$id_tamu = $_GET['id_tamu'];
$data_tamu = query("SELECT * FROM tamu WHERE id_tamu='$id_tamu '")[0];
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Ubah Data Tamu</h3>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_tamu" value="<?= $data_tamu['id_tamu']; ?>">

                        <h5 class="card-title mt-4">Tanggal Masuk Tamu</h5>
                        <div class="col-sm">
                            <input type="datetime-local" class="form-control" id="tanggal_tamu" name="tanggal_tamu" value="<?= $data_tamu['tanggal_tamu']; ?>" required>
                        </div>

                        <h5 class="card-title mt-4">Nama Tamu</h5>
                        <div class="col-sm">
                            <input type="text" class="form-control" id="nama_tamu" name="nama_tamu" placeholder="Masukkan Nama Tamu" value="<?= $data_tamu['nama_tamu']; ?>" required>
                        </div>

                        <h5 class="card-title mt-4">Tarif</h5>
                        <div class="col-sm">
                            <input type="number" class="form-control" id="tarif_tamu" name="tarif_tamu" placeholder="Masukkan Tarif | Contoh: 25000" value="<?= $data_tamu['tarif_tamu']; ?>" required>
                        </div>

                        <h5 class="card-title mt-4">Lama Tamu Menginap</h5>
                        <div class="col-sm">
                            <input type="number" class="form-control" id="waktu_menginap_tamu" name="waktu_menginap_tamu" placeholder="Masukkan Lama Tamu Menginap | Contoh: 2" value="<?= $data_tamu['waktu_menginap_tamu']; ?>" required>
                        </div>

                        <h5 class="card-title mt-4">Identitas Tamu</h5>
                        <div class="col-sm">
                            <input name="identitas_tamu" id="identitas_tamu" type="file" class="form-control" onchange="previewImage(event)">
                            <?php if (!empty($data_tamu['identitas_tamu'])) : ?>
                                <img id="identitas_tamu_preview" src="<?php echo $data_tamu['identitas_tamu']; ?>" alt="identitas_tamu" style="max-width: 200px; margin-top: 10px;">
                                <br><a href="<?php echo $data_tamu['identitas_tamu'] ?>">Lihat Identitas Tamu</a>
                            <?php endif; ?>
                        </div>

                        <h5 class="card-title mt-4">Nama Penghuni</h5>
                        <div class="col-sm">
                            <select class="form-control" name="id_penghuni" required>
                                <option value="">Pilih Penghuni</option>
                                <?php
                                $query = "SELECT p.id_penghuni, u.nama, k.nomor_kamar
                                    FROM penghuni p
                                    INNER JOIN users u ON p.id_users = u.id_users
                                    INNER JOIN kamar k ON p.id_kamar = k.id_kamar";
                                $result = query($query);

                                foreach ($result as $row) {
                                    $selected = ($row['id_penghuni'] == $data_tamu['id_penghuni']) ? 'selected' : '';
                                ?>
                                    <option value="<?= $row['id_penghuni']; ?>" <?= $selected; ?>><?= $row['nama'] . ' - Kamar ' . $row['nomor_kamar']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-sm mt-4">
                            <input type="submit" name="simpan" class="btn btn-success" value="Simpan">
                            <a href="index.php?page=daftar_tamu" class="btn btn-primary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        var imageField = document.getElementById("identitas_tamu_preview");

        reader.onload = function() {
            if (reader.readyState == 2) {
                imageField.src = reader.result;
            }
        }

        reader.readAsDataURL(event.target.files[0]);
    }
</script>