<?php
date_default_timezone_set("Asia/Jakarta");

$server = "localhost";
$user = "root";
$password = "";
$db = "bismillah";

$koneksi = mysqli_connect($server, $user, $password, $db);

function query($query)
{
    global $koneksi;
    $result = mysqli_query($koneksi, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}
