<?php
$guru = array();


$ambil = $koneksi->query("SELECT * FROM siswa LEFT JOIN tahun ON siswa.id_tahun=tahun.id_tahun");
while($tiap = $ambil->fetch_assoc()){
    $siswa[] = $tiap;
}

?>

<h4>Data Siswa</h4> <br>
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
        <?php foreach ($siswa as $key => $value): ?>
        <tr>
            <td><?php echo $key+1 ?></td>
            <td><?php echo $value['tahun_ajaran']?></td>
            <td><?php echo $value['induk_siswa']?></td>
            <td><?php echo $value['nama_siswa']?></td>
            <td>
                <a href="" class="btn btn-outline-warning btn-sm">Edit</a>
                <a href="" class="btn btn-outline-danger btn-sm">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a href="index.php?halaman=siswa_tambah" class="btn btn-outline-primary btn-sm">Tambah Data</a>