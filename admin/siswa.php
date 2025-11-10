<?php
$siswa = array();

// Ambil semua data siswa beserta tahun dan status kelas
$ambil = $koneksi->query("
    SELECT siswa.*, tahun.tahun_ajaran, 
    CASE 
        WHEN siswakelas.id_siswa IS NULL THEN 'Belum Masuk Kelas'
        ELSE 'Sudah Masuk Kelas'
    END AS status_kelas
    FROM siswa
    LEFT JOIN tahun ON siswa.id_tahun = tahun.id_tahun
    LEFT JOIN siswakelas ON siswa.id_siswa = siswakelas.id_siswa
");
while($tiap = $ambil->fetch_assoc()){
    $siswa[] = $tiap;
}
?>

<h4>Data Siswa</h4> <br>

<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>No</th>
            <th>Tahun Masuk</th>
            <th>NIS</th>
            <th>Nama</th>
            <th>Status</th>
            <th>Opsi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($siswa as $key => $value): ?>
        <tr>
            <td><?php echo $key+1 ?></td>
            <td><?php echo $value['tahun_ajaran']?></td>
            <td><?php echo $value['induk_siswa']?></td>
            <td><?php echo $value['nama_siswa']?></td>
            <td>
                <?php if ($value['status_kelas'] == 'Belum Masuk Kelas'): ?>
                    <span class="badge bg-secondary">Belum Masuk Kelas</span>
                <?php else: ?>
                    <span class="badge bg-success">Sudah Masuk Kelas</span>
                <?php endif; ?>
            </td>
            <td>
                <a href="index.php?halaman=siswamain_hapus&id=<?php echo $value['id_siswa'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus siswa ini?')">Hapus Permanen</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a href="index.php?halaman=siswa_tambah" class="btn btn-outline-primary btn-sm">Tambah Data</a>
