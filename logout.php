<?php
session_start();

// Fungsi untuk melakukan logout
function logout()
{
    // Memastikan pengguna sudah login sebelum logout
    if (isset($_SESSION['id_users'])) {
        // Hapus semua data session
        session_unset();
        // Hapus cookie session
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        // Mengatur ulang session ID untuk mencegah session hijacking
        session_regenerate_id(true);
        // Hancurkan session
        session_destroy();
    }

    // Arahkan pengguna kembali ke halaman login
    header("Location: login.php");
    exit();
}

// Panggil fungsi logout
logout();
