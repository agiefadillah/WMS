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

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Check-In</h3>
                    <h6>
                        <span>Pilih Kamar</span>
                    </h6>

                    <div class="row">
                        <?php
                        // Ambil data kamar yang statusnya tersedia dari database dan urutkan berdasarkan nomor_kamar
                        $query_kamar = "SELECT * FROM kamar WHERE status_kamar = 'Tersedia' ORDER BY nomor_kamar";
                        $result_kamar = mysqli_query($koneksi, $query_kamar);

                        // Cek apakah ada kamar yang tersedia
                        if (mysqli_num_rows($result_kamar) > 0) {
                            while ($row_kamar = mysqli_fetch_assoc($result_kamar)) {
                                $id_kamar = $row_kamar['id_kamar'];
                                $nomor_kamar = $row_kamar['nomor_kamar'];
                                $harga_kamar = $row_kamar['harga_kamar'];
                                // Tampilkan data kamar dan atur elemen menjadi klikabel
                                echo '
                                    <div class="col-md-6 col-lg-3 col-xlg-3">
                                        <div class="card card-hover">
                                            <div class="p-2 bg-secondary text-center">
                                                <h2 class="font-light text-white">' . $nomor_kamar . '</h2>
                                                <h6 class="text-white">Rp. ' . number_format($harga_kamar, 0, ',', '.') . '</h6>
                                                <button class="small-box-footer" onclick="gotoFormCheckIn(' . $id_kamar . ')">Check-In <i class="fas fa-arrow-circle-right"></i></button>
                                            </div>
                                        </div>
                                    </div>';
                            }
                        } else {
                            // Jika tidak ada kamar tersedia, tampilkan pesan
                            echo '<div class="col-md-12">
                                    <div class="alert alert-info">
                                        Tidak ada kamar yang tersedia.
                                    </div>
                                </div>';
                        }
                        ?>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex align-items-center mb-4">
                                <div class="card-body collapse show">
                                    <h4 class="card-title">Reminder</h4>
                                    <p class="card-text">Apabila Kamar yang Dicari Tidak Tersedia dalam Daftar, Berarti Kamar Tersebut Tidak Tersedia.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function gotoFormCheckIn(id_kamar) {
        window.location.href = "index.php?page=form_check_in&id_kamar=" + id_kamar;
    }
</script>