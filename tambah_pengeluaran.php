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
    if (tambahPengeluaran($_POST) > 0) {
        echo '
       <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
       <script>
           Swal.fire({
               icon: "success",
               title: "Data Berhasil Ditambahkan",
               showConfirmButton: false,
               timer: 1500
           }).then(function () {
               window.location.href = "index.php?page=daftar_pengeluaran";
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
                    window.location.href = "index.php?page=tambah_pengeluaran";
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
                    <h3 class="card-title">Tambah Data Pengeluaran</h3>

                    <form action="" method="POST" enctype="multipart/form-data">

                        <h5 class="card-title mt-4">Tanggal</h5>
                        <div class="col-sm">
                            <input type="date" class="form-control " id="tanggal_pengeluaran" name="tanggal_pengeluaran" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>

                        <h5 class="card-title mt-4">Keterangan Pengeluaran</h5>
                        <div class="col-sm">
                            <input type="text" class="form-control " placeholder="Masukkan Keterangan Pengeluaran" name="keterangan_pengeluaran" required>
                        </div>

                        <h5 class="card-title mt-4">Jumlah Pengeluaran</h5>
                        <div class="col-sm">
                            <input type="number" class="form-control " placeholder="Masukkan Jumlah Pengeluaran" name="nominal_pengeluaran" required>
                        </div>

                        <h5 class="card-title mt-4">Bukti Pembayaran</h5>
                        <div class="col-sm">
                            <input name="uploadgambar" id="uploadgambar" type="file" class="form-control" onchange="previewImage(event)">
                            <img id="preview" src="img/nodata.jpg" alt="Preview" style="max-width: 100%; max-height: 200px; margin-top: 10px;">
                        </div>

                        <div class="col-sm mt-4">
                            <input type="submit" name="simpan" class="btn btn-success" value="Simpan">
                            <a href="index.php?page=daftar_pengeluaran" class="btn btn-primary">Kembali</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Image -->
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