<?php
if ($_SESSION['tipe'] != 'pemilik' && $_SESSION['tipe'] != 'penjaga') {
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
                                $cardkamar = mysqli_query($koneksi, "SELECT * FROM kamar WHERE status_kamar = 'Tersedia'");
                                $cardkamar = mysqli_num_rows($cardkamar);
                                echo $cardkamar;
                                ?>
                            </h2>
                        </div>
                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Kamar Tersedia</h6>
                    </div>
                    <div class="ml-auto mt-md-3 mt-lg-0">
                        <span class="opacity-7 text-muted"><i class="nav-icon fas fa-bed"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card border-right">
            <div class="card-body">
                <div class="d-flex d-lg-flex d-md-block align-items-center">
                    <div>
                        <h2 class="text-dark mb-1 font-weight-medium">
                            <?php
                            $cardkamar = mysqli_query($koneksi, "SELECT * FROM kamar WHERE status_kamar = 'Terisi'");
                            $cardkamar = mysqli_num_rows($cardkamar);
                            echo $cardkamar;
                            ?>
                        </h2>
                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Kamar Terisi</h6>
                    </div>
                    <div class="ml-auto mt-md-3 mt-lg-0">
                        <span class="opacity-7 text-muted"><i class="nav-icon fas fa-user"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card border-right">
            <div class="card-body">
                <div class="d-flex d-lg-flex d-md-block align-items-center">
                    <div>
                        <div class="d-inline-flex align-items-center">
                            <h2 class="text-dark mb-1 font-weight-medium">
                                <?php
                                $cardtagihanbl = mysqli_query($koneksi, "SELECT * FROM tagihan WHERE status_tagihan = 'Belum Bayar'");
                                $cardtagihanbl = mysqli_num_rows($cardtagihanbl);
                                echo $cardtagihanbl;
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
                            $cardtagihanbl = mysqli_query($koneksi, "SELECT * FROM tagihan WHERE status_tagihan = 'Belum Lunas'");
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