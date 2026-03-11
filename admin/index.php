<?php
include '../config/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SIAKAD Premium</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        heading: ['"Outfit"', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                            950: '#172554',
                        },
                        secondary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        dark: '#0f172a',
                        sidebar: '#0f172a',
                    },
                    boxShadow: {
                        'soft': '0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02)',
                        'glow': '0 0 15px rgba(59, 130, 246, 0.5)',
                    }
                }
            }
        }
    </script>
     <style>
        /* Custom scrollbar */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Glassmorphism utilities */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        .sidebar-glass {
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 1) 100%);
            backdrop-filter: blur(20px);
        }

        /* Transitions */
        .fade-in {
            animation: fadeIn 0.4s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Table styles compatibility */
        .table { width: 100%; text-align: left; border-collapse: separate; border-spacing: 0; }
        .table th { background-color: #f8fafc; color: #475569; font-weight: 600; padding: 1rem; border-bottom: 2px solid #e2e8f0; }
        .table td { padding: 1rem; border-bottom: 1px solid #e2e8f0; color: #334155; }
        .table tr:last-child td { border-bottom: none; }
        .table tr:hover td { background-color: #f8fafc; }
        
        /* Button styles compatibility */
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 0.5rem 1rem; font-weight: 500; border-radius: 0.5rem; transition: all 0.2s; cursor: pointer; text-decoration: none; border: 1px solid transparent; }
        .btn:hover { transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }
        
        .btn-primary { background-color: #3b82f6; color: white; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2); }
        .btn-primary:hover { background-color: #2563eb; box-shadow: 0 6px 8px -1px rgba(59, 130, 246, 0.3); }
        
        .btn-danger { background-color: #ef4444; color: white; box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.2); }
        .btn-danger:hover { background-color: #dc2626; box-shadow: 0 6px 8px -1px rgba(239, 68, 68, 0.3); }
        
        .btn-success { background-color: #10b981; color: white; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2); }
        .btn-success:hover { background-color: #059669; box-shadow: 0 6px 8px -1px rgba(16, 185, 129, 0.3); }
        
        .btn-warning { background-color: #f59e0b; color: white; box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.2); }
        .btn-warning:hover { background-color: #d97706; box-shadow: 0 6px 8px -1px rgba(245, 158, 11, 0.3); }

        .btn-sm { padding: 0.25rem 0.75rem; font-size: 0.875rem; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased overflow-hidden">

    <div class="flex h-screen w-full">
        <!-- Sidebar -->
        <aside class="w-72 sidebar-glass text-white flex flex-col fixed md:relative z-30 h-full transition-all duration-300 transform -translate-x-full md:translate-x-0 shadow-2xl" id="sidebar">
            <!-- Brand -->
            <div class="h-20 flex items-center justify-center border-b border-gray-700/50 bg-gray-900/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-primary-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-primary-500/30">
                        <i class="fas fa-graduation-cap text-xl text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-heading font-bold tracking-tight text-white">SIAKAD</h1>
                        <p class="text-xs text-gray-400 font-medium">Administrator Panel</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="flex-1 overflow-y-auto py-6 px-4 scrollbar-hide space-y-1">
                <p class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 font-heading">Menu Utama</p>
                <?php
                $halaman = $_GET['halaman'] ?? '';
                $menuItems = [
                    '' => ['icon' => 'fa-home', 'label' => 'Dashboard'],
                    'tahun' => ['icon' => 'fa-calendar-alt', 'label' => 'Tahun Ajaran'],
                    'guru' => ['icon' => 'fa-chalkboard-user', 'label' => 'Data Guru'],
                    'siswa' => ['icon' => 'fa-user-graduate', 'label' => 'Data Siswa'],
                    'jurusan' => ['icon' => 'fa-layer-group', 'label' => 'Jurusan'],
                    'kelas' => ['icon' => 'fa-door-open', 'label' => 'Kelas_Ruang'],
                    'kategori' => ['icon' => 'fa-clipboard-list', 'label' => 'Kategori Nilai'],
                    'mapel' => ['icon' => 'fa-book-open', 'label' => 'Mata Pelajaran'],
                    'mengajar' => ['icon' => 'fa-person-chalkboard', 'label' => 'Jadwal Mengajar'],
                ];

                foreach ($menuItems as $key => $item) {
                    $isActive = ($halaman == $key);
                    $activeClass = $isActive 
                        ? 'bg-primary-600 text-white shadow-lg shadow-primary-900/20' 
                        : 'text-gray-400 hover:bg-gray-800/50 hover:text-white hover:translate-x-1';
                    
                    $iconClass = $isActive ? 'text-white' : 'text-gray-500 group-hover:text-white';
                    $href = "index.php" . ($key ? "?halaman=$key" : "");
                    
                    echo "<a href='$href' class='group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200 mb-1 $activeClass'>
                            <div class='w-8 flex justify-center mr-2 transition-colors duration-200'>
                                <i class='fas {$item['icon']} $iconClass text-lg'></i>
                            </div>
                            {$item['label']}
                            " . ($isActive ? "<i class='fas fa-chevron-right ml-auto text-xs opacity-70'></i>" : "") . "
                          </a>";
                }
                ?>
                
                <div class="my-4 border-t border-gray-700/50"></div>
                
                <p class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 font-heading">Laporan & Arsip</p>
                <?php
                $reportItems = [
                        'alumni' => ['icon' => 'fa-user-tie', 'label' => 'Data Alumni'],
                        'kelas_naik' => ['icon' => 'fa-chart-line', 'label' => 'Kenaikan Kelas'],
                ];
                    foreach ($reportItems as $key => $item) {
                    $isActive = ($halaman == $key);
                    $activeClass = $isActive 
                        ? 'bg-primary-600 text-white shadow-lg shadow-primary-900/20' 
                        : 'text-gray-400 hover:bg-gray-800/50 hover:text-white hover:translate-x-1';
                    
                    $iconClass = $isActive ? 'text-white' : 'text-gray-500 group-hover:text-white';
                    
                    echo "<a href='index.php?halaman=$key' class='group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200 mb-1 $activeClass'>
                            <div class='w-8 flex justify-center mr-2 transition-colors duration-200'>
                                <i class='fas {$item['icon']} $iconClass text-lg'></i>
                            </div>
                            {$item['label']}
                            " . ($isActive ? "<i class='fas fa-chevron-right ml-auto text-xs opacity-70'></i>" : "") . "
                          </a>";
                }
                ?>
            </div>

            <!-- Profile/Logout Bottom -->
            <div class="p-4 border-t border-gray-700/50 bg-gray-900/30 backdrop-blur-sm">
                <a href="index.php?halaman=logout" class="flex items-center gap-3 p-2 rounded-xl hover:bg-red-500/10 hover:text-red-400 transition-all group">
                    <div class="w-8 h-8 rounded-lg bg-red-500/20 flex items-center justify-center text-red-500 group-hover:bg-red-500 group-hover:text-white transition-colors">
                        <i class="fas fa-sign-out-alt text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-300 group-hover:text-red-300">Logout</p>
                    </div>
                </a>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 h-full relative">
            <!-- Top Header -->
            <header class="h-16 glass z-20 flex items-center justify-between px-4 sm:px-6 sticky top-0 border-b border-gray-200/50 shadow-sm">
                <button class="md:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 focus:outline-none transition-colors" id="sidebar-toggle">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                
                <!-- Breadcrumbs/Page Title -->
                <div class="hidden md:flex items-center text-sm font-medium text-gray-500">
                    <span class="hover:text-primary-600 cursor-pointer transition-colors">Admin</span>
                    <i class="fas fa-chevron-right text-xs mx-2 text-gray-400"></i>
                    <span class="text-gray-800 font-semibold capitalize">
                        <?= $halaman ? str_replace('_', ' ', $halaman) : 'Dashboard' ?>
                    </span>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-4 ml-auto">
                    <!-- Notifications (Mockup) -->
                    <button class="relative p-2 text-gray-400 hover:text-primary-600 transition-colors rounded-full hover:bg-gray-100">
                        <i class="far fa-bell text-lg"></i>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>

                    <!-- Profile Dropdown -->
                    <div class="flex items-center pl-4 border-l border-gray-200">
                        <div class="text-right mr-3 hidden sm:block">
                            <p class="text-sm font-bold text-gray-800 font-heading">Administrator</p>
                            <p class="text-xs text-gray-500">Super Admin</p>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-primary-500 to-indigo-600 p-0.5 shadow-md cursor-pointer hover:shadow-lg transition-shadow">
                            <img src="https://ui-avatars.com/api/?name=Admin&background=random&color=fff" alt="Profile" class="h-full w-full rounded-full object-cover border-2 border-white">
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Area -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 md:p-8 scroll-smooth relative z-0">
                <!-- Background Decoration -->
                <div class="absolute inset-0 z-[-1] opacity-50 pointer-events-none" style="background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 24px 24px;"></div>
                
                <div class="max-w-7xl mx-auto fade-in">
                    <?php
                    // Page Routing Logic
                    if (isset($_GET['halaman'])) {
                        $hal = $_GET['halaman'];
                        $allowed_pages = [
                            'tahun', 'guru', 'guru_tambah', 'guru_edit', 'guru_hapus', 
                            'mengajar', 'siswa', 'siswa_tambah', 'siswa_hapus', 'siswamain_hapus',
                            'siswa_detail', 'siswa_edit', 'jurusan', 'kelas', 'kelas_tambah', 
                            'kelas_edit', 'kelas_naik', 'alumni', 'mapel', 'laporan_nilai', 
                            'kategori', 'siswakelas', 'prestasi_hapus', 'pelanggaran_hapus', 
                            'absensi_hapus', 'logout'
                        ];

                        if (in_array($hal, $allowed_pages)) {
                            // Security check: ensure file exists before including
                            if (file_exists($hal . '.php')) {
                                include $hal . '.php';
                            } else {
                                echo "<div class='p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50' role='alert'>
                                        <span class='font-medium'>Error!</span> Halaman tidak ditemukan.
                                      </div>";
                            }
                        } else {
                            include 'dashboard.php';
                        }
                    } else {
                        include 'dashboard.php';
                    }
                    ?>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Mobile Sidebar Overlay -->
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-20 hidden transition-opacity duration-300 md:hidden" id="sidebar-overlay"></div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const body = document.body;

        function toggleSidebar() {
            const isClosed = sidebar.classList.contains('-translate-x-full');
            if (isClosed) {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.remove('hidden');
                setTimeout(() => sidebarOverlay.classList.remove('opacity-0'), 10); // Fade in
                body.style.overflow = 'hidden'; 
            } else {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('opacity-0'); // Fade out
                setTimeout(() => sidebarOverlay.classList.add('hidden'), 300);
                body.style.overflow = '';
            }
        }

        sidebarToggle.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);
    </script>
</body>
</html>