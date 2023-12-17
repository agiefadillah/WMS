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
                    <div class="text-center">
                        <div class="d-flex flex-column flex-md-row justify-content-center">
                            <button type="button" class="btn btn-primary mb-2 mb-md-0 mr-md-2" onclick="RiwayatCheckOut()">Riwayat Check-Out</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="card-title">Check-Out</h3>
                    <h6>
                        <span>Pilih Kamar</span>
                    </h6>

                    <div class="row">
                        <?php
                        // Ambil data kamar yang statusnya tersedia dari database dan urutkan berdasarkan nomor_kamar
                        $query_kamar = "SELECT k.*, u.nama AS nama_penghuni
                        FROM kamar k
                        LEFT JOIN penghuni p ON k.id_kamar = p.id_kamar
                        LEFT JOIN users u ON p.id_users = u.id_users
                        WHERE k.status_kamar = 'Terisi'
                        ORDER BY k.nomor_kamar";
                        $result_kamar = mysqli_query($koneksi, $query_kamar);

                        // Cek apakah ada kamar yang tersedia
                        if (mysqli_num_rows($result_kamar) > 0) {
                            while ($row_kamar = mysqli_fetch_assoc($result_kamar)) {
                                $id_kamar = $row_kamar['id_kamar'];
                                $nomor_kamar = $row_kamar['nomor_kamar'];
                                $harga_kamar = $row_kamar['harga_kamar'];
                                $nama_penghuni = $row_kamar['nama_penghuni'];

                                // Tampilkan data kamar dan atur elemen menjadi klikabel
                                echo '
                                <div class="col-md-6 col-lg-3 col-xlg-3">
                                    <div class="card card-hover">
                                        <div class="p-2 bg-light text-center">
                                            <h2 class="font-light text-dark">' . $nomor_kamar . '</h2>
                                            <h6 class="text-dark">Rp. ' . number_format($harga_kamar, 0, ',', '.') . '</h6>
                                            <p class="text-dark">' . ($nama_penghuni ? $nama_penghuni : 'Belum Ada Penghuni') . '</p>
                                            <button class="small-box-footer" onclick="gotoFormCheckOut(' . $id_kamar . ')"><i class="fas fa-arrow-circle-left"></i> Check-Out</button>
                                        </div>
                                    </div>
                                </div>';
                            }
                        } else {
                            // Jika tidak ada kamar tersedia, tampilkan pesan
                            echo '<div class="col-md-12">
                                    <div class="alert alert-info">
                                        -
                                    </div>
                                </div>';
                        }
                        ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function gotoFormCheckOut(id_kamar) {
        window.location.href = "index.php?page=form_check_out&id_kamar=" + id_kamar;
    }
</script>