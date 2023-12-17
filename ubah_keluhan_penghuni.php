<?php

$id_keluhan = $_GET['id_keluhan'];
$keluhan = query("SELECT * FROM keluhan WHERE id_keluhan='$id_keluhan'")[0];

if (isset($_POST['simpan'])) {
    $result = ubahKeluhan_Penghuni($_POST, $_FILES['gambar_keluhan'], $id_keluhan);

    if ($result) {
        echo '
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
        <script>
            Swal.fire({
                icon: "success",
                title: "Data Berhasil Diubah",
                showConfirmButton: false,
                timer: 1500
            }).then(function () {
                window.location.href = "index.php?page=daftar_keluhan_penghuni";
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
                timer: 2500
            }).then(function () {
                window.location.href = "index.php?page=daftar_keluhan_penghuni";
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
                    <h3 class="card-title">Form Ubah Keluhan Wisma Mutiara Selaras</h3>

                    <form action="" method="POST" enctype="multipart/form-data">

                        <input type="hidden" name="id_keluhan" value="<?php echo $keluhan['id_keluhan']; ?>">

                        <h5 class="card-title mt-4">Gambar Keluhan</h5>
                        <div class="col-sm">
                            <input name="gambar_keluhan" id="gambar_keluhan" type="file" class="form-control" onchange="previewImage(event)">
                            <?php if (!empty($keluhan['gambar_keluhan'])) : ?>
                                <img id="gambar_keluhan_preview" src="<?php echo $keluhan['gambar_keluhan']; ?>" alt="gambar_keluhan" style="max-width: 200px; margin-top: 10px;">
                                <br><a href="<?php echo $keluhan['gambar_keluhan'] ?>">Lihat</a>
                            <?php endif; ?>
                        </div>

                        <h5 class="card-title mt-4">Keluhan</h5>
                        <div class="col-sm">
                            <input type="text" class="form-control" placeholder="Masukkan Isi Keluhan" name="isi_keluhan" id="isi_keluhan" value="<?php echo $keluhan['isi_keluhan']; ?>" required>
                        </div>

                        <div class="col-sm mt-4">
                            <input type="submit" name="simpan" class="btn btn-success" value="Simpan">
                            <a href="index.php?page=daftar_keluhan_penghuni" class="btn btn-primary">Kembali</a>
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
        var imageField = document.getElementById("gambar_keluhan_preview");

        reader.onload = function() {
            if (reader.readyState == 2) {
                imageField.src = reader.result;
            }
        }

        reader.readAsDataURL(event.target.files[0]);
    }
</script>