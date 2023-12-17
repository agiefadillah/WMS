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
    if (ubahPengeluaran($_POST) > 0) {
        echo '
       <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
       <script>
           Swal.fire({
               icon: "success",
               title: "Data Berhasil Dirubah",
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
            title: "Gagal Merubah Data",
            showConfirmButton: false,
            timer: 1500
        }).then(function () {
            window.location.href = "index.php?page=ubah_pengeluaran&id_pengeluaran=" + data;
        });
    </script>';
    }
}

$id = $_GET['id_pengeluaran'];
$pengeluaran = query("SELECT * FROM pengeluaran WHERE id_pengeluaran='$id'")[0];

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Ubah Data Pengeluaran</h3>

                    <form action="" method="POST" enctype="multipart/form-data">

                        <input type="hidden" name="id_pengeluaran" value="<?php echo $pengeluaran['id_pengeluaran']; ?>">

                        <h5 class="card-title mt-4">Tanggal</h5>
                        <div class="col-sm">
                            <input type="date" class="form-control " id="tanggal_pengeluaran" name="tanggal_pengeluaran" value="<?php echo $pengeluaran['tanggal_pengeluaran']; ?>" required>
                        </div>

                        <h5 class="card-title mt-4">Keterangan Pengeluaran</h5>
                        <div class="col-sm">
                            <input type="text" class="form-control " placeholder="Masukkan Keterangan Pengeluaran" name="keterangan_pengeluaran" value="<?php echo $pengeluaran['keterangan_pengeluaran']; ?>" required>
                        </div>

                        <h5 class="card-title mt-4">Jumlah Pengeluaran</h5>
                        <div class="col-sm">
                            <input type="number" class="form-control " placeholder="Masukkan Jumlah Pengeluaran" name="nominal_pengeluaran" value="<?php echo $pengeluaran['nominal_pengeluaran']; ?>" required>
                        </div>

                        <h5 class="card-title mt-4">Bukti Pembayaran</h5>
                        <div class="col-sm">
                            <input name="uploadgambar" id="uploadgambar" type="file" class="form-control" onchange="previewImage(event)">
                            <?php if (!empty($pengeluaran['bukti_bayar'])) : ?>
                                <img id="preview" src="<?php echo $pengeluaran['bukti_bayar']; ?>" alt="bukti_bayar" style="max-width: 200px; margin-top: 10px;">
                                <br><a href="<?php echo $pengeluaran['bukti_bayar'] ?>">Lihat</a>
                            <?php endif; ?>
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

<script>
    // Fungsi untuk menampilkan gambar pratinjau saat memilih file
    function previewImage(event) {
        var reader = new FileReader();
        var imageField = document.getElementById("preview");

        reader.onload = function() {
            if (reader.readyState == 2) {
                imageField.src = reader.result;
            }
        }

        reader.readAsDataURL(event.target.files[0]);
    }
</script>