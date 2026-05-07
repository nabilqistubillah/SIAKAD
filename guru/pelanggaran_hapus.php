<?php
$id = $_GET['id'];
$id_siswa = $_GET['ids'];

$koneksi->query("DELETE FROM pelanggaran WHERE id_pelanggaran='$id'");
echo "<script>alert('Data pelanggaran telah dihapus');location='index.php?halaman=siswa_detail&id=$id_siswa';</script>";
?>
