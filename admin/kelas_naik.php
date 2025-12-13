<?php
// keamanan dasar
if (!isset($_SESSION['admin'])) {
    echo "<script>alert('Harus login admin');location='../login.php';</script>";
    exit;
}

// ambil tahun ajaran
$tahun = $koneksi->query("SELECT * FROM tahun");

// ambil jurusan
$jurusan = $koneksi->query("SELECT * FROM jurusan");
?>

<h4>Kenaikan Kelas</h4>
<hr>

<form method="post">
    <!-- Tahun Ajaran -->
    <div class="mb-3">
        <label>Tahun Ajaran</label>
        <select name="id_tahun" class="form-control" required>
            <option value="">Pilih Tahun</option>
            <?php while ($t = $tahun->fetch_assoc()): ?>
                <option value="<?= $t['id_tahun'] ?>">
                    <?= $t['tahun_ajaran'] ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <!-- Jurusan -->
    <div class="mb-3">
        <label>Jurusan</label>
        <select name="id_jurusan" class="form-control" required>
            <option value="">Pilih Jurusan</option>
            <?php while ($j = $jurusan->fetch_assoc()): ?>
                <option value="<?= $j['id_jurusan'] ?>">
                    <?= $j['nama_jurusan'] ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <!-- Kelas Asal -->
    <div class="mb-3">
        <label>Kelas Asal</label>
        <select name="kelas_asal" class="form-control" required>
            <option value="">Pilih Kelas Asal</option>
            <?php
            $kelas = $koneksi->query("SELECT * FROM kelas");
            while ($k = $kelas->fetch_assoc()):
            ?>
                <option value="<?= $k['id_kelas'] ?>">
                    <?= $k['nama_kelas'] ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <!-- Kelas Tujuan -->
    <div class="mb-3">
        <label>Kelas Tujuan</label>
        <select name="kelas_tujuan" class="form-control" required>
            <option value="">Pilih Kelas Tujuan</option>
            <?php
            $kelas = $koneksi->query("SELECT * FROM kelas");
            while ($k = $kelas->fetch_assoc()):
            ?>
                <option value="<?= $k['id_kelas'] ?>">
                    <?= $k['nama_kelas'] ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <button type="submit" name="tampilkan" class="btn btn-primary btn-sm">
        Tampilkan Siswa
    </button>
</form>

<hr>

<?php
// ======================
// TAMPILKAN SISWA
// ======================
if (isset($_POST['tampilkan'])) {

    $kelas_asal = $_POST['kelas_asal'];

    $siswa = $koneksi->query("
        SELECT sk.id_siswakelas, s.id_siswa, s.induk_siswa, s.nama_siswa
        FROM siswakelas sk
        JOIN siswa s ON sk.id_siswa = s.id_siswa
        WHERE sk.id_kelas = '$kelas_asal'
    ");
?>

<form method="post">
    <input type="hidden" name="kelas_asal" value="<?= $_POST['kelas_asal'] ?>">
    <input type="hidden" name="kelas_tujuan" value="<?= $_POST['kelas_tujuan'] ?>">

    <table class="table table-bordered mt-3">
        <thead class="table-light">
            <tr>
                <th>Pilih</th>
                <th>NIS</th>
                <th>Nama</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($s = $siswa->fetch_assoc()): ?>
                <tr>
                    <td>
                        <input type="checkbox" name="id_siswakelas[]" value="<?= $s['id_siswakelas'] ?>" checked>
                    </td>
                    <td><?= $s['induk_siswa'] ?></td>
                    <td><?= $s['nama_siswa'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <button type="submit" name="naikkan" class="btn btn-success btn-sm"
        onclick="return confirm('Yakin naikkan siswa ke kelas tujuan?')">
        Naikkan Kelas
    </button>
</form>

<?php } ?>

<?php
// ======================
// PROSES NAIK KELAS
// ======================
if (isset($_POST['naikkan'])) {

    $kelas_tujuan = $_POST['kelas_tujuan'];
    $data = $_POST['id_siswakelas'];

    foreach ($data as $id_sk) {
        // ambil id siswa
        $ambil = $koneksi->query("SELECT id_siswa FROM siswakelas WHERE id_siswakelas='$id_sk'");
        $s = $ambil->fetch_assoc();

        // hapus relasi lama
        $koneksi->query("DELETE FROM siswakelas WHERE id_siswakelas='$id_sk'");

        // masukkan ke kelas baru
        $koneksi->query("INSERT INTO siswakelas (id_siswa, id_kelas)
                         VALUES ('{$s['id_siswa']}', '$kelas_tujuan')");
    }

    echo "<script>alert('Kenaikan kelas berhasil');location='index.php?halaman=kelas';</script>";
}
?>
