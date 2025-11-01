<?php
$guru = array();


$ambil = $koneksi->query("SELECT * FROM tahun");
while($tiap = $ambil->fetch_assoc()){
    $tahun[] = $tiap;
}

?>

<h4>Tambah Data Siswa</h4>
<div class="row">
    <div class="col-">
        <form method="post" enctype="multipart/form-data">
            <div class="mb3">
                <label>Tahun Masuk</label>
                <select type="text" class="form-control" name="id_tahun" required>
                    <option value="">Pilih</option>

                    <?php foreach($tahun as $key => $value): ?>
                    <option value="<?php echo $value['id_tahun'] ?>">
                        <?php echo $value['tahun_ajaran'] ?>
                    </option>
                    <?php endforeach ?>
                    
                </select>
            </div> 
            <div class="mb-3">
                <label>NIS</label>
                <input type="text" name="nis" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="text" name="pass" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Foto</label>
                <input type="file" name="foto" class="form-control" >
            </div>
            <div class="mb-3">
                <label>Alamat</label>
                <textarea class="form-control" name="alamat"></textarea>
            </div>
            <button class="btn btn-primary btn-sm" name="simpan">Simpan</button>
        </form>
    </div>
</div>

<?php
if(isset($_POST['simpan']))
{
    $id_tahun = $_POST['id_tahun'];
    $nis = $_POST['nis'];
    $pass = sha1($_POST['pass']);
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $namafoto = $_FILES['foto']['name'];
    $lokasifoto = $_FILES['foto']['tmp_name'];

    $namafoto = date("YmdHis").$namafoto;

    move_uploaded_file($lokasifoto, "../assets/siswa/".$namafoto);

    $koneksi->query("INSERT INTO siswa (id_tahun,induk_siswa,pw_siswa,nama_siswa,alamat_siswa,foto_siswa)
        VALUE ('$id_tahun','$nis','$pass','$nama','$alamat','$namafoto')");

    echo "<script>alert('data tersimpan')</script>";
    echo "<script>location='index.php?halaman=siswa'</script>";
}
?>

