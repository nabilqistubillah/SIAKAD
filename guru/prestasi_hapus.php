<?php
$id = $_GET['id'];
$id_siswa = $_GET['ids'];

$koneksi->query("DELETE FROM prestasi WHERE id_prestasi='$id'");
echo "<script>alert('Data prestasi telah dihapus');location='index.php?halaman=siswa_detail&id=$id_siswa';</script>";
?>
