<nav class="sidebar-nav">
    <ul id="sidebarnav">
        <li class="sidebar-item <?php echo $_GET['page'] == 'dashboard_penghuni' ? 'menu-open' : ''; ?>">
            <a class="sidebar-link sidebar-link <?php echo $_GET['page'] == 'dashboard_penghuni' ? 'active' : ''; ?>" href="index.php?page=dashboard_penghuni" aria-expanded="false">
                <i data-feather="home" class="feather-icon"></i>
                <span class="hide-menu">
                    Beranda
                </span>
            </a>
        </li>

        <li class="list-divider"></li>

        <li class="sidebar-item <?php echo $_GET['page'] == 'daftar_tagihan_penghuni' ? 'menu-open' : ''; ?>">
            <a class="sidebar-link sidebar-link <?php echo $_GET['page'] == 'daftar_tagihan_penghuni' ? 'active' : ''; ?>" href="index.php?page=daftar_tagihan_penghuni" aria-expanded="false">
                <i class="nav-icon fa-solid fa-file-invoice"></i>
                <span class="hide-menu">
                    Tagihan
                </span>
            </a>
        </li>

        <li class="list-divider"></li>

        <li class="sidebar-item <?php echo $_GET['page'] == 'daftar_pembayaran_penghuni' ? 'menu-open' : ''; ?>">
            <a class="sidebar-link sidebar-link <?php echo $_GET['page'] == 'daftar_daftar_pembayaran_penghunitagihan_penghuni' ? 'active' : ''; ?>" href="index.php?page=daftar_pembayaran_penghuni" aria-expanded="false">
                <i class="fa-solid fa-money-bill"></i>
                <span class="hide-menu">
                    Pembayaran
                </span>
            </a>
        </li>

        <li class="list-divider"></li>

        <li class="sidebar-item <?php echo ($_GET['page'] == 'daftar_keluhan_penghuni' || $_GET['page'] == 'tambah_keluhan_penghuni' || $_GET['page'] == 'ubah_keluhan_penghuni') ? 'menu-open' : ''; ?>">
            <a class="sidebar-link <?php echo ($_GET['page'] == 'daftar_keluhan_penghuni' || $_GET['page'] == 'tambah_keluhan_penghuni' || $_GET['page'] == 'ubah_keluhan_penghuni') ? 'active' : ''; ?>" href="index.php?page=daftar_keluhan_penghuni" aria-expanded="false">
                <i class="nav-icon fa fa-comments"></i>
                <span class="hide-menu">
                    Keluhan
                </span>
            </a>
        </li>

        <li class="list-divider"></li>

        <li class="sidebar-item <?php echo $_GET['page'] == 'daftar_rekening_penghuni' ? 'menu-open' : ''; ?>">
            <a class="sidebar-link sidebar-link <?php echo $_GET['page'] == 'daftar_rekening_penghuni' ? 'active' : ''; ?>" href="index.php?page=daftar_rekening_penghuni" aria-expanded="false">
                <i class="nav-icon fa-regular fa-credit-card"></i>
                <span class="hide-menu">
                    Daftar Rekening
                </span>
            </a>
        </li>

        <li class="list-divider"></li>

        <li class="sidebar-item  <?php echo $_GET['page'] == 'akun' ? 'menu-open' : ''; ?>">
            <a class="sidebar-link sidebar-link <?php echo $_GET['page'] == 'akun' ? 'active' : ''; ?>" href="index.php?page=akun">
                <i class="nav-icon fa fa-gear"></i>
                <span class="hide-menu">
                    Akun
                </span>
            </a>
        </li>

        <li class="list-divider"></li>

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