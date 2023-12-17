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
    if (tambahTamu($_POST) > 0) {
        echo '
       <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
       <script>
           Swal.fire({
               icon: "success",
               title: "Data Berhasil Ditambahkan",
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

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Tambah Data Tamu</h3>
                    <form action="" method="POST" enctype="multipart/form-data">

                        <h5 class="card-title mt-4">Tanggal Masuk Tamu</h5>
                        <div class="col-sm">
                            <input type="datetime-local" class="form-control" id="tanggal_tamu" name="tanggal_tamu" value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                        </div>


                        <h5 class="card-title mt-4">Nama Tamu</h5>
                        <div class="col-sm">
                            <input type="text" class="form-control" id="nama_tamu" name="nama_tamu" placeholder="Masukkan Nama Tamu" required>
                        </div>

                        <h5 class="card-title mt-4">Tarif</h5>
                        <div class="col-sm">
                            <input type="number" class="form-control" id="tarif_tamu" name="tarif_tamu" placeholder="Masukkan Tarif | Contoh: 25000" required>
                        </div>

                        <h5 class="card-title mt-4">Lama Tamu Menginap</h5>
                        <div class="col-sm">
                            <input type="number" class="form-control" id="waktu_menginap_tamu" name="waktu_menginap_tamu" placeholder="Masukkan Lama Tamu Menginap | Contoh: 2" required>
                        </div>

                        <h5 class="card-title mt-4">Identitas Tamu</h5>
                        <div class="col-sm">
                            <input name="identitas_tamu" id="identitas_tamu" type="file" class="form-control" onchange="previewImage(event)">
                            <img id="preview" src="img/nodata.jpg" alt="Preview" style="max-width: 100%; max-height: 200px; margin-top: 10px;">
                        </div>

                        <h5 class="card-title mt-4">Nama Penghuni</h5>
                        <div class="col-sm">
                            <select class="form-control" name="id_penghuni" required>
                                <option value="">Pilih Penghuni</option>
                                <?php
                                $query = "SELECT p.id_penghuni, u.nama, k.nomor_kamar
                                FROM penghuni p
                                INNER JOIN users u ON p.id_users = u.id_users
                                INNER JOIN kamar k ON p.id_kamar = k.id_kamar
                                WHERE p.status_penghuni = 'Aktif'";
                                $result = query($query);

                                foreach ($result as $row) :
                                ?>
                                    <option value="<?= $row['id_penghuni']; ?>"><?= $row['nama'] . ' - Kamar ' . $row['nomor_kamar']; ?></option>
                                <?php
                                endforeach;
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