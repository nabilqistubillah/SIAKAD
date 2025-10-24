<?php
$guru = array();

$ambil = $koneksi->query("SELECT * FROM kategori");
while($tiap = $ambil->fetch_assoc()){
    $kategori[] = $tiap;
}

?>

<h4>Data Kategori</h4>
<table class="table">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Kategori</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($kategori as $key => $value):?>
        <tr>
            <td><?php echo $key + 1; ?></td>
            <td><?php echo $value['nama_kategori']; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>