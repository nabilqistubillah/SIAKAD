<!--ini juga harus di styling yaa niaa-->
<?php
function getCount($koneksi, $sql)
{
    $q = $koneksi->query($sql);
    if (!$q) {
        die("SQL Error: " . $koneksi->error);
    }
    $r = $q->fetch_assoc();
    return $r['j'] ?? 0;
}

function getSingle($koneksi, $sql)
{
    $q = $koneksi->query($sql);
    if (!$q) {
        die("SQL Error: " . $koneksi->error);
    }
    return $q->fetch_assoc();
}

// DATA RINGKAS
$jml_siswa   = getCount($koneksi, "SELECT COUNT(*) j FROM siswa WHERE status='AKTIF'");
$jml_alumni  = getCount($koneksi, "SELECT COUNT(*) j FROM siswa WHERE status=''");
$jml_kelas   = getCount($koneksi, "SELECT COUNT(*) j FROM kelas");
$jml_jurusan = getCount($koneksi, "SELECT COUNT(*) j FROM jurusan");
$jml_guru    = getCount($koneksi, "SELECT COUNT(*) j FROM guru");

$tahun = getSingle($koneksi, "
    SELECT tahun_ajaran
    FROM tahun
    ORDER BY id_tahun DESC
    LIMIT 1
");
?>


<h4 class="mb-3">Dashboard</h4>
<div class="row g-3">

    <?php
    $cards = [
        ['value' => $jml_siswa,   'label' => 'Siswa Aktif'],
        ['value' => $jml_alumni,  'label' => 'Alumni'],
        ['value' => $jml_kelas,   'label' => 'Kelas'],
        ['value' => $jml_jurusan, 'label' => 'Jurusan'],
        ['value' => $jml_guru,    'label' => 'Guru'],
        ['value' => $tahun['tahun_ajaran'] ?? '-', 'label' => 'Tahun Aktif'],
    ];

    foreach ($cards as $c):
    ?>
        <div class="col-md-2 col-sm-4">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <h4 class="fw-bold mb-1"><?= $c['value'] ?></h4>
                    <small class="text-muted"><?= $c['label'] ?></small>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

</div>
<hr>


<hr>

<h5 class="mt-4">Statistik Siswa per Kelas</h5>
<div class="table-responsive">
    <table class="table table-bordered table-sm align-middle">
        <thead class="table-light text-center">
            <tr>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $q = $koneksi->query("
    SELECT k.nama_kelas, j.nama_jurusan, COUNT(sk.id_siswa) jumlah
    FROM kelas k
    JOIN jurusan j ON k.id_jurusan=j.id_jurusan
    LEFT JOIN siswakelas sk ON k.id_kelas=sk.id_kelas
    GROUP BY k.id_kelas
");
            if (!$q) die($koneksi->error);

            while ($k = $q->fetch_assoc()):
            ?>
                <tr>
                    <td><?= $k['nama_kelas'] ?></td>
                    <td><?= $k['nama_jurusan'] ?></td>
                    <td class="text-center"><?= $k['jumlah'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>


<hr>

<!-- SISWA TERBARU -->
<h5>Siswa Terbaru</h5>
<table class="table table-bordered table-sm">
    <thead class="table-light">
        <tr>
            <th>NIS</th>
            <th>Nama</th>
            <th>Tahun Masuk</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $siswa = $koneksi->query("
            SELECT s.induk_siswa, s.nama_siswa, t.tahun_ajaran, s.status
            FROM siswa s
            LEFT JOIN tahun t ON s.id_tahun=t.id_tahun
            ORDER BY s.id_siswa DESC
            LIMIT 5
        ");
        while ($s = $siswa->fetch_assoc()):
        ?>
        <tr>
            <td><?= $s['induk_siswa'] ?></td>
            <td><?= $s['nama_siswa'] ?></td>
            <td><?= $s['tahun_ajaran'] ?></td>
            <td>
                <span class="badge bg-<?= $s['status']=='LULUS'?'success':'primary' ?>">
                    <?= $s['status'] ?>
                </span>
                </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<hr>

<div class="text-muted small">
    Sistem Informasi Akademik (SIAKAD) <br>
    SMK Al-Miftah Putri
</div>