<?php
include './config/config.php';
$id = $_GET['id']; // id_siswakelas
$id_kelas = $_GET['id_kelas'];

$koneksi->query("DELETE FROM siswakelas WHERE id_siswakelas='$id'");
echo "<script>alert('Data berhasil dihapus');</script>";
echo "<script>location='index.php?halaman=siswakelas&id=$id_kelas';</script>";
?>