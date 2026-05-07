<?php
session_start();
include '../config/config.php';
// Cek sesi siswa
if (!isset($_SESSION['siswa'])) {
    echo "<script>alert('Anda harus login terlebih dahulu'); location='../index.php';</script>";
    exit();
}
$siswa = $_SESSION['siswa'];

// Ambil tahun ajaran terbaru
$ambil_tahun = $koneksi->query("SELECT tahun_ajaran FROM tahun ORDER BY id_tahun DESC LIMIT 1");
$tahun_aktif = $ambil_tahun->fetch_assoc();
$tahun_label = $tahun_aktif ? $tahun_aktif['tahun_ajaran'] : '-';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siswa Dashboard - SIAKAD</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                        sidebar: '#1e293b',
                    }
                }
            }
        }
    </script>
     <style>
        /* Custom scrollbar for sidebar */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Compatibility for existing Bootstrap classes in included files */
        .table { width: 100%; text-align: left; border-collapse: collapse; margin-bottom: 1rem; }
        .table th, .table td { padding: 0.75rem; vertical-align: top; border-top: 1px solid #dee2e6; }
        .table-bordered { border: 1px solid #dee2e6; }
        .table-bordered th, .table-bordered td { border: 1px solid #dee2e6; }
        .table-light th { background-color: #f8f9fa; color: #495057; font-weight: 600; }
        
        .btn { display: inline-block; font-weight: 400; text-align: center; vertical-align: middle; user-select: none; border: 1px solid transparent; padding: 0.375rem 0.75rem; font-size: 1rem; line-height: 1.5; border-radius: 0.25rem; transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out; text-decoration: none; cursor: pointer; }
        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.875rem; line-height: 1.5; border-radius: 0.2rem; }
        .btn-danger { color: #fff; background-color: #dc3545; border-color: #dc3545; }
        .btn-danger:hover { background-color: #c82333; border-color: #bd2130; }
        .btn-warning { color: #212529; background-color: #ffc107; border-color: #ffc107; }
        .btn-success { color: #fff; background-color: #28a745; border-color: #28a745; }
        .btn-primary { color: #fff; background-color: #007bff; border-color: #007bff; }
        .btn-secondary { color: #fff; background-color: #6c757d; border-color: #6c757d; }

        .badge { display: inline-block; padding: 0.25em 0.4em; font-size: 75%; font-weight: 700; line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: 0.25rem; color: #fff; }
        .bg-secondary { background-color: #6c757d !important; }
        .bg-success { background-color: #28a745 !important; }
        .bg-danger { background-color: #dc3545 !important; }
        
        .alert { position: relative; padding: 0.75rem 1.25rem; margin-bottom: 1rem; border: 1px solid transparent; border-radius: 0.25rem; }
        .alert-info { color: #0c5460; background-color: #d1ecf1; border-color: #bee5eb; }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-sidebar text-white flex flex-col fixed md:relative z-30 h-full transition-transform transform -translate-x-full md:translate-x-0" id="sidebar">
            <div class="h-16 flex items-center justify-center border-b border-gray-700 bg-black/20">
                <div class="flex items-center gap-3">
                    <i class="fas fa-graduation-cap text-2xl text-primary"></i>
                    <h1 class="text-xl font-bold tracking-wide">SMK Al-Miftah</h1>
                </div>
            </div>

            <!-- User Profile in Sidebar -->
            <div class="p-6 border-b border-gray-700 text-center">
                 <div class="w-20 h-20 mx-auto rounded-full bg-gray-600 mb-3 overflow-hidden border-2 border-primary">
                    <?php if (!empty($siswa['foto_siswa'])): ?>
                        <img src="../siswa-foto/<?= $siswa['foto_siswa'] ?>" alt="Foto" class="w-full h-full object-cover">
                    <?php else: ?>
                        <i class="fas fa-user text-4xl text-gray-400 mt-4"></i>
                    <?php endif; ?>
                </div>
                <h3 class="font-semibold truncate"><?= $siswa['nama_siswa'] ?></h3>
                <p class="text-xs text-gray-400 mt-1"><?= $siswa['induk_siswa'] ?></p>
            </div>

            <div class="flex-1 overflow-y-auto py-4 scrollbar-hide">
                <nav class="space-y-1 px-2">
                    <?php
                    $halaman = $_GET['halaman'] ?? '';
                    $menuItems = [
                        '' => ['icon' => 'fa-home', 'label' => 'Beranda'],
                        'profil' => ['icon' => 'fa-user', 'label' => 'Data Pribadi'],
                        'nilai' => ['icon' => 'fa-clipboard-list', 'label' => 'Nilai & Prestasi'],
                        'absensi' => ['icon' => 'fa-calendar-check', 'label' => 'Absensi & Pelanggaran'],
                    ];

                    foreach ($menuItems as $key => $item) {
                        $activeClass = ($halaman == $key) ? 'bg-primary text-white shadow-md border-l-4 border-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white border-l-4 border-transparent';
                        $href = "index.php" . ($key ? "?halaman=$key" : "");
                        echo "<a href='$href' class='group flex items-center px-4 py-3 text-sm font-medium transition-all $activeClass'>
                                <i class='fas {$item['icon']} w-6 text-center mr-3'></i>
                                {$item['label']}
                              </a>";
                    }
                    ?>
                </nav>
            </div>

            <div class="p-4 border-t border-gray-700">
                <a href="index.php?halaman=logout" class="flex items-center px-4 py-2 text-sm font-medium text-red-400 hover:bg-red-500/10 hover:text-red-300 rounded-md transition-colors w-full justify-center">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    Logout
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 z-20">
                <button class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none" id="sidebar-toggle">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                
                <h2 class="text-xl font-semibold text-gray-800 ml-4 md:ml-0">
                    <?php
                    if ($halaman == 'profil') echo "Data Pribadi";
                    elseif ($halaman == 'nilai') echo "Nilai & Prestasi";
                    elseif ($halaman == 'absensi') echo "Absensi & Pelanggaran";
                    else echo "Dashboard";
                    ?>
                </h2>
                
                <!-- Right Actions -->
                <div class="flex items-center gap-4 ml-auto">
                     <div class="text-gray-500 text-sm hidden md:flex items-center mr-2 border-r border-gray-200 pr-4">
                         <i class="far fa-calendar-alt mr-2"></i>
                         Tahun Ajaran: <span class="text-primary font-bold ml-1"><?= $tahun_label ?></span>
                     </div>
                    
                    <!-- Profile Section -->
                    <div class="flex items-center">
                        <div class="text-right mr-3 hidden sm:block">
                            <p class="text-sm font-bold text-gray-800 font-sans"><?= $siswa['nama_siswa'] ?></p>
                            <p class="text-xs text-gray-500">Siswa Aktif</p>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-primary to-secondary p-0.5 shadow-md cursor-pointer hover:shadow-lg transition-shadow">
                            <?php if (!empty($siswa['foto_siswa'])): ?>
                                <img src="../siswa-foto/<?= $siswa['foto_siswa'] ?>" alt="Profile" class="h-full w-full rounded-full object-cover border-2 border-white">
                            <?php else: ?>
                                <div class="h-full w-full rounded-full bg-gray-100 flex items-center justify-center border-2 border-white">
                                    <i class="fas fa-user text-gray-400 text-sm"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Area -->
            <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50">
                <div class="max-w-6xl mx-auto">
                    <?php
                    if (isset($_GET['halaman'])) {
                        $hal = $_GET['halaman'];
                        if ($hal == 'profil') include 'profil.php';
                        elseif ($hal == 'nilai') include 'nilai.php';
                        elseif ($hal == 'absensi') include 'absensi.php';
                        elseif ($hal == 'logout') include 'logout.php';
                        else include 'dashboard.php';
                    } else {
                        include 'dashboard.php';
                    }
                    ?>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Mobile Sidebar Overlay -->
    <div class="fixed inset-0 bg-black/50 z-20 hidden md:hidden" id="sidebar-overlay"></div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        function toggleSidebar() {
            const isClosed = sidebar.classList.contains('-translate-x-full');
            if (isClosed) {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            }
        }

        sidebarToggle.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);
    </script>
</body>
</html>