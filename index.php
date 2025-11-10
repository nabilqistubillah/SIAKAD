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
            font-family: 'Poppins', sans-serif;
        }

        html,
        body {
            scroll-behavior: smooth;
            background: #f2f2f2;

        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: #333;
            z-index: 100;
        }

        .flex {
            display: flex;
            align-items: center;
        }

        .container {
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            padding: 0 20px;
        }

        .navbar {
            justify-content: space-between;
        }

        .nav-links {
            gap: 25px;
            list-style: none;
        }

        .navbar a {
            padding: 16px 0;
            display: inline-block;
            color: #fff;
            text-decoration: none;
            text-transform: capitalize;
            transition: 0.2s;
        }

        .navbar a:hover {
            color: rgb(122, 122, 245);
        }

        /* Home Page */
        .homepage {
            position: relative;
            height: 100vh;
            width: 100%;
            background: url(./assets/home_bg.JPG);
            background-position: top;
            background-size: cover;
        }

        .homepage::before {
            content: "";
            position: absolute;
            height: 100%;
            width: 100%;
            top: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.6);
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
            backdrop-filter: brightness(0.9);
        }

        /* Bagian kiri: teks nama sekolah */
        .login-left {
            flex: 1;
            text-align: center;
            padding: 40px;
        }

        .login-left h1 {
            font-size: 32px;
            margin-bottom: 10px;
            color: #fff;
        }

        .login-left p {
            font-size: 16px;
            color: #ddd;
        }

        /* Garis pembatas di tengah */
        .login-divider {
            width: 2px;
            height: 60%;
            background-color: rgba(255, 255, 255, 0.6);
            margin: 0 40px;
        }

        /* Bagian kanan: form login */
        .login-right {
            flex: 1;
            color: white;
            max-width: 350px;
        }

        .login-right h2 {
            margin-bottom: 20px;
            color: #fff;
        }

        .input-group {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }

        .input-group label {
            margin-bottom: 5px;
            font-weight: 500;
        }

        .input-group input {
            padding: 10px;
            border: none;
            border-radius: 6px;
            background-color: rgba(255, 255, 255, 0.8);
            color: #000;
        }

        .input-group input:focus {
            outline: 2px solid #007bff;
        }

        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 6px;
            background-color: #007bff;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        a {
            display: block;
            margin-top: 10px;
            text-align: center;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
        }

        a:hover {
            text-decoration: underline;
        }

        /* bagian menu */
        section {
            padding-top: 80px;
        }

        .section-title {
            text-align: center;
        }

        section h2 {
            font-size: 2rem;
            margin-bottom: 15px;
        }

        section .cards {
            margin-top: 50px;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        section .card {
            width: calc(95% / 2 - 30px);
            text-align: center;
            list-style: none;
            background-color: #fff;
            padding: 40px 15px;
            border-radius: 8px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 40px;
            transition: 0.3s;
        }

        section .first:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
        }

        section .card img {
            height: 120px;
            width: 120px;
            border-radius: 50px;
            margin-bottom: 20px;
        }

        section .card p {
            margin-top: 5px;
        }

        /* bagian portofolio */
        .portfolio .card {
            width: calc(100% / 3 - 30px);
            text-align: center;
            list-style: none;
            background-color: #fff;
            padding: 40px 15px;
            border-radius: 8px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 40px;
            transition: 0.3s;
        }

        .portfolio .card img {
            height: 240px;
            width: 100%;
            border-radius: 8px 8px 0 0;
        }

        /* contact */
        .contact .row {
            justify-content: space-between;
            margin: 60px 0 90px;
        }

        .contact .row .col {
            padding: 0 10px;
            width: calc(100% / 2 - 50px);
        }

        .contact .row .col p {
            color: hwb(236 56% 6%);
            margin-bottom: 23px;
        }

        .contact .row .col p i {
            margin-right: 15px;
            color: hsl(236, 100%, 51%);
        }

        .contact form input {
            width: 100%;
            height: 45px;
            margin-bottom: 20px;
            padding: 0 15px;
            border: 1px solid #bfbfbf;
            outline: none;
        }

        .contact form textarea {
            padding: 15px;
            width: 100%;
            height: 120px;
            outline: none;
            resize: none;
            border: 1px solid #bfbfbf;
        }

        .contact form button {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 16px;
            color: #fff;
            margin-top: 10px;
            border: none;
            background: hsl(236, 100%, 51%);
            cursor: pointer;
            transition: 0.2s;
        }

        .contact form button:hover {
            background: rgb(122, 122, 245);
        }

        /* footer */
        .footer {
            background: #333;
            padding: 20px 0;
            text-align: center;
        }

        .footer span {
            color: #fff;
        }

        /* Responsive */
        .navbar .menu-btn {
            display: none;
        }
    </style>
</head>

<body>
    <!-- header awal -->
    <header>
        <nav class="navbar container flex">
            <h2 class="logo"><a href="#">LOGO</a></h2>

            <ul class="nav-links flex">
                <li><a href="#homepage">Login</a></li>
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