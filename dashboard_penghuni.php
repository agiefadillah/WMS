<?php
session_start();

// Ambil id user dari session
$id_users = $_SESSION['id_users'];

if ($_SESSION['tipe'] != 'penghuni') {
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
    <div class="card-group">
        <div class="card border-right">
            <div class="card-body">
                <div class="d-flex d-lg-flex d-md-block align-items-center">
                    <div>
                        <div class="d-inline-flex align-items-center">
                            <h2 class="text-dark mb-1 font-weight-medium">
                                <?php
                                $cardtagihanbb = mysqli_query($koneksi, "SELECT t.*
                                FROM tagihan t
                                INNER JOIN penghuni p ON t.id_penghuni = p.id_penghuni
                                WHERE t.status_tagihan = 'Belum Bayar' AND p.id_users = $id_users");
                                $cardtagihanbb = mysqli_num_rows($cardtagihanbb);
                                echo $cardtagihanbb;
                                ?>
                            </h2>
                        </div>
                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Tagihan Belum Bayar</h6>
                    </div>
                    <div class="ml-auto mt-md-3 mt-lg-0">
                        <span class="opacity-7 text-muted"><i class="fa fa-x"></i></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="d-flex d-lg-flex d-md-block align-items-center">
                    <div>
                        <h2 class="text-dark mb-1 font-weight-medium">
                            <?php
                            $cardtagihanbl = mysqli_query($koneksi, "SELECT t.*
                                        FROM tagihan t
                                        INNER JOIN penghuni p ON t.id_penghuni = p.id_penghuni
                                        WHERE t.status_tagihan = 'Belum Lunas' AND p.id_users = $id_users");
                            $cardtagihanbl = mysqli_num_rows($cardtagihanbl);
                            echo $cardtagihanbl;
                            ?>
                        </h2>
                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Tagihan Belum Lunas</h6>
                    </div>
                    <div class="ml-auto mt-md-3 mt-lg-0">
                        <span class="opacity-7 text-muted"><i class="fa fa-slash"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 mx-auto">
                            <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner" role="listbox">
                                    <div class="carousel-item active"> <img class="img-fluid" src="img/BANNER5.PNG" alt="First slide"> </div>
                                    <div class="carousel-item"> <img class="img-fluid" src="img/BANNER5.png" alt="Second slide"> </div>
                                    <div class="carousel-item"> <img class="img-fluid" src="img/BANNER5.png" alt="Third slide"> </div>
                                    <div class="carousel-item"> <img class="img-fluid" src="img/BANNER5.png" alt="Fourth slide"> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>