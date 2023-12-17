<footer class="footer text-center text-muted">
    Wisma Mutiara Selaras &copy; <?php echo date('Y'); ?>
</footer>

<script src="app/assets/libs/jquery/dist/jquery.min.js"></script>
<script src="app/assets/libs/popper.js/dist/umd/popper.min.js"></script>
<script src="app/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- apps -->
<!-- apps -->
<script src="app/dist/js/app-style-switcher.js"></script>
<script src="app/dist/js/feather.min.js"></script>
<script src="app/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
<script src="app/dist/js/sidebarmenu.js"></script>
<!--Custom JavaScript -->
<script src="app/dist/js/custom.min.js"></script>
<!--This page JavaScript -->
<script src="app/assets/extra-libs/c3/d3.min.js"></script>
<script src="app/assets/extra-libs/c3/c3.min.js"></script>
<script src="app/assets/libs/chartist/dist/chartist.min.js"></script>
<script src="app/assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>
<script src="app/assets/extra-libs/jvector/jquery-jvectormap-2.0.2.min.js"></script>
<script src="app/assets/extra-libs/jvector/jquery-jvectormap-world-mill-en.js"></script>
<script src="app/dist/js/pages/dashboards/dashboard1.min.js"></script>


<!-- Table -->
<script src="app/assets/extra-libs/sparkline/sparkline.js"></script>
<script src="app/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="app/dist/js/pages/datatable/datatable-basic.init.js"></script>

<!-- Button -->
<script src="app/assets/extra-libs/prism/prism.js"></script>

<!-- Sweet Alert -->
<script src="app/plugins/sweetalert2/sweetalert2.all.min.js"></script>

<!-- ------------------------------------------------------------- -->

<!-- Keluar -->
<script>
    function keluar(data) {
        Swal.fire({
            title: 'Apakah Anda Ingin Keluar?',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            cancelButtonColor: '#063970',
            confirmButtonText: 'Logout',
            confirmButtonColor: 'red',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "logout.php";
            }
        })
    }
</script>

<!-- Lokasi Kamar -->
<script>
    function ubahlokasikamar(data) {
        window.location.href = "index.php?page=ubah_lokasi_kamar&id_lokasi=" + data;
    }

    function hapuslokasikamar(data) {
        Swal.fire({
            title: 'Apakah Anda Ingin Menghapus Data?',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            cancelButtonColor: '#063970',
            confirmButtonText: 'Hapus',
            confirmButtonColor: 'red',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "config/hapusdata.php?id_lokasi=" + data;
            }
        })
    }
</script>

<!-- Kamar -->
<script>
    function ubahkamar(data) {
        window.location.href = "index.php?page=ubah_kamar&id_kamar=" + data;
    }

    function hapuskamar(data) {
        Swal.fire({
            title: 'Apakah Anda Ingin Menghapus Data?',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            cancelButtonColor: '#063970',
            confirmButtonText: 'Hapus',
            confirmButtonColor: 'red',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "config/hapusdata.php?id_kamar=" + data;
            }
        })
    }
</script>

<!-- Penghuni -->
<script>
    function KonfirmasiTambahPenghuni() {
        Swal.fire({
            title: '<span style="font-weight: normal;">Apakah Penghuni Ingin Langsung Menempati Kamar?</span>',
            showDenyButton: true,
            // showCancelButton: true,
            showCloseButton: true,

            confirmButtonText: 'Ya',
            denyButtonText: 'Tidak',
            // cancelButtonText: 'Tutup',
            denyButtonColor: '#063970',
            confirmButtonColor: 'green',
            reverseButtons: false,
            focusCancel: true,
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "index.php?page=tambah_penghuni_aktif";
            } else if (result.isDenied) {
                window.location.href = "index.php?page=tambah_penghuni_tidak_aktif";
            }
        })
    }

    function ubahpenghuni(data, status_penghuni) {
        if (status_penghuni === 'aktif') {
            window.location.href = "index.php?page=ubah_penghuni_aktif&id_penghuni=" + data;
        } else if (status_penghuni === 'tidak aktif') {
            window.location.href = "index.php?page=ubah_penghuni_tidak_aktif&id_penghuni=" + data;
        } else {
            // Tindakan lain sesuai dengan kebutuhan Anda
        }
    }

    function hapuspenghuni(data) {
        Swal.fire({
            title: 'Apakah Anda Ingin Menghapus Data?',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            cancelButtonColor: '#063970',
            confirmButtonText: 'Hapus',
            confirmButtonColor: 'red',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "config/hapusdata.php?id_penghuni=" + data;
            }
        })
    }
</script>

<!-- Rekening -->
<script>
    function ubahrekening(data) {
        window.location.href = "index.php?page=ubah_rekening&id_rekening=" + data;
    }

    function hapusrekening(data) {
        Swal.fire({
            title: 'Apakah Anda Ingin Menghapus Data?',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            cancelButtonColor: '#063970',
            confirmButtonText: 'Hapus',
            confirmButtonColor: 'red',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "config/hapusdata.php?id_rekening=" + data;
            }
        })
    }
</script>

<!-- Pengeluaran -->
<script>
    function ubahpengeluaran(data) {
        window.location.href = "index.php?page=ubah_pengeluaran&id_pengeluaran=" + data;
    }

    function hapuspengeluaran(data) {
        Swal.fire({
            title: 'Apakah Anda Ingin Menghapus Data?',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            cancelButtonColor: '#063970',
            confirmButtonText: 'Hapus',
            confirmButtonColor: 'red',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "config/hapusdata.php?id_pengeluaran=" + data;
            }
        })
    }
</script>

<!-- Kendaraan -->
<script>
    function ubahkendaraan(data) {
        window.location.href = "index.php?page=ubah_kendaraan&id_kendaraan=" + data;
    }

    function hapusKendaraan(data) {
        Swal.fire({
            title: 'Apakah Anda Ingin Menghapus Data?',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            cancelButtonColor: '#063970',
            confirmButtonText: 'Hapus',
            confirmButtonColor: 'red',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "config/hapusdata.php?id_kendaraan=" + data;
            }
        })
    }
</script>

<!-- Tagihan -->
<script>
    function ubahtagihan(data, kategori_tagihan) {
        if (kategori_tagihan === 'sewa_kamar') {
            window.location.href = "index.php?page=ubah_tagihan_kamar&id_tagihan=" + data;
        } else if (kategori_tagihan === 'sewa_kendaraan') {
            window.location.href = "index.php?page=ubah_tagihan_kendaraan&id_tagihan=" + data;
        } else if (kategori_tagihan === 'sewa_lainnya') {
            window.location.href = "index.php?page=ubah_tagihan_lainnya&id_tagihan=" + data;
        } else if (kategori_tagihan === 'sewa_tamu') {
            window.location.href = "index.php?page=ubah_tagihan_tamu&id_tagihan=" + data;
        } else {
            // Tindakan lain sesuai dengan kebutuhan Anda
        }
    }

    function hapustagihan(data) {
        Swal.fire({
            title: 'Apakah Anda Ingin Menghapus Data?',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            cancelButtonColor: '#063970',
            confirmButtonText: 'Hapus',
            confirmButtonColor: 'red',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "config/hapusdata.php?id_tagihan=" + data;
            }
        })
    }

    function statusbelumlunas(data) {
        window.location.href = "index.php?page=ubah_tagihan_belum_lunas&id_tagihan=" + data;
    }
</script>

<!-- Tamu -->
<script>
    function ubahtamu(data) {
        window.location.href = "index.php?page=ubah_tamu&id_tamu=" + data;
    }

    function hapustamu(data) {
        Swal.fire({
            title: 'Apakah Anda Ingin Menghapus Data?',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            cancelButtonColor: '#063970',
            confirmButtonText: 'Hapus',
            confirmButtonColor: 'red',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "config/hapusdata.php?id_tamu=" + data;
            }
        })
    }
</script>

<!-- Check-Out -->
<script>
    function RiwayatCheckOut(data) {
        window.location.href = "index.php?page=riwayat_check_out";
    }

    function ubahcheckout(data) {
        window.location.href = "index.php?page=ubah_check_out&id_checkout=" + data;
    }
</script>

<!-- Keluhan -->
<script>
    function ubahkeluhan_penghuni(data) {
        window.location.href = "index.php?page=ubah_keluhan_penghuni&id_keluhan=" + data;
    }

    function hapusKeluhan(data) {
        Swal.fire({
            title: 'Apakah Anda Ingin Menghapus Data?',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            cancelButtonColor: '#063970',
            confirmButtonText: 'Hapus',
            confirmButtonColor: 'red',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "config/hapusdata.php?id_keluhan=" + data;
            }
        })
    }
</script>