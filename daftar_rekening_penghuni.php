<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex align-items-center mb-4">
                        <h3 class="card-title">Data Rekening Pembayaran</h3>
                    </div>

                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered display no-wrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th class="text-center">Jenis Pembayaran</th>
                                    <th class="text-center">Nomor Rekening</th>
                                    <th class="text-center">Pemilik Rekening</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $rekening = query("SELECT * FROM rekening");

                                $i = 1;
                                foreach ($rekening as $r) :
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $i; ?></td>
                                        <td class="text-center"><?= $r['jenis_pembayaran']; ?></td>
                                        <td class="text-center"><?= $r['nomor_rekening']; ?></td>
                                        <td class="text-center"><?= $r['pemilik_rekening']; ?></td>
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