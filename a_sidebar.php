<aside class="left-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar" data-sidebarbg="skin6">
        <!-- Sidebar navigation-->
        <?php
        if (isset($_SESSION['id_users'])) {
            if ($_SESSION['tipe'] === 'pemilik') {
                include('pemilik/sidebar_pemilik.php');
            } else if ($_SESSION['tipe'] === 'staff') {
                include('staff/sidebar_staff.php');
            } else if ($_SESSION['tipe'] === 'penghuni') {
                include('penghuni/sidebar_penghuni.php');
            } else {
                include('error.php');
            }
        } else {
            include('error.php');
        }
        ?>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>