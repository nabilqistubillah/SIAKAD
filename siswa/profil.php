<?php
$id_siswa = $_SESSION['siswa']['id_siswa'];

// data siswa
$siswa = $koneksi->query("
  SELECT s.*, t.tahun_ajaran
  FROM siswa s
  LEFT JOIN tahun t ON s.id_tahun = t.id_tahun
  WHERE s.id_siswa = '$id_siswa'
")->fetch_assoc();

// data kelas aktif
$kelas = $koneksi->query("
  SELECT k.nama_kelas, j.nama_jurusan, t.tahun_ajaran
  FROM siswakelas sk
  JOIN kelas k ON sk.id_kelas = k.id_kelas
  JOIN jurusan j ON k.id_jurusan = j.id_jurusan
  JOIN tahun t ON k.id_tahun = t.id_tahun
  WHERE sk.id_siswa = '$id_siswa'
")->fetch_assoc();
?>

<h4>Profil Siswa</h4>
<hr>

<div class="row">

  <!-- FOTO -->
  <div class="col-md-3 text-center">
    <div class="card">
      <div class="card-body">
        <?php if (!empty($siswa['foto_siswa'])): ?>
          <img src="../assets/siswa/<?= $siswa['foto_siswa']; ?>" 
               class="img-fluid rounded mb-2" 
               style="max-height:220px;object-fit:cover;">
        <?php else: ?>
          <img src="../assets/default-user.png" 
               class="img-fluid rounded mb-2" 
               style="max-height:220px;">
        <?php endif; ?>

        <h6 class="mt-2"><?= $siswa['nama_siswa']; ?></h6>
        <span class="badge bg-success"><?= $siswa['status']; ?></span>
      </div>
    </div>
  </div>

  <!-- DATA PRIBADI -->
  <div class="col-md-9">
    <div class="card mb-3">
      <div class="card-header bg-light">
        <strong>Data Pribadi</strong>
      </div>
      <div class="card-body">
        <table class="table table-borderless">
          <tr>
            <th width="200">NIS</th>
            <td><?= $siswa['induk_siswa']; ?></td>
          </tr>
          <tr>
            <th>Tahun Masuk</th>
            <td><?= $siswa['tahun_ajaran']; ?></td>
          </tr>
          <tr>
            <th>Alamat</th>
            <td><?= $siswa['alamat_siswa']; ?></td>
          </tr>
        </table>
      </div>
    </div>

    <!-- DATA KELAS -->
    <div class="card">
      <div class="card-header bg-light">
        <strong>Informasi Kelas</strong>
      </div>
      <div class="card-body">
        <?php if ($kelas): ?>
          <table class="table table-borderless">
            <tr>
              <th width="200">Kelas</th>
              <td><?= $kelas['nama_kelas']; ?></td>
            </tr>
            <tr>
              <th>Jurusan</th>
              <td><?= $kelas['nama_jurusan']; ?></td>
            </tr>
            <tr>
              <th>Tahun Ajaran</th>
              <td><?= $kelas['tahun_ajaran']; ?></td>
            </tr>
          </table>
        <?php else: ?>
          <div class="alert alert-warning mb-0">
            Siswa belum terdaftar di kelas aktif.
          </div>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>
