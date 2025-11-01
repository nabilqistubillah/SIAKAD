<?php
include_once '../config/config.php';
?>

<div class="row">
    <div class="col-6">
        <h4>Tambah Guru</h4>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label>NIP</label>
                <input type="text" class="form-control" name="nip">
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="text" class="form-control" name="password" required>
            </div>
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" class="form-control" name="nama">
            </div>
            <div class="mb-3">
                <label>Jenis Kelamin</label>
                <select class="form-control" name="jk">
                    <option value="">Pilih</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Alamat</label>
                <textarea class="form-control" name="alamat"></textarea>
            </div>
            <div class="mb-3">
                <label>Foto</label>
                <input type="file" class="form-control" name="foto">
            </div>
            <button class="btn btn-primary btn-sm" name="simpan">Simpan</button>
        </form>
    </div>
</div>

<?php 
if (isset($_POST['simpan'])) 
    {
    $namafoto = $_FILES['foto']['name'];
    $lokasifoto = $_FILES['foto']['tmp_name'];

    $namafoto = date("YmdHis").$namafoto;

    move_uploaded_file($lokasifoto, "/SIAKAD/assets/guru/".$namafoto);

    $ps = sha1($_POST['password']);
    $nip = $_POST['nip'];
    $nama = $_POST['nama'];
    $jk = $_POST['jk'];
    $alamat = $_POST['alamat'];
    
    $koneksi->query("INSERT INTO guru (induk_guru,pw_guru,nama_guru,kelamin_guru,alamat_guru,foto_guru) 
        VALUES ('$nip','$ps','$nama','$jk','$alamat','$namafoto')");

    echo "<script>alert('data tersimpan')</script>";
    echo "<script>location='index.php?halaman=guru'</script>";
} ?>