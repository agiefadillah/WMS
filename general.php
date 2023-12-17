<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 mx-auto">
                            <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner" role="listbox">
                                    <div class="carousel-item"> <img class="img-fluid" src="img/BANNER5.PNG" alt="First slide"> </div>
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

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <h4 class="card-title mx-auto">Penghuni Terlama Wisma Mutiara Selaras</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table no-wrap v-middle mb-0">
                            <thead>
                            </thead>
                            <tbody>
                                <?php
                                $penghuni = query("SELECT 
                                users.nama AS `Nama Penghuni`, 
                                kamar.nomor_kamar AS `Nomor Kamar`, 
                                penghuni_in_house.tanggal_masuk_pih AS `Tanggal Masuk`,
                                DATEDIFF(CURRENT_DATE, penghuni_in_house.tanggal_masuk_pih) as `Total Menghuni` 
                                FROM penghuni_in_house 
                                JOIN penghuni ON penghuni_in_house.id_penghuni = penghuni.id_penghuni
                                JOIN users ON penghuni.id_users = users.id_users
                                JOIN kamar ON penghuni.id_kamar = kamar.id_kamar
                                ORDER BY `Total Menghuni` DESC
                                LIMIT 3");

                                $i = 1;
                                foreach ($penghuni as $p) :
                                ?>
                                    <tr>
                                        <td class="text-center">
                                            <a class="btn btn-dark rounded-circle btn-circle font-12 popover-item" href="javascript:void(0)" width="45" height="45"><?= $i; ?></a>
                                        </td>
                                        <td class="font-weight-medium text-dark">

                                            <div class="">
                                                <h5 class="text-dark mb-0 font-16 font-weight-medium">
                                                    <?= $p['Nama Penghuni']; ?></h5>
                                                <span class="text-muted font-14">
                                                    Kamar <?= $p['Nomor Kamar']; ?>
                                                    <br>
                                                    Tanggal Masuk: <?= $p['Tanggal Masuk']; ?>
                                                </span>
                                            </div>
                                        </td>

                                        <td class="font-weight-medium text-dark"><?= $p['Total Menghuni']; ?> Hari</td>
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

</div>