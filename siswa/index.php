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
  <title>SIAKAD</title>

  <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/dashboard/">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <!-- Custom styles for this template -->
  <link href="../admin/css/dashboard.css" rel="stylesheet">
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
              <a class="nav-link <?= !isset($_GET['halaman']) ? 'active' : '' ?>" href="index.php">
                <i class="bi bi-house-fill"></i>
                Beranda
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link <?= ($_GET['halaman'] ?? '') == 'profil' ? 'active' : '' ?>"
                href="index.php?halaman=profil">
                <i class="bi bi-person"></i>
                Data Pribadi
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link <?= ($_GET['halaman'] ?? '') == 'nilai' ? 'active' : '' ?>"
                href="index.php?halaman=nilai">
                <i class="bi bi-clipboard-data"></i>
                Nilai & Prestasi
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link <?= ($_GET['halaman'] ?? '') == 'absensi' ? 'active' : '' ?>"
                href="index.php?halaman=absensi">
                <i class="bi bi-calendar-check"></i>
                Absensi & Pelanggaran
              </a>
            </li>
          </ul>

        </div>
      </nav>

      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        <?php
        if (isset($_GET['halaman'])) {
          switch ($_GET['halaman']) {
            case 'profil':
              include 'profil.php';
              break;

            case 'nilai':
              include 'nilai.php';
              break;

            case 'absensi':
              include 'absensi.php';
              break;

            case 'logout':
              include 'logout.php';
              break;

            default:
              include 'dashboard.php';
              break;
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