<?php
include './config/config.php';

if (isset($_POST['login'])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // login admin
    $ambil = $koneksi->query("SELECT * FROM admin WHERE username='$username' AND password='" . sha1($password) . "'");
    $cekadmin = $ambil->fetch_assoc();
    if (!empty($cekadmin)) {
        $_SESSION["admin"] = $cekadmin;
        echo "<script>alert('Login admin berhasil'); location='admin/index.php';</script>";
        exit;
    }

    // login siswa
    $ambilsiswa = $koneksi->query("SELECT * FROM siswa WHERE nama_siswa='$username' AND induk_siswa='$password'");
    $ceksiswa = $ambilsiswa->fetch_assoc();
    if (!empty($ceksiswa)) {
        $_SESSION["siswa"] = $ceksiswa;
        echo "<script>alert('Selamat datang wali dari, {$ceksiswa['nama_siswa']}!'); location='siswa/index.php';</script>";
        exit;
    }

    echo "<script>alert('Login gagal! Periksa kembali username dan password Anda.');</script>";
}
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Akademik - SMK Al-Miftah</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: '#4f46e5',
                        secondary: '#0ea5e9',
                        dark: '#0f172a',
                    }
                }
            }
        }
    </script>
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <!-- Header -->
    <header class="fixed w-full z-50 transition-all duration-300" id="navbar">
        <div class="absolute inset-0 bg-dark/80 backdrop-blur-md shadow-lg"></div>
        <nav class="container mx-auto px-6 py-4 relative flex justify-between items-center">
            <a href="#" class="text-2xl font-bold text-white tracking-wide hover:text-primary transition">SIAKAD</a>
            
            <!-- Desktop Menu -->
            <ul class="hidden md:flex space-x-8 text-white font-medium">
                <li><a href="#home" class="hover:text-primary transition">Beranda</a></li>
                <li><a href="#menu" class="hover:text-primary transition">Menu</a></li>
                <li><a href="#portfolio" class="hover:text-primary transition">Portfolio</a></li>
                <li><a href="#contact" class="hover:text-primary transition">Kontak</a></li>
            </ul>

            <!-- Mobile Menu Button -->
            <button class="md:hidden text-white text-2xl" id="menu-btn">
                <i class="fas fa-bars"></i>
            </button>
        </nav>
        
        <!-- Mobile Dropdown -->
        <div class="hidden md:hidden bg-dark text-white absolute w-full left-0 top-full shadow-lg" id="mobile-menu">
            <ul class="flex flex-col p-4 space-y-4 font-medium">
                <li><a href="#home" class="block hover:text-primary">Beranda</a></li>
                <li><a href="#menu" class="block hover:text-primary">Menu</a></li>
                <li><a href="#portfolio" class="block hover:text-primary">Portfolio</a></li>
                <li><a href="#contact" class="block hover:text-primary">Kontak</a></li>
            </ul>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="home" class="relative min-h-screen flex items-center justify-center bg-cover bg-center overflow-hidden" style="background-image: url('./assets/home_bg.JPG');">
        <div class="absolute inset-0 bg-gradient-to-br from-black/80 via-black/60 to-black/40"></div>
        
        <div class="container mx-auto px-6 relative z-10 flex flex-col md:flex-row items-center justify-between gap-12 pt-20">
            <!-- Left Side: Text -->
            <div class="text-white md:w-1/2 space-y-6 text-center md:text-left animate-fade-in-up">
                <h1 class="text-5xl md:text-6xl font-extrabold leading-tight text-shadow">
                    SMK Al-Miftah <span class="text-primary">Pamekasan</span>
                </h1>
                <p class="text-lg md:text-xl text-gray-200 font-light max-w-lg mx-auto md:mx-0">
                    Sistem Informasi Akademik Terpadu untuk kemudahan akses informasi nilai, absensi, dan perkembangan siswa secara real-time.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                    <a href="#menu" class="px-8 py-3 bg-primary hover:bg-indigo-700 text-white font-semibold rounded-full shadow-lg transition transform hover:scale-105">
                        Jelajahi Menu
                    </a>
                    <a href="#contact" class="px-8 py-3 glass hover:bg-white/20 text-white font-semibold rounded-full shadow-lg transition transform hover:scale-105">
                        Hubungi Kami
                    </a>
                </div>
            </div>

            <!-- Right Side: Login Card -->
            <div class="w-full md:w-1/3 max-w-sm">
                <div class="glass p-8 rounded-2xl shadow-2xl backdrop-blur-xl border border-white/10 animate-fade-in-right">
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/20 text-primary mb-4">
                            <i class="fas fa-user-lock text-3xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-white">Login Portal</h2>
                        <p class="text-gray-300 text-sm mt-1">Masuk sebagai Siswa atau Admin</p>
                    </div>

                    <form method="post" class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Username / Nama Siswa</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" name="username" class="w-full pl-10 pr-4 py-3 bg-white/10 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" placeholder="Masukkan username" required>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Password / NISN</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" name="password" class="w-full pl-10 pr-4 py-3 bg-white/10 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" placeholder="Masukkan password" required>
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <label class="flex items-center text-gray-300 cursor-pointer">
                                <input type="checkbox" class="mr-2 rounded bg-white/10 border-gray-600 text-primary focus:ring-offset-0 focus:ring-2 focus:ring-primary">
                                Ingat Saya
                            </label>
                            <a href="#" class="text-primary hover:text-indigo-400 transition">Lupa Password?</a>
                        </div>

                        <button type="submit" name="login" class="w-full py-3 bg-gradient-to-r from-primary to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-bold rounded-lg shadow-lg transform hover:-translate-y-0.5 transition duration-200">
                            Masuk Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Scroll Down Indicator -->
        <a href="#menu" class="absolute bottom-10 left-1/2 transform -translate-x-1/2 text-white animate-bounce">
            <i class="fas fa-chevron-down text-2xl opacity-70"></i>
        </a>
    </section>

    <!-- Menu Section -->
    <section id="menu" class="py-20 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-primary font-semibold text-sm uppercase tracking-wider">Layanan Kami</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-2 mb-4">Informasi Akademik</h2>
                <div class="h-1 w-20 bg-primary mx-auto rounded-full"></div>
                <p class="text-gray-600 mt-6 text-lg">Akses berbagai informasi penting terkait akademik sekolah dengan mudah dan cepat.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Card 1 -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition duration-300 overflow-hidden group">
                    <div class="h-48 overflow-hidden relative">
                        <img src="images/datasiswa.jpeg" alt="Data Siswa" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                            <a href="#" class="px-6 py-2 bg-white text-dark font-semibold rounded-full hover:bg-primary hover:text-white transition">Lihat Detail</a>
                        </div>
                    </div>
                    <div class="p-6 text-center">
                        <div class="w-12 h-12 bg-primary/10 text-primary rounded-lg flex items-center justify-center mx-auto mb-4 text-xl">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Data Siswa</h3>
                        <p class="text-gray-500 text-sm">Informasi lengkap mengenai data seluruh siswa aktif.</p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition duration-300 overflow-hidden group">
                    <div class="h-48 overflow-hidden relative">
                        <img src="images/datasiswa.jpeg" alt="Prestasi Siswa" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                            <a href="#" class="px-6 py-2 bg-white text-dark font-semibold rounded-full hover:bg-primary hover:text-white transition">Lihat Detail</a>
                        </div>
                    </div>
                    <div class="p-6 text-center">
                        <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mx-auto mb-4 text-xl">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Prestasi Siswa</h3>
                        <p class="text-gray-500 text-sm">Daftar pencapaian dan prestasi siswa yang membanggakan.</p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition duration-300 overflow-hidden group">
                    <div class="h-48 overflow-hidden relative">
                        <img src="images/datasiswa.jpeg" alt="Prestasi Sekolah" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                         <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                            <a href="#" class="px-6 py-2 bg-white text-dark font-semibold rounded-full hover:bg-primary hover:text-white transition">Lihat Detail</a>
                        </div>
                    </div>
                    <div class="p-6 text-center">
                        <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-lg flex items-center justify-center mx-auto mb-4 text-xl">
                            <i class="fas fa-star"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Prestasi Sekolah</h3>
                        <p class="text-gray-500 text-sm">Pencapaian sekolah di tingkat regional maupun nasional.</p>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition duration-300 overflow-hidden group">
                    <div class="h-48 overflow-hidden relative">
                        <img src="images/datasiswa.jpeg" alt="Tentang Sekolah" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                         <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                            <a href="#" class="px-6 py-2 bg-white text-dark font-semibold rounded-full hover:bg-primary hover:text-white transition">Lihat Detail</a>
                        </div>
                    </div>
                    <div class="p-6 text-center">
                        <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center mx-auto mb-4 text-xl">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Tentang Sekolah</h3>
                        <p class="text-gray-500 text-sm">Profil singkat dan sejarah SMK Al-Miftah Pamekasan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio/Activities Section -->
    <section id="portfolio" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-16">
                 <span class="text-primary font-semibold text-sm uppercase tracking-wider">Galeri</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-2 mb-4">Kegiatan Sekolah</h2>
                <div class="h-1 w-20 bg-primary mx-auto rounded-full"></div>
                <p class="text-gray-600 mt-6 text-lg">Dokumentasi kegiatan belajar mengajar dan ekstrakurikuler.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Portfolio Item 1 -->
                <div class="rounded-xl overflow-hidden shadow-lg group relative cursor-pointer">
                    <img src="images/port.jpeg" alt="Portfolio 1" class="w-full h-64 object-cover transform group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 flex flex-col justify-end p-6">
                        <span class="text-primary font-bold text-sm uppercase mb-2">Akademik</span>
                        <h3 class="text-white text-xl font-bold">Olimpiade Sains</h3>
                        <p class="text-gray-300 text-sm mt-2">Partisipasi siswa dalam olimpiade tingkat provinsi.</p>
                    </div>
                </div>

                <!-- Portfolio Item 2 -->
                <div class="rounded-xl overflow-hidden shadow-lg group relative cursor-pointer">
                    <img src="images/port.jpeg" alt="Portfolio 2" class="w-full h-64 object-cover transform group-hover:scale-110 transition duration-500">
                     <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 flex flex-col justify-end p-6">
                        <span class="text-primary font-bold text-sm uppercase mb-2">Ekstrakurikuler</span>
                        <h3 class="text-white text-xl font-bold">Pramuka</h3>
                        <p class="text-gray-300 text-sm mt-2">Kegiatan kemah tahunan di Pamekasan.</p>
                    </div>
                </div>

                <!-- Portfolio Item 3 -->
                <div class="rounded-xl overflow-hidden shadow-lg group relative cursor-pointer">
                    <img src="images/port.jpeg" alt="Portfolio 3" class="w-full h-64 object-cover transform group-hover:scale-110 transition duration-500">
                     <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 flex flex-col justify-end p-6">
                        <span class="text-primary font-bold text-sm uppercase mb-2">Sosial</span>
                        <h3 class="text-white text-xl font-bold">Bakti Sosial</h3>
                        <p class="text-gray-300 text-sm mt-2">Berbagi dengan masyarakat sekitar.</p>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-12">
                <a href="#" class="inline-block px-8 py-3 border-2 border-primary text-primary font-semibold rounded-full hover:bg-primary hover:text-white transition duration-300">Lihat Semua Kegiatan</a>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-dark text-white relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#4f46e5 1px, transparent 1px); background-size: 20px 20px;"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Info -->
                <div class="space-y-8">
                    <div>
                        <span class="text-primary font-bold uppercase tracking-wider">Hubungi Kami</span>
                        <h2 class="text-4xl font-bold mt-2">Tetap Terhubung</h2>
                        <p class="text-gray-300 mt-4 text-lg">Punya pertanyaan atau butuh bantuan teknis? Hubungi kami melalui kontak di bawah ini.</p>
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-white/10 rounded-lg flex items-center justify-center text-primary text-xl flex-shrink-0">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-semibold">Alamat Sekolah</h4>
                                <p class="text-gray-400">Jln. Raya Poto'an Palengaan, Pamekasan, Jawa Timur</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-white/10 rounded-lg flex items-center justify-center text-primary text-xl flex-shrink-0">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-semibold">Telepon / WhatsApp</h4>
                                <p class="text-gray-400">0878-1697-3214</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-white/10 rounded-lg flex items-center justify-center text-primary text-xl flex-shrink-0">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-semibold">Email</h4>
                                <p class="text-gray-400">smkalmiftahpanyeppenputri@gmail.com</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Social Media Links -->
                     <div class="flex space-x-4 pt-4">
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-primary transition"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-primary transition"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-primary transition"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <!-- Form -->
                <div class="bg-white/5 backdrop-blur-sm p-8 rounded-2xl border border-white/10 shadow-xl">
                    <form action="" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Nama Lengkap</label>
                                <input type="text" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition" placeholder="John Doe" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                                <input type="email" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition" placeholder="john@example.com" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Pesan Anda</label>
                            <textarea rows="4" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition resize-none" placeholder="Tulis pesan Anda di sini..." required></textarea>
                        </div>
                        <button type="submit" class="w-full py-3 bg-primary hover:bg-indigo-600 text-white font-bold rounded-lg shadow-lg transform hover:-translate-y-1 transition duration-200">
                            Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black py-8 border-t border-white/10">
        <div class="container mx-auto px-6 text-center">
            <p class="text-gray-500">&copy; 2025 <span class="text-white font-medium">SMK Al-Miftah Putri</span>. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Navbar Scroll Effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('py-2');
                navbar.classList.remove('py-4');
            } else {
                navbar.classList.add('py-4');
                navbar.classList.remove('py-2');
            }
        });

        // Mobile Menu Toggle
        const menuBtn = document.getElementById('menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</body>
</html>