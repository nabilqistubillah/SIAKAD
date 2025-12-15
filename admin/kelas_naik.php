<?php
// keamanan admin
if (!isset($_SESSION['admin'])) {
    echo "<script>alert('Harus login admin');location='../login.php';</script>";
    exit;
}

// ambil data
$tahun = $koneksi->query("SELECT * FROM tahun");
$jurusan = $koneksi->query("SELECT * FROM jurusan");
?>

<h4>Kenaikan Kelas & Kelulusan</h4>
<hr>

<!--untuk filtr-->
<form method="post">
    <div class="row">
        <div class="col-md-3">
            <label>Tahun Ajaran</label>
            <select name="id_tahun" class="form-control" required>
                <option value="">Pilih</option>
                <?php while ($t = $tahun->fetch_assoc()): ?>
                    <option value="<?= $t['id_tahun'] ?>"><?= $t['tahun_ajaran'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="col-md-3">
            <label>Jurusan</label>
            <select name="id_jurusan" class="form-control" required>
                <option value="">Pilih</option>
                <?php while ($j = $jurusan->fetch_assoc()): ?>
                    <option value="<?= $j['id_jurusan'] ?>"><?= $j['nama_jurusan'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="col-md-3">
            <label>Kelas Asal</label>
            <select name="kelas_asal" class="form-control" required>
                <option value="">Pilih</option>
                <?php
                $kelas = $koneksi->query("SELECT * FROM kelas");
                while ($k = $kelas->fetch_assoc()):
                ?>
                    <option value="<?= $k['id_kelas'] ?>"><?= $k['nama_kelas'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" name="tampilkan" class="btn btn-primary btn-sm">
                Tampilkan Siswa
            </button>
        </div>
    </div>
</form>

<hr>


<?php
// nampilin siswa
if (isset($_POST['tampilkan'])) {

    $kelas_asal = $_POST['kelas_asal'];

    $siswa = $koneksi->query("SELECT sk.id_siswakelas, s.id_siswa, s.induk_siswa, s.nama_siswa
        FROM siswakelas sk
        JOIN siswa s ON sk.id_siswa = s.id_siswa
        WHERE sk.id_kelas = '$kelas_asal'
          AND s.status = 'AKTIF'
    ");
?>

<form method="post">
    <input type="hidden" name="kelas_asal" value="<?= $kelas_asal ?>">

    <table class="table table-bordered">
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
                        <input type="checkbox" name="id_siswa[]" value="<?= $s['id_siswa'] ?>" checked>
                    </td>
                    <td><?= $s['induk_siswa'] ?></td>
                    <td><?= $s['nama_siswa'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="row mt-3">
        <div class="col-md-4">
            <label>Kelas Tujuan (untuk Naik Kelas)</label>
            <select name="kelas_tujuan" class="form-control">
                <option value="">-- Pilih Kelas Tujuan --</option>
                <?php
                $kelas = $koneksi->query("SELECT * FROM kelas");
                while ($k = $kelas->fetch_assoc()):
                ?>
                    <option value="<?= $k['id_kelas'] ?>"><?= $k['nama_kelas'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="col-md-8 d-flex align-items-end gap-2">
            <button type="submit" name="naikkan" class="btn btn-success btn-sm"
                onclick="return confirm('Yakin naikkan kelas siswa terpilih?')">
                Naikkan Kelas
            </button>

            <button type="submit" name="Luluskan" class="btn btn-danger btn-sm"
                onclick="return confirm('Yakin LULUSKAN siswa terpilih?')">
                Luluskan
            </button>
        </div>
    </div>
</form>

<?php } ?>


<?php
//naik kelas
if (isset($_POST['naikkan'])) {

    if (empty($_POST['id_siswa'])) {
        echo "<script>alert('Pilih siswa terlebih dahulu');history.back();</script>";
        exit;
    }

    $kelas_asal   = $_POST['kelas_asal'];
    $kelas_tujuan = $_POST['kelas_tujuan'];
    $data         = $_POST['id_siswa'];

    if (empty($kelas_tujuan)) {
        echo "<script>alert('Pilih kelas tujuan!');history.back();</script>";
        exit;
    }

    foreach ($data as $id_siswa) {

        // hapus dari kelas asal
        $koneksi->query("
            DELETE FROM siswakelas 
            WHERE id_siswa='$id_siswa'
              AND id_kelas='$kelas_asal'
        ");

        // masukkan ke kelas tujuan
        $koneksi->query("
            INSERT INTO siswakelas (id_siswa, id_kelas)
            VALUES ('$id_siswa', '$kelas_tujuan')
        ");
    }

    echo "<script>alert('Kenaikan kelas berhasil');location='index.php?halaman=kelas';</script>";
}


// lulus
if (isset($_POST['Luluskan'])) {

    if (empty($_POST['id_siswa'])) {
        echo "<script>alert('Pilih siswa terlebih dahulu');history.back();</script>";
        exit;
    }

    $data = $_POST['id_siswa'];

    foreach ($data as $id_siswa) {

        // ubah status jadi LULUS
        $koneksi->query("UPDATE siswa 
            SET status='LULUS'
            WHERE id_siswa='$id_siswa'
        ");

        // hapus relasi kelas
        $koneksi->query("DELETE FROM siswakelas 
            WHERE id_siswa='$id_siswa'
        ");
    }

    echo "<script>alert('Siswa berhasil diluluskan');location='index.php?halaman=alumni';</script>";
}

?>
