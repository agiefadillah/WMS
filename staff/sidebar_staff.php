<aside class="left-sidebar" data-sidebarbg="skin6">

    <div class="scroll-sidebar" data-sidebarbg="skin6">

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

                <li class="sidebar-item <?php echo $_GET['page'] == 'check_in' ? 'menu-open' : ''; ?>">
                    <a class="sidebar-link sidebar-link <?php echo $_GET['page'] == 'check_in' ? 'active' : ''; ?>" href="index.php?page=check_in" aria-expanded="false">
                        <i class="fa-solid fa-person-arrow-down-to-line"></i>
                        <span class="hide-menu">
                            Check-In
                        </span>
                    </a>
                </li>

                <li class="sidebar-item <?php echo $_GET['page'] == 'check_out' ? 'menu-open' : ''; ?>">
                    <a class="sidebar-link sidebar-link <?php echo $_GET['page'] == 'check_out' ? 'active' : ''; ?>" href="index.php?page=check_out" aria-expanded="false">
                        <i class="fa-solid fa-person-arrow-up-from-line"></i>
                        <span class="hide-menu">
                            Check-Out
                        </span>
                    </a>
                </li>

                <li class="sidebar-item <?php echo $_GET['page'] == 'in_house' ? 'menu-open' : ''; ?>">
                    <a class="sidebar-link sidebar-link <?php echo $_GET['page'] == 'in_house' ? 'active' : ''; ?>" href="index.php?page=in_house" aria-expanded="false">
                        <i class="fa-solid fa-person-shelter"></i>
                        <span class="hide-menu">
                            In House
                        </span>
                    </a>
                </li>
                <li class="sidebar-item <?php echo $_GET['page'] == 'daftar_penghuni' ? 'menu-open' : ''; ?>">
                    <a class="sidebar-link sidebar-link <?php echo $_GET['page'] == 'daftar_penghuni' ? 'active' : ''; ?>" href="index.php?page=daftar_penghuni">
                        <i class="nav-icon fas fa-user"></i>
                        <span class="hide-menu">
                            Penghuni
                        </span>
                    </a>
                </li>

                <li class="list-divider"></li>
                <!-- <li class="nav-small-cap"><span class="hide-menu">Menu</span></li> -->

                <li class="sidebar-item  <?php echo $_GET['page'] == 'daftar_lokasi_kamar' ? 'menu-open' : ''; ?>">
                    <a class="sidebar-link sidebar-link <?php echo $_GET['page'] == 'daftar_lokasi_kamar' ? 'active' : ''; ?>" href="index.php?page=daftar_lokasi_kamar">
                        <i class="nav-icon fa fa-location-pin"></i>
                        <span class="hide-menu">
                            Lokasi Kamar
                        </span>
                    </a>
                </li>

                <li class="sidebar-item <?php echo ($_GET['page'] == 'daftar_kamar' || $_GET['page'] == 'tambah_kamar' || $_GET['page'] == 'ubah_kamar') ? 'menu-open' : ''; ?>">
                    <a class="sidebar-link <?php echo ($_GET['page'] == 'daftar_kamar' || $_GET['page'] == 'tambah_kamar' || $_GET['page'] == 'ubah_kamar') ? 'active' : ''; ?>" href="index.php?page=daftar_kamar" aria-expanded="false">
                        <i class="nav-icon fas fa-bed"></i>
                        <span class="hide-menu">
                            Kamar
                        </span>
                    </a>
                </li>


                <li class="list-divider"></li>

                <li class="sidebar-item  <?php echo $_GET['page'] == 'daftar_keluhan' ? 'menu-open' : ''; ?>">
                    <a class="sidebar-link sidebar-link <?php echo $_GET['page'] == 'daftar_keluhan' ? 'active' : ''; ?>" href="index.php?page=daftar_keluhan">
                        <i class="nav-icon fa fa-comments"></i>
                        <span class="hide-menu">
                            Keluhan
                        </span>
                    </a>
                </li>

                <li class="sidebar-item  <?php echo $_GET['page'] == 'pindah_kamar' ? 'menu-open' : ''; ?>">
                    <a class="sidebar-link sidebar-link <?php echo $_GET['page'] == 'pindah_kamar' ? 'active' : ''; ?>" href="index.php?page=pindah_kamar">
                        <i class="nav-icon fa-solid fa-right-left"></i>
                        <span class="hide-menu">
                            Pindah Kamar
                        </span>
                    </a>
                </li>

                <li class="sidebar-item <?php echo ($_GET['page'] == 'daftar_tamu' || $_GET['page'] == 'tambah_tamu' || $_GET['page'] == 'ubah_tamu') ? 'menu-open' : ''; ?>">
                    <a class="sidebar-link <?php echo ($_GET['page'] == 'daftar_tamu' || $_GET['page'] == 'tambah_tamu' || $_GET['page'] == 'ubah_tamu') ? 'active' : ''; ?>" href="index.php?page=daftar_tamu" aria-expanded="false">
                        <i class="nav-icon fa fa-people-arrows"></i>
                        <span class="hide-menu">
                            Tamu
                        </span>
                    </a>
                </li>

                <li class="sidebar-item <?php echo ($_GET['page'] == 'daftar_kendaraan' || $_GET['page'] == 'tambah_kendaraan' || $_GET['page'] == 'ubah_kendaraan') ? 'menu-open' : ''; ?>">
                    <a class="sidebar-link <?php echo ($_GET['page'] == 'daftar_kendaraan' || $_GET['page'] == 'tambah_kendaraan' || $_GET['page'] == 'ubah_kendaraan') ? 'active' : ''; ?>" href="index.php?page=daftar_kendaraan" aria-expanded="false">
                        <i class="nav-icon fa fa-solid fa-car-side"></i>
                        <span class="hide-menu">
                            Kendaraan
                        </span>
                    </a>
                </li>

                <li class="list-divider"></li>

                <li class="sidebar-item <?php echo ($_GET['page'] == 'daftar_tagihan' || $_GET['page'] == 'tambah_tagihan' || $_GET['page'] == 'ubah_tagihan_kamar' || $_GET['page'] == 'ubah_tagihan_kendaraan' || $_GET['page'] == 'ubah_tagihan_lainnya') ? 'menu-open' : ''; ?>">
                    <a class="sidebar-link <?php echo ($_GET['page'] == 'daftar_tagihan' || $_GET['page'] == 'tambah_tagihan' || $_GET['page'] == 'ubah_tagihan_kamar' || $_GET['page'] == 'ubah_tagihan_kamar' || $_GET['page'] == 'ubah_tagihan_kendaraan' || $_GET['page'] == 'ubah_tagihan_lainnya') ? 'active' : ''; ?>" href="index.php?page=daftar_tagihan" aria-expanded="false">
                        <i class="nav-icon fa-solid fa-file-invoice"></i>
                        <span class="hide-menu">
                            Tagihan
                        </span>
                    </a>
                </li>

                <li class="sidebar-item  <?php echo $_GET['page'] == 'daftar_pengeluaran' ? 'menu-open' : ''; ?>">
                    <a class="sidebar-link sidebar-link <?php echo $_GET['page'] == 'daftar_pengeluaran' ? 'active' : ''; ?>" href="index.php?page=daftar_pengeluaran">
                        <i class="nav-icon fa fa-square-arrow-up-right"></i>
                        <span class="hide-menu">
                            Pengeluaran
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

                <li class="sidebar-item  <?php echo $_GET['page'] == 'daftar_rekening' ? 'menu-open' : ''; ?>">
                    <a class="sidebar-link sidebar-link <?php echo $_GET['page'] == 'daftar_rekening' ? 'active' : ''; ?>" href="index.php?page=daftar_rekening">
                        <i class="nav-icon fa-regular fa-credit-card"></i>
                        <span class="hide-menu">
                            Rekening
                        </span>
                    </a>
                </li>


                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">Pengaturan</span></li>

                <li class="sidebar-item  <?php echo $_GET['page'] == 'akun' ? 'menu-open' : ''; ?>">
                    <a class="sidebar-link sidebar-link <?php echo $_GET['page'] == 'akun' ? 'active' : ''; ?>" href="index.php?page=akun">
                        <i class="nav-icon fa fa-gear"></i>
                        <span class="hide-menu">
                            Akun
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

    </div>

</aside>