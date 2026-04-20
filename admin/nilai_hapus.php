<?php

$id_siswa = $_GET['ids'];
$id_nilai = $_GET['id'];

if (!$id_siswa || !$id_nilai) {
    die("Parameter tidak lengkap");
}

$koneksi->query("DELETE FROM nilai WHERE id_nilai='$id_nilai'");

echo "<script>alert('Data nilai berhasil dihapus');</script>";
echo "<script>location='index.php?halaman=siswa_detail&id=$id_siswa';</script>";
