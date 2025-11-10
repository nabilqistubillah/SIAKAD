<?php
include '../config/config.php';

// pastikan parameter id dikirim
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // hapus data siswa berdasarkan id
    $hapus = $koneksi->query("DELETE FROM siswa WHERE id_siswa='$id'");

    if ($hapus) {
        echo "<script>alert('Data siswa berhasil dihapus');</script>";
        echo "<script>location='index.php?halaman=siswa';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data!');</script>";
        echo "<script>location='index.php?halaman=siswa';</script>";
    }
} else {
    echo "<script>alert('ID siswa tidak ditemukan!');</script>";
    echo "<script>location='index.php?halaman=siswa';</script>";
}
?>
