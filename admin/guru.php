<?php
$guru = array();


$ambil = $koneksi->query("SELECT * FROM guru");
while ($tiap = $ambil->fetch_assoc()) {
    $guru[] = $tiap;
}
?>

<h4 class="font-weight-normal mb-3">Data Guru</h4>

<div class="row">

    <?php foreach ($guru as $key => $value): ?>
        <div class="col-3">
            <div class="card mb-3">
                <img src="../assets/guru/<?php echo $value['foto_guru']; ?>"
                    alt="foto guru">

                <div class="card-body">
                    <h6 class="card-title"><?php echo $value['nama_guru'] ?></h6>
                    <p>NIP: <?php echo $value['induk_guru'] ?></p>

                    <a href="index.php?halaman=guru_edit&id=<?php echo $value['id_guru'] ?>" class="btn btn-outline-warning btn-sm">Edit</a>
                    <a href="index.php?halaman=guru_hapus&id=<?php echo $value['id_guru'] ?>" class="btn btn-outline-danger btn-sm">Hapus</a>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>

<a href="index.php?halaman=guru_tambah" class="btn btn-primary btn-sm">Tambah</a>