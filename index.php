<?php
date_default_timezone_set("Asia/Jakarta");
session_start();
require 'config/function.php';


if (!isset($_SESSION['id_users'])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html dir="ltr" lang="en">

<!-- head -->
<?php include 'a_header.php'; ?>

<body>
    <!-- Preloader -->



    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">

        <!-- TopBar -->
        <?php include 'a_topbar.php'; ?>

        <!-- SideBar -->
        <?php include 'a_sidebar.php'; ?>

        <div class="page-wrapper">
            <?php include 'a_contentbar.php'; ?>

            <!-- Konten Utama -->
            <?php
            if (isset($_GET['page'])) {
                if ($_GET['page'] == 'general') {
                    include 'general.php';
                } else if ($_GET['page'] == 'dashboard_admin') {
                    include('dashboard_admin.php');
                } else if ($_GET['page'] == 'dashboard_penghuni') {
                    include('dashboard_penghuni.php');
                } else if ($_GET['page'] == 'akun') {
                    include('akun.php');
                } else if ($_GET['page'] == 'daftar_lokasi_kamar') {
                    include('daftar_lokasi_kamar.php');
                } else if ($_GET['page'] == 'ubah_lokasi_kamar') {
                    include('ubah_lokasi_kamar.php');
                } else if ($_GET['page'] == 'daftar_kamar') {
                    include('daftar_kamar.php');
                } else if ($_GET['page'] == 'tambah_kamar') {
                    include('tambah_kamar.php');
                } else if ($_GET['page'] == 'ubah_kamar') {
                    include('ubah_kamar.php');
                } else if ($_GET['page'] == 'daftar_penghuni') {
                    include('daftar_penghuni.php');
                } else if ($_GET['page'] == 'tambah_penghuni_tidak_aktif') {
                    include('tambah_penghuni_tidak_aktif.php');
                } else if ($_GET['page'] == 'ubah_penghuni_tidak_aktif') {
                    include('ubah_penghuni_tidak_aktif.php');
                } else if ($_GET['page'] == 'tambah_penghuni_aktif') {
                    include('tambah_penghuni_aktif.php');
                } else if ($_GET['page'] == 'ubah_penghuni_aktif') {
                    include('ubah_penghuni_aktif.php');
                } else if ($_GET['page'] == 'check_in') {
                    include('check_in.php');
                } else if ($_GET['page'] == 'form_check_in') {
                    include('form_check_in.php');
                } else if ($_GET['page'] == 'check_out') {
                    include('check_out.php');
                } else if ($_GET['page'] == 'form_check_out') {
                    include('form_check_out.php');
                } else if ($_GET['page'] == 'riwayat_check_out') {
                    include('riwayat_check_out.php');
                } else if ($_GET['page'] == 'ubah_check_out') {
                    include('ubah_check_out.php');
                } else if ($_GET['page'] == 'in_house') {
                    include('in_house.php');
                } else if ($_GET['page'] == 'daftar_keluhan') {
                    include('daftar_keluhan.php');
                } else if ($_GET['page'] == 'tambah_keluhan') {
                    include('tambah_keluhan.php');
                } else if ($_GET['page'] == 'ubah_keluhan') {
                    include('ubah_keluhan.php');
                } else if ($_GET['page'] == 'daftar_keluhan_penghuni') {
                    include('daftar_keluhan_penghuni.php');
                } else if ($_GET['page'] == 'tambah_keluhan_penghuni') {
                    include('tambah_keluhan_penghuni.php');
                } else if ($_GET['page'] == 'ubah_keluhan_penghuni') {
                    include('ubah_keluhan_penghuni.php');
                } else if ($_GET['page'] == 'daftar_rekening') {
                    include('daftar_rekening.php');
                } else if ($_GET['page'] == 'daftar_rekening_penghuni') {
                    include('daftar_rekening_penghuni.php');
                } else if ($_GET['page'] == 'tambah_rekening') {
                    include('tambah_rekening.php');
                } else if ($_GET['page'] == 'ubah_rekening') {
                    include('ubah_rekening.php');
                } else if ($_GET['page'] == 'daftar_pemasukan') {
                    include('daftar_pemasukan.php');
                } else if ($_GET['page'] == 'daftar_pengeluaran') {
                    include('daftar_pengeluaran.php');
                } else if ($_GET['page'] == 'tambah_pengeluaran') {
                    include('tambah_pengeluaran.php');
                } else if ($_GET['page'] == 'ubah_pengeluaran') {
                    include('ubah_pengeluaran.php');
                } else if ($_GET['page'] == 'daftar_kendaraan') {
                    include('daftar_kendaraan.php');
                } else if ($_GET['page'] == 'tambah_kendaraan') {
                    include('tambah_kendaraan.php');
                } else if ($_GET['page'] == 'ubah_kendaraan') {
                    include('ubah_kendaraan.php');
                } else if ($_GET['page'] == 'daftar_tagihan') {
                    include('daftar_tagihan.php');
                } else if ($_GET['page'] == 'tambah_tagihan') {
                    include('tambah_tagihan.php');
                } else if ($_GET['page'] == 'tambah_tagihan_tanda_jadi') {
                    include('tambah_tagihan_tanda_jadi.php');
                } else if ($_GET['page'] == 'ubah_tagihan_kamar') {
                    include('ubah_tagihan_kamar.php');
                } else if ($_GET['page'] == 'ubah_tagihan_kendaraan') {
                    include('ubah_tagihan_kendaraan.php');
                } else if ($_GET['page'] == 'ubah_tagihan_lainnya') {
                    include('ubah_tagihan_lainnya.php');
                } else if ($_GET['page'] == 'ubah_tagihan_tamu') {
                    include('ubah_tagihan_tamu.php');
                } else if ($_GET['page'] == 'ubah_tagihan_belum_lunas') {
                    include('ubah_tagihan_belum_lunas.php');
                } else if ($_GET['page'] == 'pindah_kamar') {
                    include('pindah_kamar.php');
                } else if ($_GET['page'] == 'daftar_tamu') {
                    include('daftar_tamu.php');
                } else if ($_GET['page'] == 'tambah_tamu') {
                    include('tambah_tamu.php');
                } else if ($_GET['page'] == 'ubah_tamu') {
                    include('ubah_tamu.php');
                } else if ($_GET['page'] == 'laporan_keuangan') {
                    include('laporan_keuangan.php');
                } else if ($_GET['page'] == 'daftar_keluhan_penghuni') {
                    include('daftar_keluhan_penghuni.php');
                } else if ($_GET['page'] == 'tambah_keluhan_penghuni') {
                    include('tambah_keluhan_penghuni.php');
                } else if ($_GET['page'] == 'ubah_keluhan_penghuni') {
                    include('ubah_keluhan_penghuni.php');
                } else if ($_GET['page'] == 'daftar_pembayaran') {
                    include('daftar_pembayaran.php');
                } else if ($_GET['page'] == 'daftar_pembayaran_penghuni') {
                    include('daftar_pembayaran_penghuni.php');
                } else if ($_GET['page'] == 'daftar_tagihan_penghuni') {
                    include('daftar_tagihan_penghuni.php');
                } else if ($_GET['page'] == 'bayar_tagihan_penghuni') {
                    include('bayar_tagihan_penghuni.php');
                } else {
                    // Halaman tidak ditemukan 
                    include 'not_found.php';
                }
            } else {
                // Jika tidak ada parameter page, tampilkan halaman general secara default
                include 'general.php';
            }
            ?>
        </div>

    </div>

    <?php include 'a_footer.php'; ?>
</body>

</html>