<?php
include './config/config.php';

$id = $_GET['id'];
$ids = $_GET['ids'];

$koneksi->query("DELETE FROM pelanggaran WHERE id_pelanggaran='$id'");

echo "<script>alert('Data pelanggaran berhasil dihapus!');</script>";
echo "<script>location='index.php?halaman=siswa_detail&id=$ids';</script>";
?>
