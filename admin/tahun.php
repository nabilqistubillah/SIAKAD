<?php
$guru = array();


$ambil = $koneksi->query("SELECT * FROM tahun");
while($tiap = $ambil->fetch_assoc()){
    $tahun[] = $tiap;
}

?>


<h4>Data Tahun</h4>
<table class="table">
    <thead>
        <tr>
            <th>No</th>
            <th>Tahun Ajaran</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tahun as $key => $value):?>
        <tr>
            <td><?php echo $key + 1; ?></td>
            <td><?php echo $value['tahun_ajaran']; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>