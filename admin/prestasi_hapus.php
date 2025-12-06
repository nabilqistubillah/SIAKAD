<?php
include './config/config.php';

$id = $_GET['id'];
$ids = $_GET['ids'];

$koneksi->query("DELETE FROM prestasi WHERE id_prestasi='$id'");

echo "<script>alert('Data prestasi berhasil dihapus!');</script>";
echo "<script>location='index.php?halaman=siswa_detail&id=$ids';</script>";
?>
