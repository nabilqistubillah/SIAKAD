<?php include './config/config.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Akademik</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

    <style>
        @import url(https://fonts.googleapis.com/css2?family=Crimson+Pro:wght@200..900&display=swap);

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Quicksand', sans-serif;
        }
        body {
            background-color:#f4f6f9;
            color: #333;
            line-height: 1.6;
        }

        a {
            text-decoration: none;
            color: #333;
            line-height: inherit;
        }
        ul{
            list-style: none;
        }

        .flex {
            display: flex;
            gap: 20px;
        }

        .container {
            max-width: 1200px;
            margin:  auto;
            padding: 60 20px;
        }

        .section-title{
            text-align: center;
            margin-bottom: 40px;
        }

        .section-title h2{
            font-size: 32px;
            color: #0d47a1;
        }

        .section-title p{
           max-width: 700px;
           margin: 10px auto 0;
           color: #555;
        }

        header{
            background-color: #0d47a1;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar_container_flex {
            max-width: 1100px;
            margin: auto;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo a{
            color: #fff;
            font-size: 24px;
            font-weight: bold;
        }
        .nav-links li a {
            color: #fff;
            font-size: 16px;
            transition: 0.3s;
        }
        .nav-links li a:hover {
            color: #ffeb3b;
        }
    

        /* Home Page */
        .homepage {
            background: linear-gradient(to right, #0d47a1, #1976d2);
            padding: 80px 20px;
        }

        .login-container{
            max-width: 900px;
            margin: auto;
            background: #fff;
            border-radius: 10px;
            display: flex;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .login-left {
            flex: 1;
            padding: 50px;
            background: #0d47a1;
            color: #fff;
            background: url('assets/home_bg.JPG') no-repeat center center;
        }

        .login-left h1 {
           width: 2px;
           background-color: #e0e0e0;
        }

        

        /* Garis pembatas di tengah */
        .login-divider {
            width: 2px;
            background-color: #e0e0e0;
        }

        .login-right {
            flex: 1;
            padding: 50px;
        }

        .login-right h2 {
            margin-bottom: 20px;
            color: #0d47a1;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            font-size: 14px;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .login-right button {
            width: 100%;
            padding: 10px;
            background-color: #0d47a1;
            color: #fff;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            cursor: pointer;
        }

        .login-right button:hover {
            background-color: #08306b;
        }

        .login-right a {
            display: block;
            margin-top: 10px;
            font-size: 14px;
            color: #0d47a1;
            text-align: center;
        }

        .cards {
            flex-wrap: wrap;
            justify-content: center;
        }

        .card {
            background: #fff;
            width: 250px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: 0.3s;
        }

        .card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
        }

        .card h3 {
            padding: 15px 15px 0;
            color: #0d47a1;
        }

        .card p {
            padding: 10px 15px 20px;
            font-size: 14px;
            color: #555;
        }
        
        .contact {
            background-color: #e3f2ff;
        }
        .row {
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .col {
            flex: 1;
            min-width: 280px;
        }

        .contact-detail p {
            margin-bottom: 10px;
            font-size: 14px;
        }

        .contact-detail i {
            margin-right: 10px;
            color: #0d47a1;
        }

        .form input,
        .form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .form textarea {
            resize: none;
            height: 120px;
        }

        .form button {
            background-color: #0d47a1;
            color: #fff;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form button:hover {
            background-color: #08306b;
        }

        /* =====================
        FOOTER
        ===================== */
        .footer {
            background-color: #0d47a1;
            color: #fff;
            text-align: center;
            padding: 20px;
            font-size: 14px;
        }

        /* =====================
        RESPONSIVE
        ===================== */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }

            .login-divider {
                display: none;
            }

            .navbar_container_flex {
                flex-direction: column;
                gap: 10px;
            }

            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <!-- header awal -->
    <header>
        <nav class="navbar container flex">
            <h2 class="logo"><a href="#">LOGO</a></h2>

            <ul class="nav-links flex">
                <!-- <li><a href="#homepage">Login</a></li> -->
                <li><a href="#menu">Login</a></li>
                <li><a href="#portfolio">Portfolio</a></li>
                <li><a href="#contactus">Tentang Sekolah</a></li>
            </ul>
        </nav>
    </header>
    <!--header akhir-->

    <!-- dashboard awal -->
    <section class="homepage" id="homepage">
        <div class="login-container">
            <div class="login-left">
                <h1>SMK Al-Miftah Pamekasan</h1>
                <p>Sistem Informasi Akademik Siswa (SIAKAD)</p>
            </div>

            <div class="login-divider"></div>

            <div class="login-right">
                <h2>Login</h2>
                <form method="post">
                    <div class="input-group">
                        <label>Username</label>
                        <input type="text" name="username" required>
                    </div>
                    <div class="input-group">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>
                    <button name="login">Login</button>
                    <a href="#">Lupa password?</a>
                </form>
            </div>
        </div>

    </section>
    <!--dashboard akhir-->

    <?php
    if (isset($_POST['login'])) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        // login admin
        $ambil = $koneksi->query("SELECT * FROM admin WHERE username='$username' AND password='" . sha1($password) . "'");
        $cekadmin = $ambil->fetch_assoc();
        if (!empty($cekadmin)) {
            $_SESSION["admin"] = $cekadmin;
            echo "<script>alert('Login admin berhasil')</script>";
            echo "<script>location='admin/index.php'</script>";
            exit;
        }

        // login guru
        $ambilguru = $koneksi->query("SELECT * FROM guru WHERE induk_guru='$username' AND pw_guru='" . sha1($password) . "'");
        $cekguru = $ambilguru->fetch_assoc();
        if (!empty($cekguru)) {
            $_SESSION["guru"] = $cekguru;
            echo "<script>alert('Login guru berhasil')</script>";
            echo "<script>location='guru/index.php'</script>";
            exit;
        }

        // login siswa
        $ambilsiswa = $koneksi->query("SELECT * FROM siswa WHERE nama_siswa='$username' AND induk_siswa='$password'");
        $ceksiswa = $ambilsiswa->fetch_assoc();
        if (!empty($ceksiswa)) {
            $_SESSION["siswa"] = $ceksiswa;
            echo "<script>alert('Selamat datang wali dari, {$ceksiswa['nama_siswa']}!')</script>";
            echo "<script>location='siswa/index.php'</script>";
            exit;
        }

        // jika semua gagal
        echo "<script>alert('Login gagal! Periksa kembali username dan password Anda.')</script>";
    }
    ?>


    <!--Menu awal-->
    <section class="menu" id="menu">
        <div class="container">
            <div class="section-title">
                <h2>Menu</h2>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias magni nesciunt omnis pariatur ipsa! Tempora ipsa in aspernatur quis ad rerum ratione </p>
            </div>

            <ul class="cards flex">
                <li class="card first">
                    <a href="login/login.html" style="text-decoration: none; color: inherit;">
                        <img src="images/datasiswa.jpeg" alt="datasiswa" />
                        <h3>Data Siswa</h3>
                        <p> aliquid qui eius doloremque autem temporibus voluptates sint distinctio, atque aliquam dignissimos sunt voluptate molestiae.</p>
                    </a>
                </li>
                <li class="card">
                    <img src="images/datasiswa.jpeg" alt="datasiswa" />
                    <h3>Prestasi Siswa</h3>
                    <p> aliquid qui eius doloremque autem temporibus voluptates sint distinctio, atque aliquam dignissimos sunt voluptate molestiae.</p>
                </li>
                <li class="card">
                    <img src="images/datasiswa.jpeg" alt="datasiswa" />
                    <h3>Prestasi Sekolah</h3>
                    <p> aliquid qui eius doloremque autem temporibus voluptates sint distinctio, atque aliquam dignissimos sunt voluptate molestiae.</p>
                </li>
                <li class="card">
                    <img src="images/datasiswa.jpeg" alt="datasiswa" />
                    <h3>Tentang Sekolah</h3>
                    <p> aliquid qui eius doloremque autem temporibus voluptates sint distinctio, atque aliquam dignissimos sunt voluptate molestiae.</p>
                </li>
            </ul>
        </div>
    </section>
    <!--menu akhir-->

    <!--portofolio awal-->
    <section class="portfolio" id="portfolio">
        <div class="container">
            <div class="section-title">
                <h2>Portfolio</h2>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias magni nesciunt omnis pariatur ipsa! Tempora ipsa in aspernatur quis ad rerum ratione </p>
            </div>
            <ul class="cards flex">
                <li class="card">
                    <img src="images/port.jpeg" alt="portofolio" />
                    <h3>-</h3>
                    <p> sit amet consectetur, adipisicing elit. Magni, possimus architecto enim quia hic, error aliquid qui eius doloremque autem temporibus voluptates sint distinctio, atque aliquam dignissimos sunt voluptate molestiae.</p>
                </li>
                <li class="card">
                    <img src="images/port.jpeg" alt="portofolio" />
                    <h3>-</h3>
                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Magni, possimus architecto enim quia hic, error aliquid qui eius doloremque autem temporibus voluptates sint distinctio, atque aliquam dignissimos sunt voluptate molestiae.</p>
                </li>
                <li class="card">
                    <img src="images/port.jpeg" alt="portofolio" />
                    <h3>-</h3>
                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Magni, possimus architecto enim quia hic, error aliquid qui eius doloremque autem temporibus voluptates sint distinctio, atque aliquam dignissimos sunt voluptate molestiae.</p>
                </li>
            </ul>
        </div>
    </section>
    <!--portofolio akhir-->

    <!--Contact sekolah awal-->
    <section class="contact" id="contactus">
        <div class="container">
            <div class="section-title">
                <h2>Contact Us</h2>
            </div>

            <div class="row flex">
                <div class="col information">
                    <div class="contact-detail">
                        <p><i class="fas fa-map-marker-alt"></i>jln.Raya Poto'an palengaan Pamekasan</p>
                        <p><i class="fas fa-phone"></i>087816973214</p>
                        <p><i class="fas fa-envelope"></i>smkalmiftahpanyeppenputri@gmail.com</p>
                        <p><i class="fa-solid fa-globe"></i>https://smkalmiftahputri-pamekasan.co.id</p>
                        <p><i class="fa-solid fa-video"></i>https://smkalmiftahputri-pamekasan.co.id</p>
                        <p><i class="fa-solid fa-camera"></i>https://smkalmiftahputri-pamekasan.co.id</p>
                    </div>
                </div>
                <div class="col form">
                    <form action="">
                        <input type="text" placeholder="Nama :" required>
                        <input type="email" placeholder="Email" required>
                        <textarea placeholder="Pesan" required></textarea>
                        <button id="submite" type="submit">Kirim Pesan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!--Contact sekolah akhir-->

    <section class="footer">
        <div class="container">
            <span>2025 &copy; Sistem Informasi Akademik Siswa SMK Al-Miftah Pamekasan</span>
        </div>
    </section>
    <!--footer akhir-->
</body>

</html>