<?php
// ambil tahun ajaran
$tahun = $koneksi->query("SELECT * FROM tahun");

// filter
$where = "WHERE s.status=''";
if (isset($_POST['filter'])) {
    $id_tahun = $_POST['id_tahun'];
    if (!empty($id_tahun)) {
        $where .= " AND s.id_tahun='$id_tahun'";
    }
}

// ambil data alumni
$alumni = $koneksi->query("SELECT s.*, t.tahun_ajaran
    FROM siswa s
    LEFT JOIN tahun t ON s.id_tahun = t.id_tahun
    $where
    ORDER BY s.nama_siswa ASC
");
?>

<h4>Data Alumni</h4>
<hr>

<!-- FILTER -->
<form method="post" class="row mb-3">
    <div class="col-md-3">
        <select name="id_tahun" class="form-control">
            <option value="">Semua Tahun Masuk</option>
            <?php while ($t = $tahun->fetch_assoc()): ?>
                <option value="<?= $t['id_tahun'] ?>"><?= $t['tahun_ajaran'] ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" name="filter" class="btn btn-primary btn-sm">
            Filter
        </button>
    </div>
</form>

<!-- TABEL ALUMNI -->
<table class="table table-bordered table-striped">
    <thead class="table-light">
        <tr>
            <th>No</th>
            <th>Tahun Masuk</th>
            <th>NIS</th>
            <th>Nama</th>
            <th>Opsi</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; while ($a = $alumni->fetch_assoc()): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $a['tahun_ajaran'] ?></td>
            <td><?= $a['induk_siswa'] ?></td>
            <td><?= $a['nama_siswa'] ?></td>
            <td>
                <a href="index.php?halaman=siswa_detail&id=<?= $a['id_siswa'] ?>"
                   class="btn btn-info btn-sm">
                    Detail
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php if ($alumni->num_rows == 0): ?>
    <div class="alert alert-warning">
        Belum ada data alumni.
    </div>
<?php endif; ?>
