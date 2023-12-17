<!-- Sidebar Menu -->
<nav class="sidebar-nav">
    <ul id="sidebarnav">

        <!-- <li class="sidebar-item">
            <a class="sidebar-link sidebar-link" href="index.html" aria-expanded="false">
                <i data-feather="home" class="feather-icon"></i>
                <span class="hide-menu">
                    Dashboard
                </span>
            </a>
        </li> -->

        <li class="sidebar-item <?php echo $_GET['page'] == 'dashboard_admin' ? 'menu-open' : ''; ?>">
            <a class="sidebar-link sidebar-link <?php echo $_GET['page'] == 'dashboard' ? 'active' : ''; ?>" href="index.php?page=dashboard_admin" aria-expanded="false">
                <i data-feather="home" class="feather-icon"></i>
                <span class="hide-menu">
                    Beranda
                </span>
            </a>
        </li>

        <li class="list-divider"></li>
        <li class="nav-small-cap"><span class="hide-menu">Menu</span></li>

        <li class="sidebar-item <?php echo ($_GET['page'] == 'daftarkamar' || $_GET['page'] == 'tambah_kamar' || $_GET['page'] == 'ubah_kamar') ? 'menu-open' : ''; ?>">
            <a class="sidebar-link <?php echo ($_GET['page'] == 'daftarkamar' || $_GET['page'] == 'tambah_kamar' || $_GET['page'] == 'ubah_kamar') ? 'active' : ''; ?>" href="index.php?page=daftarkamar" aria-expanded="false">
                <i class="nav-icon fas fa-bed"></i>
                <span class="hide-menu">
                    Kamar
                </span>
            </a>
        </li>

        <li class="sidebar-item <?php echo $_GET['page'] == 'daftarpenghuni' ? 'menu-open' : ''; ?>">
            <a class="sidebar-link sidebar-link <?php echo $_GET['page'] == 'daftarpenghuni' ? 'active' : ''; ?>" href="index.php?page=daftarpenghuni">
                <i class="nav-icon fas fa-user"></i>
                <span class="hide-menu">
                    Penghuni
                </span>
            </a>
        </li>

        <li class="sidebar-item  <?php echo $_GET['page'] == 'pindahkamar' ? 'menu-open' : ''; ?>">
            <a class="sidebar-link sidebar-link <?php echo $_GET['page'] == 'pindahkamar' ? 'active' : ''; ?>" href="index.php?page=pindahkamar">
                <i class="nav-icon fa-solid fa-right-left"></i>
                <span class="hide-menu">
                    Pindah Kamar
                </span>
            </a>
        </li>


        <li class="sidebar-item  <?php echo $_GET['page'] == 'daftarkeluhan' ? 'menu-open' : ''; ?>">
            <a class="sidebar-link sidebar-link <?php echo $_GET['page'] == 'daftarkeluhan' ? 'active' : ''; ?>" href="index.php?page=daftarkeluhan">
                <i class="nav-icon fa fa-comments"></i>
                <span class="hide-menu">
                    Keluhan
                </span>
            </a>
        </li>




        <li class="sidebar-item <?php echo ($_GET['page'] == 'daftartamu' || $_GET['page'] == 'tambah_tamu' || $_GET['page'] == 'ubah_tamu') ? 'menu-open' : ''; ?>">
            <a class="sidebar-link <?php echo ($_GET['page'] == 'daftartamu' || $_GET['page'] == 'tambah_tamu' || $_GET['page'] == 'ubah_tamu') ? 'active' : ''; ?>" href="index.php?page=daftartamu" aria-expanded="false">
                <i class="nav-icon fa fa-people-arrows"></i>
                <span class="hide-menu">
                    Tamu
                </span>
            </a>
        </li>

        <li class="list-divider"></li>
        <li class="nav-small-cap"><span class="hide-menu">Keuangan</span></li>

        <li class="sidebar-item  <?php echo $_GET['page'] == 'daftartagihan' ? 'menu-open' : ''; ?>">
            <a class="sidebar-link sidebar-link <?php echo $_GET['page'] == 'daftartagihan' ? 'active' : ''; ?>" href="index.php?page=daftartagihan">
                <i class="nav-icon fa-solid fa-file-invoice"></i>
                <span class="hide-menu">
                    Tagihan
                </span>
            </a>
        </li>

        <li class="sidebar-item  <?php echo $_GET['page'] == 'laporan_keuangan' ? 'menu-open' : ''; ?>">
            <a class="sidebar-link sidebar-link <?php echo $_GET['page'] == 'laporan_keuangan' ? 'active' : ''; ?>" href="index.php?page=laporan_keuangan">
                <i class="nav-icon fa fa-rupiah-sign"></i>
                <span class="hide-menu">
                    Laporan Keuangan
                </span>
            </a>
        </li>

        <li class="sidebar-item  <?php echo $_GET['page'] == 'daftartransaksi' ? 'menu-open' : ''; ?>">
            <a class="sidebar-link sidebar-link <?php echo $_GET['page'] == 'daftartransaksi' ? 'active' : ''; ?>" href="index.php?page=daftartransaksi">
                <i class="nav-icon fa fa-money-bill"></i>
                <span class="hide-menu">
                    Transaksi
                </span>
            </a>
        </li>

        <li class="sidebar-item  <?php echo $_GET['page'] == 'daftarpengeluaran' ? 'menu-open' : ''; ?>">
            <a class="sidebar-link sidebar-link <?php echo $_GET['page'] == 'daftarpengeluaran' ? 'active' : ''; ?>" href="index.php?page=daftarpengeluaran">
                <i class="nav-icon fa fa-square-arrow-up-right"></i>
                <span class="hide-menu">
                    Pengeluaran
                </span>
            </a>
        </li>

        <li class="sidebar-item  <?php echo $_GET['page'] == 'daftarrekening' ? 'menu-open' : ''; ?>">
            <a class="sidebar-link sidebar-link <?php echo $_GET['page'] == 'daftarrekening' ? 'active' : ''; ?>" href="index.php?page=daftarrekening">
                <i class="nav-icon fa-regular fa-credit-card"></i>
                <span class="hide-menu">
                    Rekening
                </span>
            </a>
        </li>

        <li class="list-divider"></li>
        <li class="nav-small-cap"><span class="hide-menu">Pengaturan</span></li>

        <li class="sidebar-item  <?php echo $_GET['page'] == 'pengaturan' ? 'menu-open' : ''; ?>">
            <a class="sidebar-link sidebar-link <?php echo $_GET['page'] == 'pengaturan' ? 'active' : ''; ?>" href="index.php?page=pengaturan">
                <i class="nav-icon fa fa-gear"></i>
                <span class="hide-menu">
                    Akun
                </span>
            </a>
        </li>

        <li class="sidebar-item  <?php echo $_GET['page'] == 'tambah_lokasikamar' ? 'menu-open' : ''; ?>">
            <a class="sidebar-link sidebar-link <?php echo $_GET['page'] == 'tambah_lokasikamar' ? 'active' : ''; ?>" href="index.php?page=tambah_lokasikamar">
                <i class="nav-icon fa fa-location-pin"></i>
                <span class="hide-menu">
                    Lokasi Kamar
                </span>
            </a>
        </li>



        <li class="list-divider"></li>
        <li class="nav-small-cap"><span class="hide-menu">Logout</span></li>

        <li class="sidebar-item">
            <a class=" sidebar-link sidebar-link" onclick="keluar()">
                <i data-feather="power" class="svg-icon mr-2 ml-1"></i>
                <span class="hide-menu">
                    Logout
                </span>
            </a>
        </li>

    </ul>
</nav>