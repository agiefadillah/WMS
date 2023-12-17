<header class="topbar" data-navbarbg="skin6">
    <nav class="navbar top-navbar navbar-expand-md">
        <div class="navbar-header" data-logobg="skin6">
            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>

            <div class="navbar-brand">
                <a href="index.php">
                    <b class="logo-icon">
                        <!-- Dark Logo icon -->
                        <img src="img/WMS_TopBar_Logo.png" width="25%" alt="homepage" class="dark-logo" />
                        <!-- Light Logo icon -->
                        <img src="img/WMS_TopBar_Logo.png" width="25%" alt="homepage" class="light-logo" />
                    </b>

                    <!-- Logo text -->
                    <span class="logo-text">
                        <!-- dark Logo text -->
                        <img src="img/WMS_TopBar_Text.png" width="35%" alt="homepage" class="dark-logo" />
                        <!-- Light Logo text -->
                        <img src="img/WMS_TopBar_Text.png" width="35%" class="light-logo" alt="homepage" />
                    </span>
                </a>
            </div>
            <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
        </div>

        <div class="navbar-collapse collapse" id="navbarSupportedContent">
            <ul class="navbar-nav float-left mr-auto ml-3 pl-1">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" onclick="keluar()">
                        <i data-feather="power" class="svg-icon mr-2 ml-1"></i>
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav float-right">

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="ml-2 d-none d-lg-inline-block"><span class="text-dark"><?php echo $_SESSION['username']; ?></span> <i data-feather="chevron-down" class="svg-icon"></i></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                        <!-- <a class="dropdown-item" href="javascript:void(0)"><i data-feather="user" class="svg-icon mr-2 ml-1"></i>
                            My Profile</a>
                        <a class="dropdown-item" href="javascript:void(0)"><i data-feather="credit-card" class="svg-icon mr-2 ml-1"></i>
                            My Balance</a>
                        <a class="dropdown-item" href="javascript:void(0)"><i data-feather="mail" class="svg-icon mr-2 ml-1"></i>
                            Inbox</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="javascript:void(0)"><i data-feather="settings" class="svg-icon mr-2 ml-1"></i>
                            Account Setting</a> -->
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" onclick="keluar()"><i data-feather="power" class="svg-icon mr-2 ml-1"></i>
                            Logout</a>
                        <div class="dropdown-divider"></div>
                        <!-- <div class="pl-4 p-3"><a href="javascript:void(0)" class="btn btn-sm btn-info">
                                View Profile</a></div> -->
                    </div>
                </li>

            </ul>
        </div>
    </nav>
</header>