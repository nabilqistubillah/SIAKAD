<?php
$tahun = array();


$ambil = $koneksi->query("SELECT * FROM tahun");
while($tiap = $ambil->fetch_assoc()){
    $tahun[] = $tiap;
}

$jurusan = array();

$ambil = $koneksi->query("SELECT * FROM jurusan");
while($tiap = $ambil->fetch_assoc()){
    $jurusan[] = $tiap;
}

?>

<h5>Tambah Kelas</h5>
<div class="row">
    <div class="col-6">
        <form method="post">
            <div class="mb-3">
                <label>Tahun Ajaran</label>
                <select class="form-control" name="id_tahun" >
                    <option value="">Pilih Tahun</option>
                    <?php foreach($tahun as $key => $value): ?>
                    <option value="<?php echo$value ['id_tahun'] ?>">
                        <?php echo $value['tahun_ajaran']?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Jurusan</label>
                <select class="form-control" name="id_jurusan" >
                    <option value="">Pilih Jurusan</option>
                    <?php foreach ($jurusan as $key => $value): ?>
                    <option value="<?php echo $value['id_jurusan'] ?>">
                        <?php echo $value['nama_jurusan']?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Nama Kelas</label>
                <input type="text" class="form-control" name="nama_kelas">
            </div>
            <div class="mb-3">
                <label>Jenjang Kelas</label>
                <select class="form-control" name="jenjang_kelas" >
                    <option value=""></option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                </select>
            </div>
            <button class="btn btn-primary btn-sm" name="simpan">Simpan</button>
        </form>
    </div>
</div>

<?php
if (isset($_POST['simpan'])) 
{
    $id_tahun = $_POST['id_tahun'];
    $id_jurusan = $_POST['id_jurusan'];
    $nama_kelas = $_POST['nama_kelas'];
    $jenjang_kelas = $_POST['jenjang_kelas'];

    $koneksi->query("INSERT INTO kelas (id_tahun, id_jurusan, nama_kelas, jenjang_kelas) 
    VALUES ('$id_tahun', '$id_jurusan', '$nama_kelas', '$jenjang_kelas')");


    echo "<script>alert('data tersimpan')</script>";
    echo "<script>location='index.php?halaman=kelas'</script>";
}
?>