<?php

$id = $_GET['id'];

// ambil data siswa
$siswa = $koneksi->query("
  SELECT s.*, t.tahun_ajaran 
  FROM siswa s
  LEFT JOIN tahun t ON s.id_tahun = t.id_tahun
  WHERE s.id_siswa = '$id'
")->fetch_assoc();

// ambil data kelas
$kelas = $koneksi->query("
  SELECT k.nama_kelas, j.nama_jurusan, t.tahun_ajaran 
  FROM siswakelas sk
  JOIN kelas k ON sk.id_kelas = k.id_kelas
  JOIN jurusan j ON k.id_jurusan = j.id_jurusan
  JOIN tahun t ON k.id_tahun = t.id_tahun
  WHERE sk.id_siswa = '$id'
")->fetch_assoc();

// ambil nilai
$nilai = $koneksi->query("
  SELECT m.nama_mapel, n.h1, n.h2, n.h3, n.h4, n.rph, n.pts, n.pas
  FROM nilai n
  JOIN mengajar mg ON n.id_mengajar = mg.id_ajar
  JOIN mapel m ON mg.id_mapel = m.id_mapel
  WHERE n.id_siswakelas IN (SELECT id_siswakelas FROM siswakelas WHERE id_siswa = '$id')
");

// ambil absensi
$absensi = $koneksi->query("
  SELECT * FROM absensi WHERE id_siswa='$id' ORDER BY tahun DESC, bulan DESC
");
?>

<div class="container mt-3">

  <h4 class="text-center mb-3">Detail Siswa</h4>
  <hr>

  <!-- Foto Siswa -->
  <div class="text-center mb-4">
    <?php if (!empty($siswa['foto'])): ?>
      <img src="foto_siswa/<?php echo $siswa['foto']; ?>" 
           class="rounded-circle shadow"
           style="width:150px; height:150px; object-fit:cover;">
    <?php else: ?>
      <img src="../assets/siswa/" 
           class="rounded-circle shadow"
           style="width:150px; height:150px; object-fit:cover;">
    <?php endif; ?>
  </div>

  <!-- Data Siswa -->
  <div class="card mb-3">
    <div class="card-header text-center fw-bold fs-5">Data Siswa</div>
    <table class="table table-bordered mb-0 text-center">
      <tr>
        <th style="width:30%">Nama</th>
        <td><?php echo $siswa['nama_siswa']; ?></td>
      </tr>
      <tr>
        <th>NISN</th>
        <td><?php echo $siswa['induk_siswa']; ?></td>
      </tr>
      <tr>
        <th>Tahun Masuk</th>
        <td><?php echo $siswa['tahun_ajaran']; ?></td>
      </tr>
      <tr>
        <th>Alamat</th>
        <td><?php echo $siswa['alamat_siswa']; ?></td>
      </tr>
    </table>
  </div>

  <!-- Data Kelas -->
  <div class="card mb-3">
    <div class="card-header text-center fw-bold fs-5">Kelas</div>
    <table class="table table-bordered mb-0 text-center">
      <tr>
        <th style="width:30%">Kelas</th>
        <td><?php echo $kelas['nama_kelas'] ?? '-'; ?></td>
      </tr>
      <tr>
        <th>Jurusan</th>
        <td><?php echo $kelas['nama_jurusan'] ?? '-'; ?></td>
      </tr>
      <tr>
        <th>Tahun Ajaran</th>
        <td><?php echo $kelas['tahun_ajaran'] ?? '-'; ?></td>
      </tr>
    </table>
  </div>

  <div>
    <a href="index.php?halaman=siswa_edit&id=<?php echo $siswa['id_siswa']; ?>" class="btn btn-warning btn-sm">Edit Data</a>
  </div>

</div>



<div class="container mt-3">
  <!-- Nilai -->
  <div class="card mb-3">
    <div class="card-body">
      <h5 class="card-title">Nilai</h5>
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>Mata Pelajaran</th>
            <th>H1</th>
            <th>H2</th>
            <th>H3</th>
            <th>H4</th>
            <th>RPH</th>
            <th>PTS</th>
            <th>PAS</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($n = $nilai->fetch_assoc()): ?>
            <tr>
              <td><?php echo $n['nama_mapel']; ?></td>
              <td><?php echo $n['h1']; ?></td>
              <td><?php echo $n['h2']; ?></td>
              <td><?php echo $n['h3']; ?></td>
              <td><?php echo $n['h4']; ?></td>
              <td><?php echo $n['rph']; ?></td>
              <td><?php echo $n['pts']; ?></td>
              <td><?php echo $n['pas']; ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Rekap absensi -->
  <div class="card mb-3">
    <div class="card-body">
      <h5 class="card-title">Rekap Absensi Bulanan</h5>
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>Bulan</th>
            <th>Tahun</th>
            <th>Hadir</th>
            <th>Sakit</th>
            <th>Izin</th>
            <th>Alpa</th>
            <th>Opsi</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($a = $absensi->fetch_assoc()): ?>
            <tr>
              <td><?php echo $a['bulan']; ?></td>
              <td><?php echo $a['tahun']; ?></td>
              <td><?php echo $a['hadir']; ?></td>
              <td><?php echo $a['sakit']; ?></td>
              <td><?php echo $a['izin']; ?></td>
              <td><?php echo $a['alpa']; ?></td>
              <td>
                <a href="index.php?halaman=absensi_hapus&id=<?php echo $a['id_siswa']; ?>&ids=<?php echo $a['id_absensi']; ?>"
                  class="btn btn-danger btn-sm"
                  onclick="return confirm('Yakin ingin menghapus data absensi ini?')">Hapus</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <hr>
      <h6>Tambah Rekap Absensi Bulan Baru</h6>
      <form method="post" class="row g-2">
        <div class="col-md-3">
          <label>Bulan</label>
          <select name="bulan" class="form-control" required>
            <option value="">Pilih Bulan</option>
            <?php
            $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            foreach ($bulan as $b) {
              echo "<option value='$b'>$b</option>";
            }
            ?>
          </select>
        </div>
        <div class="col-md-2">
          <label>Tahun</label>
          <input type="number" name="tahun" class="form-control" value="<?php echo date('Y'); ?>" required>
        </div>
        <div class="col-md-1"><label>Hadir</label><input type="number" name="hadir" class="form-control" value="0"></div>
        <div class="col-md-1"><label>Sakit</label><input type="number" name="sakit" class="form-control" value="0"></div>
        <div class="col-md-1"><label>Izin</label><input type="number" name="izin" class="form-control" value="0"></div>
        <div class="col-md-1"><label>Alpa</label><input type="number" name="alpa" class="form-control" value="0"></div>
        <div class="col-md-2 align-self-end">
          <button type="submit" name="tambah_absensi" class="btn btn-primary btn-sm">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <?php
  // proses tambah absensi
  if (isset($_POST['tambah_absensi'])) {
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];
    $hadir = $_POST['hadir'];
    $sakit = $_POST['sakit'];
    $izin = $_POST['izin'];
    $alpa = $_POST['alpa'];

    // cek apakah data bulan-tahun sudah ada
    $cek = $koneksi->query("SELECT * FROM absensi WHERE id_siswa='$id' AND bulan='$bulan' AND tahun='$tahun'");
    if ($cek->num_rows > 0) {
      echo "<script>alert('Data absensi bulan ini sudah ada!'); location='index.php?halaman=siswa_detail&id=$id';</script>";
    } else {
      $koneksi->query("INSERT INTO absensi (id_siswa, bulan, tahun, hadir, sakit, izin, alpa)
                        VALUES ('$id', '$bulan', '$tahun', '$hadir', '$sakit', '$izin', '$alpa')");
      echo "<script>alert('Data absensi berhasil ditambahkan'); location='index.php?halaman=siswa_detail&id=$id';</script>";
    }
  }
  ?>

  <!-- Prestasi Siswa -->
  <div class="card mt-4">
    <div class="card-header bg-light text-black">
      <h5 class="mb-0">Data Prestasi Siswa</h5>
    </div>
    <div class="card-body">
      <?php
      $id_siswa = $_GET['id'];
      $prestasi = $koneksi->query("SELECT * FROM prestasi WHERE id_siswa='$id_siswa' ORDER BY tanggal DESC");
      ?>

      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Jenis Prestasi</th>
            <th>Tingkat</th>
            <th>Keterangan</th>
            <th>Opsi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          while ($data = $prestasi->fetch_assoc()):
          ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= date("d M Y", strtotime($data['tanggal'])); ?></td>
              <td><?= $data['jenis_prestasi']; ?></td>
              <td><?= $data['tingkat']; ?></td>
              <td><?= $data['keterangan']; ?></td>
              <td>
                <a href="index.php?halaman=prestasi_hapus&id=<?= $data['id_prestasi'] ?>&ids=<?= $id_siswa ?>"
                  class="btn btn-danger btn-sm"
                  onclick="return confirm('Yakin ingin menghapus prestasi ini?')">Hapus</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <!-- Form Tambah Prestasi -->
      <form method="post" class="mt-3">
        <div class="row">
          <div class="col-md-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal_prestasi" class="form-control" required>
          </div>
          <div class="col-md-3">
            <label>Jenis Prestasi</label>
            <input type="text" name="jenis_prestasi" class="form-control" placeholder="Contoh: Juara 1 Lomba IT" required>
          </div>
          <div class="col-md-2">
            <label>Tingkat</label>
            <input type="text" name="tingkat" class="form-control" placeholder="Kabupaten">
          </div>
          <div class="col-md-3">
            <label>Keterangan</label>
            <input type="text" name="keterangan_prestasi" class="form-control">
          </div>
          <div class="col-md-1 d-flex align-items-end">
            <button type="submit" name="simpan_prestasi" class="btn btn-primary btn-sm">+</button>
          </div>
        </div>
      </form>

      <?php
      if (isset($_POST['simpan_prestasi'])) {
        $tgl = $_POST['tanggal_prestasi'];
        $jenis = $_POST['jenis_prestasi'];
        $tingkat = $_POST['tingkat'];
        $ket = $_POST['keterangan_prestasi'];

        $koneksi->query("INSERT INTO prestasi (id_siswa, tanggal, jenis_prestasi, tingkat, keterangan)
                             VALUES ('$id_siswa', '$tgl', '$jenis', '$tingkat', '$ket')");
        echo "<script>alert('Data prestasi berhasil ditambahkan!');</script>";
        echo "<script>location='index.php?halaman=siswa_detail&id=$id_siswa';</script>";
      }
      ?>
    </div>
  </div>


  <!-- Pelanggaran Siswa -->
  <div class="card mt-4">
    <div class="card-header text-black bg-light">
      <h5 class="mb-0">Data Pelanggaran Siswa</h5>
    </div>
    <div class="card-body">
      <?php
      $id_siswa = $_GET['id'];
      $pelanggaran = $koneksi->query("SELECT * FROM pelanggaran WHERE id_siswa='$id_siswa' ORDER BY tanggal DESC");
      ?>

      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Jenis Pelanggaran</th>
            <th>Keterangan</th>
            <th>Poin</th>
            <th>Opsi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          while ($data = $pelanggaran->fetch_assoc()):
          ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= date("d M Y", strtotime($data['tanggal'])); ?></td>
              <td><?= $data['jenis_pelanggaran']; ?></td>
              <td><?= $data['keterangan']; ?></td>
              <td><?= $data['poin']; ?></td>
              <td>
                <a href="index.php?halaman=pelanggaran_hapus&id=<?= $data['id_pelanggaran'] ?>&ids=<?= $id_siswa ?>"
                  class="btn btn-danger btn-sm"
                  onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <!-- tambah pelanggrn -->
      <form method="post" class="mt-3">
        <div class="row">
          <div class="col-md-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
          </div>
          <div class="col-md-3">
            <label>Jenis Pelanggaran</label>
            <input type="text" name="jenis" class="form-control" placeholder="Contoh: Terlambat" required>
          </div>
          <div class="col-md-3">
            <label>Keterangan</label>
            <input type="text" name="ket" class="form-control">
          </div>
          <div class="col-md-2">
            <label>Poin</label>
            <input type="number" name="poin" class="form-control" min="0" required>
          </div>
          <div class="col-md-1 d-flex align-items-end">
            <button type="submit" name="simpan_pelanggaran" class="btn btn-primary btn-sm">+</button>
          </div>
        </div>
      </form>

      <?php
      if (isset($_POST['simpan_pelanggaran'])) {
        $tgl = $_POST['tanggal'];
        $jenis = $_POST['jenis'];
        $ket = $_POST['ket'];
        $poin = $_POST['poin'];

        $koneksi->query("INSERT INTO pelanggaran (id_siswa, tanggal, jenis_pelanggaran, keterangan, poin)
                              VALUES ('$id_siswa', '$tgl', '$jenis', '$ket', '$poin')");
        echo "<script>alert('Data pelanggaran berhasil ditambahkan!');</script>";
        echo "<script>location='index.php?halaman=siswa_detail&id=$id_siswa';</script>";
      }
      ?>
    </div>
  </div>


  <?php
  // ambil data kelas
  $kelas = $koneksi->query("
  SELECT k.nama_kelas, j.nama_jurusan, t.tahun_ajaran 
  FROM siswakelas sk
  JOIN kelas k ON sk.id_kelas = k.id_kelas
  JOIN jurusan j ON k.id_jurusan = j.id_jurusan
  JOIN tahun t ON k.id_tahun = t.id_tahun
  WHERE sk.id_siswa = '$id'
")->fetch_assoc();

  ?>

  <?php if (!empty($kelas['id_kelas'])): ?>
    <a href="index.php?halaman=siswakelas&id=<?= $kelas['id_kelas']; ?>"
      class="btn btn-secondary btn-sm">Kembali</a>
  <?php else: ?>
    <a href="index.php?halaman=kelas"
      class="btn btn-secondary btn-sm">Kembali</a>
  <?php endif; ?>


</div>