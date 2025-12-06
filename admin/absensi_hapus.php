<?php

$id_siswa = $_GET['id'];      
$id_absensi = $_GET['ids'];   

if (!$id_siswa || !$id_absensi) {
    die("Parameter tidak lengkap");
}

$koneksi->query("DELETE FROM absensi WHERE id_absensi='$id_absensi'");

echo "<script>alert('Data absensi berhasil dihapus');</script>";
echo "<script>location='index.php?halaman=siswa_detail&id=$id_siswa';</script>";
