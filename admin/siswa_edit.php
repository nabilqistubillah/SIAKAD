<?php
$id = $_GET['id'];

$siswa = $koneksi->query("
    SELECT * FROM siswa 
    WHERE id_siswa='$id'
")->fetch_assoc();

// ambil tahun ajaran
$tahun = [];
$q = $koneksi->query("SELECT * FROM tahun");
while ($t = $q->fetch_assoc()) {
    $tahun[] = $t;
}

// ambil kelas siswa sekarang
$kelas = $koneksi->query("
    SELECT sk.id_kelas, k.nama_kelas 
    FROM siswakelas sk
    JOIN kelas k ON sk.id_kelas = k.id_kelas
    WHERE sk.id_siswa='$id'
")->fetch_assoc();

// ambil semua kelas
$daftar_kelas = [];
$q2 = $koneksi->query("SELECT * FROM kelas");
while ($kl = $q2->fetch_assoc()) {
    $daftar_kelas[] = $kl;
}
?>

<h4>Edit Data Siswa</h4>

<form method="post" enctype="multipart/form-data">

    <div class="mb-3">
        <label>Tahun Masuk</label>
        <select name="id_tahun" class="form-control" required>
            <?php foreach ($tahun as $t): ?>
                <option value="<?= $t['id_tahun']; ?>"
                    <?= $t['id_tahun'] == $siswa['id_tahun'] ? 'selected' : '' ?>>
                    <?= $t['tahun_ajaran']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>NIS</label>
        <input type="text" name="nis" value="<?= $siswa['induk_siswa']; ?>" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama" value="<?= $siswa['nama_siswa']; ?>" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Alamat</label>
        <textarea name="alamat" class="form-control"><?= $siswa['alamat_siswa']; ?></textarea>
    </div>

    <div class="mb-3">
        <label>Foto (biarkan kosong jika tidak diganti)</label><br>

        <?php if (!empty($siswa['foto_siswa'])): ?>
            <img src="assets/siswa/<?= $siswa['foto_siswa']; ?>" 
                 style="width:120px;height:150px;object-fit:cover;" class="mb-2">
        <?php endif; ?>

        <input type="file" name="foto" class="form-control">
    </div>

    <div class="mb-3">
        <label>Kelas</label>
        <select name="id_kelas" class="form-control">
            <?php foreach ($daftar_kelas as $dk): ?>
                <option value="<?= $dk['id_kelas']; ?>"
                    <?= $kelas && $kelas['id_kelas'] == $dk['id_kelas'] ? 'selected' : '' ?>>
                    <?= $dk['nama_kelas']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button name="simpan" class="btn btn-primary btn-sm">Simpan Perubahan</button>
</form>

<?php
if (isset($_POST['simpan'])) {

    $id_tahun = $_POST['id_tahun'];
    $nis = $_POST['nis'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $id_kelas_baru = $_POST['id_kelas'];

    // proses foto
    $foto_baru = $_FILES['foto']['name'];
    $tmp_foto = $_FILES['foto']['tmp_name'];

    if (!empty($foto_baru)) {
        $nama_baru = date("YmdHis").$foto_baru;
        move_uploaded_file($tmp_foto, "../assets/siswa/".$nama_baru);

        // hapus foto lama
        if (!empty($siswa['foto_siswa']) && file_exists("../assets/siswa/".$siswa['foto_siswa'])) {
            unlink("../assets/siswa/".$siswa['foto_siswa']);
        }

        $koneksi->query("UPDATE siswa SET 
            foto_siswa='$nama_baru'
            WHERE id_siswa='$id'
        ");
    }

    // update data pribadi
    $koneksi->query("UPDATE siswa SET 
        id_tahun='$id_tahun',
        induk_siswa='$nis',
        nama_siswa='$nama',
        alamat_siswa='$alamat'
        WHERE id_siswa='$id'
    ");

    // update kelas
    $koneksi->query("UPDATE siswakelas SET 
        id_kelas='$id_kelas_baru'
        WHERE id_siswa='$id'
    ");

    echo "<script>alert('Perubahan berhasil disimpan');</script>";
    echo "<script>location='index.php?halaman=siswa_detail&id=$id';</script>";
}
?>
