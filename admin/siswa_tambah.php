<?php
$tahun = [];
$ambil = $koneksi->query("SELECT * FROM tahun");
while ($tiap = $ambil->fetch_assoc()) {
    $tahun[] = $tiap;
}
?>

<h4>Tambah Data Siswa</h4>

<form method="post" enctype="multipart/form-data">

    <div class="mb-3">
        <label>Tahun Masuk</label>
        <select class="form-control" name="id_tahun" required>
            <option value="">Pilih</option>
            <?php foreach ($tahun as $t): ?>
                <option value="<?= $t['id_tahun'] ?>"><?= $t['tahun_ajaran'] ?></option>
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
        <input type="file" name="foto" class="form-control">
    </div>

    <div class="mb-3">
        <label>Alamat</label>
        <textarea name="alamat" class="form-control"></textarea>
    </div>

    <button class="btn btn-primary btn-sm" name="simpan">Simpan</button>
</form>

<?php
if (isset($_POST['simpan'])) {

    $id_tahun = $_POST['id_tahun'];
    $nis      = $_POST['nis'];
    $pass     = sha1($_POST['pass']);
    $nama     = $_POST['nama'];
    $alamat   = $_POST['alamat'];

    $foto_nama = null;

    if (!empty($_FILES['foto']['name'])) {
        $foto_nama = date("YmdHis") . "_" . basename($_FILES['foto']['name']);
        move_uploaded_file($_FILES['foto']['tmp_name'], "../assets/siswa/" . $foto_nama);
    }

    $koneksi->query("
        INSERT INTO siswa (id_tahun, induk_siswa, pw_siswa, nama_siswa, alamat_siswa, foto_siswa)
        VALUES ('$id_tahun','$nis','$pass','$nama','$alamat','$foto_nama')
    ");

    echo "<script>alert('Data siswa berhasil disimpan');location='index.php?halaman=siswa';</script>";
}
?>
