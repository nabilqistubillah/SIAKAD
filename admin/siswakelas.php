<?php
$id = $_GET['id'];

$ambil = $koneksi->query("SELECT * FROM kelas
    LEFT JOIN tahun ON kelas.id_tahun=tahun.id_tahun
    LEFT JOIN jurusan ON kelas.id_jurusan=jurusan.id_jurusan
    WHERE kelas.id_kelas='$id'");
$kelas = $ambil->fetch_assoc();


$siswakelas = array();
$ambil = $koneksi->query("SELECT * FROM siswakelas
 LEFT JOIN siswa ON siswakelas.id_siswa=siswa.id_siswa
 LEFT JOIN tahun ON siswa.id_tahun=tahun.id_tahun
 WHERE siswakelas.id_kelas = '$id'");
while ($tiap = $ambil->fetch_assoc()) {
    $siswakelas[] = $tiap;
}

?>

<h4>Data Siswa Kelas</h4>
<div class="row">
    <div class="col-6">
        <table class="table">
            <tr>
                <td>Tahun Ajaran</td>
                <td>: <?php echo $kelas['tahun_ajaran']; ?></td>
            </tr>
            <tr>
                <td>Jurusan</td>
                <td>: <?php echo $kelas['nama_jurusan']; ?></td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>: <?php echo $kelas['nama_kelas']; ?></td>
            </tr>
        </table>
    </div>
</div>

<?php
//siswa yang blm ada kelas
$nokelas = array();
$idt = $kelas['id_tahun'];
$ambil = $koneksi->query("
    SELECT * FROM siswa
    WHERE id_siswa NOT IN (SELECT id_siswa FROM siswakelas)
");
while ($tiap = $ambil->fetch_assoc()) {
    $nokelas[] = $tiap;
}
?>

<a href="" class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#modal-masukkan-siswa">Masukkan Siswa</a>
<div class="modal" id="modal-masukkan-siswa">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Masukkan Siswa ke Kelas > <?php echo $kelas['nama_kelas'] . " > " . $kelas['nama_jurusan'] . " > " . $kelas['tahun_ajaran'] ?></h5>
                <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <?php foreach ($nokelas as $key => $value): ?>

                        <div class="mb-1">
                            <input type="checkbox" name="id_siswa[]" value="<?php echo $value['id_siswa'] ?>"> <?php echo $value['induk_siswa'] . " " . $value['nama_siswa'] ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="simpan" class="btn btn-primary">Masukkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
if (isset($_POST['simpan'])) {
    $id_siswanya = $_POST['id_siswa'];
    $id_kelas = $_GET['id'];

    foreach ($id_siswanya as $key => $value) {
        $koneksi->query("INSERT INTO siswakelas (id_siswa, id_kelas) VALUES ('$value', '$id_kelas')");
    }
    echo "<script>alert('Siswa Berhasil dimasukkan ke kelas')</script>";
    echo "<script>location='index.php?halaman=siswakelas&id=$id_kelas'</script>";
}
?>

<table class="table">
    <thead>
        <tr>
            <th>No</th>
            <th>Tahun Masuk</th>
            <th>NIS</th>
            <th>Nama</th>
            <th>Opsi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($siswakelas as $key => $value): ?>
            <tr>
                <td><?php echo $key + 1 ?></td>
                <td><?php echo $value['tahun_ajaran'] ?></td>
                <td><?php echo $value['induk_siswa'] ?></td>
                <td><?php echo $value['nama_siswa'] ?></td>
                <td>
                    <a href="index.php?halaman=siswa_detail&id=<?php echo $value['id_siswa']; ?>"
                        class="btn btn-warning btn-sm">Detail</a>

                    <a href="index.php?halaman=siswa_hapus&id=<?php echo $value['id_siswakelas']; ?>&id_kelas=<?php echo $id; ?>" class="btn btn-outline-danger btn-sm">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if (empty($siswakelas)): ?>
    <div class="border border-primary p-3 mb-5">Belum Ada Siswa Di Kelas Ini</div>

<?php endif; ?>