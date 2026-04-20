<?php
// Pastikan ID ada
if (isset($_GET['id'])) {
    $id_mapel = $_GET['id'];
    
    // Hapus data dari tabel pelajaran
    $koneksi->query("DELETE FROM mapel WHERE id_mapel = '$id_mapel'");

    // Catatan: Jika ingin lebih lengkap, bisa ditambahkan query untuk menghapus
    // data dari tabel 'mengajar' yang berkaitan ke 'id_mapel' ini.
    // $koneksi->query("DELETE FROM mengajar WHERE id_mapel = '$id_mapel'");

    echo "<script>alert('Mata Pelajaran berhasil dihapus');</script>";
}
echo "<script>location='index.php?halaman=mapel';</script>";
?>
