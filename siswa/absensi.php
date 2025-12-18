<?php
$id_siswa = $_SESSION['siswa']['id_siswa'];

// ================= ABSENSI =================
$absensi = $koneksi->query("
  SELECT bulan, tahun, hadir, sakit, izin, alpa
  FROM absensi
  WHERE id_siswa = '$id_siswa'
  ORDER BY tahun DESC, FIELD(bulan,
    'Januari','Februari','Maret','April','Mei','Juni',
    'Juli','Agustus','September','Oktober','November','Desember'
  ) DESC
");


// ================= PELANGGARAN =================
$pelanggaran = $koneksi->query("
  SELECT * FROM pelanggaran
  WHERE id_siswa = '$id_siswa'
  ORDER BY tanggal DESC
");
?>

<h4>Absensi & Pelanggaran</h4>
<hr>

<!-- ================= ABSENSI ================= -->
<div class="card mb-4">
  <div class="card-header bg-light">
    <strong>Riwayat Absensi</strong>
  </div>
  <div class="card-body">

    <?php if ($absensi && $absensi ->num_rows > 0): ?>
      <table class="table table-bordered table-striped">
  <thead class="table-light">
    <tr>
      <th>No</th>
      <th>Bulan</th>
      <th>Tahun</th>
      <th>Hadir</th>
      <th>Sakit</th>
      <th>Izin</th>
      <th>Alpa</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; while ($a = $absensi->fetch_assoc()): ?>
      <tr>
        <td><?= $no++; ?></td>
        <td><?= $a['bulan']; ?></td>
        <td><?= $a['tahun']; ?></td>
        <td><span class="badge bg-success"><?= $a['hadir']; ?></span></td>
        <td><span class="badge bg-warning text-dark"><?= $a['sakit']; ?></span></td>
        <td><span class="badge bg-info text-dark"><?= $a['izin']; ?></span></td>
        <td><span class="badge bg-danger"><?= $a['alpa']; ?></span></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<?php else: ?>
<div class="alert alert-secondary mb-0">
  Belum ada data absensi.
</div>
<?php endif; ?>

  </div>
</div>

<!-- ================= PELANGGARAN ================= -->
<div class="card">
  <div class="card-header bg-light">
    <strong>Riwayat Pelanggaran</strong>
  </div>
  <div class="card-body">

    <?php if ($pelanggaran->num_rows > 0): ?>
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Jenis Pelanggaran</th>
            <th>Poin</th>
            <th>Keterangan</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while ($p = $pelanggaran->fetch_assoc()): ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= date('d-m-Y', strtotime($p['tanggal'])); ?></td>
              <td><?= $p['jenis']; ?></td>
              <td class="text-center">
                <span class="badge bg-danger"><?= $p['poin']; ?></span>
              </td>
              <td><?= $p['keterangan'] ?: '-'; ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="alert alert-secondary mb-0">
        Tidak ada pelanggaran tercatat 🎉
      </div>
    <?php endif; ?>

  </div>
</div>
