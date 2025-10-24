<?php
$guru = array();


$ambil = $koneksi->query("SELECT * FROM guru");
while($tiap = $ambil->fetch_assoc()){
    $guru[] = $tiap;
}
?>

<h4 class="font-weight-normal mb-3">Data Guru</h4>

<div class="row">

    <?php foreach ($guru as $key => $value): ?>
    <div class="col-3">
        <div class="card">
            <img src="../assets/ <?php echo $value['foto_guru'] ?>" alt="" class="card-img-top">
            <div class="card-body">
                <h6 class="card-title"><?php echo $value['nama_guru']?></h6>
                <p>NIP: <?php echo $value['induk_guru']?></p>

                <a href="" class="btn btn-outline-warning btn-sm">Edit</a>
                <a href="" class="btn btn-outline-danger btn-sm">Hapus</a>
            </div>
        </div>
    </div>
    <?php endforeach ?>
</div>