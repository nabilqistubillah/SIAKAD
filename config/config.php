<?php

$host = "localhost";

if ($_SERVER['SERVER_NAME'] == "localhost") {
    // LOCAL
    $user = "root";
    $pass = "";
    $db   = "smk_siakad";
} else {
    // HOSTING
    $user = "u506781743_smkalmiftah";
    $pass = "smkalmiftahB123";
    $db   = "u506781743_smk_siakad";
}

$koneksi = new mysqli($host, $user, $pass, $db);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>