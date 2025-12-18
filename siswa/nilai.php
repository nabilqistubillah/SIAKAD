<?php
$id_siswa = $_SESSION['siswa']['id_siswa'];

// nilai
$nilai = $koneksi->query("
  SELECT * FROM nilai 
  WHERE id_nilai = '$id_siswa'
  ORDER BY tahun_ajaran DESC, semester DESC
");

// prestasi
$prestasi = $koneksi->query("
  SELECT * FROM prestasi
  WHERE id_siswa = '$id_siswa'
  ORDER BY tahun DESC
");
?>

<h4>Nilai & Prestasi</h4>
<hr>

<!-- ================= NILAI ================= -->
<div class="card mb-4">
  <div class="card-header bg-light">
    <strong>Nilai Akademik</strong>
  </div>
  <div class="card-body">

    <?php if ($nilai && $nilai->num_rows > 0): ?>
      <table class="table table-bordered table-striped">
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>Mata Pelajaran</th>
            <th>Semester</th>
            <th>Tahun Ajaran</th>
            <th>Nilai</th>
            <th>Predikat</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while ($n = $nilai->fetch_assoc()): ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= $n['mapel']; ?></td>
              <td><?= $n['semester']; ?></td>
              <td><?= $n['tahun_ajaran']; ?></td>
              <td class="text-center"><?= $n['nilai_akhir']; ?></td>
              <td class="text-center">
                <span class="badge bg-primary"><?= $n['predikat']; ?></span>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="alert alert-warning mb-0">
        Nilai belum tersedia.
      </div>
    <?php endif; ?>

  </div>
</div>

<!-- ================= PRESTASI ================= -->
<div class="card">
  <div class="card-header bg-light">
    <strong>Prestasi</strong>
  </div>
  <div class="card-body">

    <?php if ($prestasi && $prestasi->num_rows > 0): ?>
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>Prestasi</th>
            <th>Tingkat</th>
            <th>Tahun</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while ($p = $prestasi->fetch_assoc()): ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= $p['nama_prestasi']; ?></td>
              <td><?= $p['tingkat']; ?></td>
              <td><?= $p['tahun']; ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="alert alert-secondary mb-0">
        Belum ada prestasi tercatat.
      </div>
    <?php endif; ?>

  </div>
</div>
