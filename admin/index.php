<?php
include '../config/config.php';
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Hugo 0.88.1">
  <title>Dashboard Template · Bootstrap v5.1</title>

  <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/dashboard/">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <!-- Custom styles for this template -->
  <link href="css/dashboard.css" rel="stylesheet">
</head>

<body>

  <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">SMK Al-Miftah Putri</a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
    <div class="navbar-nav">
      <div class="nav-item text-nowrap">
        <a class="nav-link px-3" href="index.php?halaman=logout">Sign out</a>
      </div>
    </div>
  </header>

  <div class="container-fluid">
    <div class="row">
      <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
        <div class="position-sticky pt-3">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="index.php">
                <i class="bi bi-house-fill"></i>
                Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php?halaman=tahun">
                <i class="bi bi-calendar"></i>
                Tahun
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php?halaman=guru">
                <i class="bi bi-person-badge"></i>
                Guru
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php?halaman=siswa">
                <i class="bi bi-person"></i>
                Siswa
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php?halaman=jurusan">
                <i class="bi bi-archive"></i>
                Jurusan
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php?halaman=kelas">
                <i class="bi bi-bank"></i>
                Kelas
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php?halaman=kategori">
                <i class="bi bi-tag"></i>
                Kategori
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php?halaman=mapel">
                <i class="bi bi-book"></i>
                Mata Pelajaran
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php?halaman=mengajar">
                <i class="bi bi-highlighter"></i>
                Mengajar
              </a>
            </li>
          </ul>

          <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Laporan</span>
            <a class="link-secondary" href="#" aria-label="Add a new report">
              <i class="bi bi-layers"></i>
            </a>
          </h6>
          <ul class="nav flex-column mb-2">
            <li class="nav-item">
              <a class="nav-link" href="index.php?halaman=laporan_nilai">
                <i class="bi bi-file-earmark-text"></i>
                Nilai
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php?laporan_siswa">
                <i class="bi bi-bar-chart-line"></i>
                Statistik Siswa (grafik)
              </a>
            </li>

          </ul>
        </div>
      </nav>

      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <?php
        if (isset($_GET['halaman'])) {
          if ($_GET['halaman'] == "tahun") {
            include 'tahun.php';
          } elseif ($_GET['halaman'] == "guru") {
            include 'guru.php';
          } elseif ($_GET['halaman'] == "guru_tambah") {
            include 'guru_tambah.php';
          } elseif ($_GET['halaman'] == "guru_edit") {
            include 'guru_edit.php';
          } elseif ($_GET['halaman'] == "guru_hapus") {
            include 'guru_hapus.php';
          } elseif ($_GET['halaman'] == "mengajar") {
            include 'mengajar.php';
          } elseif ($_GET['halaman'] == "siswa") {
            include 'siswa.php';
          } elseif ($_GET['halaman'] == "siswa_tambah") {
            include 'siswa_tambah.php';
          } elseif ($_GET['halaman'] == "siswa_hapus") {
            include 'siswa_hapus.php';
          } elseif ($_GET['halaman'] == "siswamain_hapus") {
            include 'siswamain_hapus.php';
          } elseif ($_GET['halaman'] == "jurusan") {
            include 'jurusan.php';
          } elseif ($_GET['halaman'] == "kelas") {
            include 'kelas.php';
          } elseif ($_GET['halaman'] == "kelas_tambah") {
            include 'kelas_tambah.php';
          } elseif ($_GET['halaman'] == "kelas_edit") {
            include 'kelas_edit.php';
          } elseif ($_GET['halaman'] == "mapel") {
            include 'mapel.php';
          } elseif ($_GET['halaman'] == "laporan_nilai") {
            include 'laporan_nilai.php';
          } elseif ($_GET['halaman'] == "laporan_siswa") {
            include 'laporan_siswa.php';
          } elseif ($_GET['halaman'] == "kategori") {
            include 'kategori.php';
          } elseif ($_GET['halaman'] == "siswakelas") {
            include 'siswakelas.php';
          } elseif ($_GET['halaman'] == "logout") {
            include 'logout.php';
          }
          } else {
            include 'dashboard.php';
          }
        ?>


      </main>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>