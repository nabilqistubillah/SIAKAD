 <?php
$guru = array();

$ambil = $koneksi->query("SELECT * FROM jurusan");
while($tiap = $ambil->fetch_assoc()){
    $jurusan[] = $tiap;
}

?>

<h4>Data Jurusan</h4>
<table class="table">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Jurusan</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($jurusan as $key => $value):?>
        <tr>
            <td><?php echo $key + 1; ?></td>
            <td><?php echo $value['nama_jurusan']; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
