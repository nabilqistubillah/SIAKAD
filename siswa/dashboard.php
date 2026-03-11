<?php
$id_siswa = $_SESSION['siswa']['id_siswa'];

// Ambil data kelas
$kelas = $koneksi->query("
    SELECT k.nama_kelas, j.nama_jurusan
    FROM siswakelas sk
    JOIN kelas k ON sk.id_kelas = k.id_kelas
    JOIN jurusan j ON k.id_jurusan = j.id_jurusan
    WHERE sk.id_siswa = '$id_siswa'
")->fetch_assoc();

// Ambil ringkasan absensi tahun ini
$absensi = $koneksi->query("
    SELECT SUM(hadir) as hadir, SUM(sakit) as sakit, SUM(izin) as izin, SUM(alpa) as alpa 
    FROM absensi 
    WHERE id_siswa = '$id_siswa'
")->fetch_assoc();
?>

<div class="bg-white rounded-2xl shadow-sm p-8 mb-8 flex flex-col md:flex-row items-center justify-between relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-primary/10 to-transparent"></div>
    <div class="relative z-10">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang Wali, <span class="text-primary"><?= $_SESSION['siswa']['nama_siswa'] ?></span>!</h1>
        <p class="text-gray-500 text-lg">
            <?php if ($kelas): ?>
                Kelas <span class="font-semibold text-gray-700"><?= $kelas['nama_kelas'] ?></span> &bull; <?= $kelas['nama_jurusan'] ?>
            <?php else: ?>
                <span class="text-red-500">Belum masuk kelas</span>
            <?php endif; ?>
        </p>
    </div>
    <div class="relative z-10 mt-6 md:mt-0">
        <div class="bg-white p-4 rounded-xl shadow-md border border-gray-100 flex items-center">
            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl mr-4">
                <i class="fas fa-id-card"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-widest font-semibold">NIS / NISN</p>
                <p class="text-lg font-bold text-gray-800"><?= $_SESSION['siswa']['induk_siswa'] ?></p>
            </div>
        </div>
    </div>
</div>

<h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
    <i class="fas fa-chart-pie mr-3 text-primary"></i> 
    Ringkasan Kehadiran
</h3>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Hadir -->
    <div class="bg-green-50 rounded-xl p-6 border border-green-100 text-center hover:shadow-md transition-shadow">
        <h4 class="text-4xl font-bold text-green-600 mb-1"><?= $absensi['hadir'] ?? 0 ?></h4>
        <p class="text-sm font-medium text-green-700 uppercase tracking-wide">Hadir</p>
    </div>
    
    <!-- Sakit -->
    <div class="bg-yellow-50 rounded-xl p-6 border border-yellow-100 text-center hover:shadow-md transition-shadow">
        <h4 class="text-4xl font-bold text-yellow-600 mb-1"><?= $absensi['sakit'] ?? 0 ?></h4>
        <p class="text-sm font-medium text-yellow-700 uppercase tracking-wide">Sakit</p>
    </div>

    <!-- Izin -->
    <div class="bg-blue-50 rounded-xl p-6 border border-blue-100 text-center hover:shadow-md transition-shadow">
        <h4 class="text-4xl font-bold text-blue-600 mb-1"><?= $absensi['izin'] ?? 0 ?></h4>
        <p class="text-sm font-medium text-blue-700 uppercase tracking-wide">Izin</p>
    </div>

    <!-- Alpa -->
    <div class="bg-red-50 rounded-xl p-6 border border-red-100 text-center hover:shadow-md transition-shadow">
        <h4 class="text-4xl font-bold text-red-600 mb-1"><?= $absensi['alpa'] ?? 0 ?></h4>
        <p class="text-sm font-medium text-red-700 uppercase tracking-wide">Alpa</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <h4 class="font-bold text-gray-800 mb-4 flex items-center justify-between">
            <span>Pengumuman Sekolah</span>
            <a href="#" class="text-sm text-primary hover:underline">Lihat Semua</a>
        </h4>
        <div class="space-y-4">
            <!-- Dummy Announcements -->
            <div class="flex items-start pb-4 border-b border-gray-50">
                <div class="w-10 h-10 rounded-lg bg-orange-100 text-orange-600 flex-shrink-0 flex items-center justify-center mr-3 font-bold text-xs text-center leading-tight">
                    12<br>FEB
                </div>
                <div>
                    <h5 class="font-semibold text-gray-800 text-sm">Libur Nasional Pemilu</h5>
                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">Sekolah diliburkan sehubungan dengan adanya Pemilihan Umum serentak.</p>
                </div>
            </div>
            
            <div class="flex items-start pb-4 border-b border-gray-50">
                <div class="w-10 h-10 rounded-lg bg-primary/20 text-primary flex-shrink-0 flex items-center justify-center mr-3 font-bold text-xs text-center leading-tight">
                    15<br>FEB
                </div>
                <div>
                    <h5 class="font-semibold text-gray-800 text-sm">Ujian Tengah Semester</h5>
                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">Jadwal UTS akan dimulai minggu depan. Harap persiapkan diri.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-indigo-600 rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
        <h4 class="font-bold text-lg mb-2 relative z-10">Quote Hari Ini</h4>
        <blockquote class="italic text-indigo-100 relative z-10">
            "Pendidikan adalah senjata paling mematikan di dunia, karena dengan pendidikan Anda dapat mengubah dunia."
        </blockquote>
        <p class="text-right text-sm font-semibold mt-4 relative z-10">- Nelson Mandela</p>
    </div>
</div>
